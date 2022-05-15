<?php
namespace rwslinkman\linkedinprofilescraper;

use Exception;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeoutException;
use rwslinkman\linkedinprofilescraper\data\LinkedinPositionData;
use rwslinkman\linkedinprofilescraper\data\LinkedinPositionRawData;
use rwslinkman\linkedinprofilescraper\data\manipulation\DataCleaner;
use rwslinkman\linkedinprofilescraper\data\manipulation\DataMapper;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\DomCrawler\Crawler;

class LinkedInProfileScraper
{
    private const LINKEDIN_PUBLIC_PROFILE_URL = "https://www.linkedin.com";

    private bool $useHeadlessBrowser;
    private string $scrapeAsUsername;
    private string $scrapeAsPassword;
    private string $outputDirectory;
    private string $userAgent;

    /**
     * @param bool $useHeadlessBrowser
     * @param string $scrapeAsUsername
     * @param string $scrapeAsPassword
     * @param string $outputDirectory
     * @param string $userAgent
     */
    public function __construct(string $scrapeAsUsername, string $scrapeAsPassword, bool $useHeadlessBrowser = true, string $outputDirectory = "out", string $userAgent = "chrome")
    {
        $this->useHeadlessBrowser = $useHeadlessBrowser;
        $this->scrapeAsUsername = $scrapeAsUsername;
        $this->scrapeAsPassword = $scrapeAsPassword;
        $this->outputDirectory = $outputDirectory;
        $this->userAgent = $userAgent;
    }


    /**
     * @param string $username
     * @return array
     * @throws NoSuchElementException
     * @throws TimeoutException
     */
    public function scrape(string $username): array
    {
        $endpoint = self::LINKEDIN_PUBLIC_PROFILE_URL . "/in/$username/details/experience/";
        if ($this->useHeadlessBrowser) {
            $client = Client::createChromeClient();
        } else {
            $client = Client::createChromeClient(null, [
                "--user-agent=$this->userAgent"
            ]);
        }

        $isLoginSuccess = $this->linkedinLogin($client);
        if(!$isLoginSuccess) return array();

        $client->request("GET", $endpoint);
        $profileCrawler = $client->waitForVisibility("#main ul.pvs-list");

        $rawScrapeResults = $profileCrawler->filter("#main ul.pvs-list")->children()->each(function(Crawler $node) {
            // determine type
            $subWorkItems = $node->filter("ul.pvs-list li ul.pvs-list div.pvs-entity");
            $isComposite = $subWorkItems->count() > 0;
            // convert to raw data objects
            $convertResult = array();
            if($isComposite) {
                // Multiple jobs at same employer
                $compositeResults = DataMapper::mapComposite($node);
                foreach($compositeResults as $singleResult) {
                    $convertResult[] = $singleResult;
                }
            } else {
                // Single employment
                $singleResult = DataMapper::mapRegular($node);
                if($singleResult != null) {
                    $convertResult[] = $singleResult;
                }
            }
            return $convertResult[0];
        });

        $cleanResults = array();
        /** @var LinkedinPositionRawData $rawResult */
        foreach($rawScrapeResults as $rawResult) {
            $jobStart = DataCleaner::splitTimeSpentForJobStart($rawResult->getTimeSpent());
            $jobEnd = DataCleaner::splitTimeSpentForJobEnd($rawResult->getTimeSpent());
            $duration = DataCleaner::splitTimeSpentForDuration($rawResult->getTimeSpent());
            $cleanCompany = DataCleaner::cleanCompanyExtras($rawResult->getCompany());
            $cleanResults[] = new LinkedinPositionData(
                $rawResult->getTitle(),
                $cleanCompany,
                $rawResult->getLocation(),
                $jobStart,
                $jobEnd,
                $duration
            );
        }
        return $cleanResults;
    }

    /**
     * @param Client $client
     * @return bool
     */
    private function linkedinLogin(Client $client): bool
    {
        try {
            $loginEndpoint = self::LINKEDIN_PUBLIC_PROFILE_URL . "/checkpoint/lg/login";
            $client->request("GET", $loginEndpoint);
            $loginCrawler = $client->waitForVisibility("#username");
            $loginForm = $loginCrawler->selectButton("Sign in")->form([
                "session_key" => $this->scrapeAsUsername,
                "session_password" => $this->scrapeAsPassword
            ]);
            $client->submit($loginForm);
            $client->waitForVisibility('.authentication-outlet');
            return true;
        } catch(Exception) {
            $client->takeScreenshot($this->outputDirectory.'/scraper-login-error.png');
            return false;
        }
    }
}