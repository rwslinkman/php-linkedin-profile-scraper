<?php
include_once "vendor/autoload.php";
use rwslinkman\linkedinprofilescraper\data\LinkedinPositionData;
use rwslinkman\linkedinprofilescraper\LinkedInProfileScraper;

// Scraper input data
$visitorEmail = "your-email-here@someprovider.com";
$visitorPassword = "your-linkedin-password";
$targetProfile = "rwslinkman";

echo "Scraper script started".PHP_EOL;
$scraper = new LinkedInProfileScraper($visitorEmail, $visitorPassword, false);
$results = $scraper->scrape($targetProfile);
/** @var LinkedinPositionData $result */
foreach($results as $result) {
    $c = $result->getCompany();
    $p = $result->getJobTitle();
    $s = $result->getJobStartedAt();
    $e = $result->getJobEndedAt();
    $d = $result->getDuration();
    echo "$targetProfile worked at $c as $p from $s to $e ($d)".PHP_EOL;
}
echo "Scraper script ended".PHP_EOL;