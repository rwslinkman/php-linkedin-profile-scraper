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

    /**
     * @param string $username
     * @param bool $useHeadless
     * @return array
     * @throws NoSuchElementException
     * @throws TimeoutException
     */
    public function scrape(string $username = "rwslinkman", bool $useHeadless = false): array
    {
        $endpoint = self::LINKEDIN_PUBLIC_PROFILE_URL . "/in/$username/details/experience/";
        if ($useHeadless) {
            $client = Client::createChromeClient();
        } else {
            $userAgent = "chrome";
            $options = [
                "--user-agent=$userAgent"
            ];
            $client = Client::createChromeClient(null, $options);
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
                "session_key" => "your-email-here",
                "session_password" => "your-password-here"
            ]);
            $client->submit($loginForm);
            $client->waitForVisibility('.authentication-outlet');
            return true;
        } catch(Exception) {
            $client->takeScreenshot('out/scraper-login-error.png');
            return false;
        }
    }
}