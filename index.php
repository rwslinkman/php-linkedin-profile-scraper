<?php
include_once "vendor/autoload.php";
use rwslinkman\linkedinprofilescraper\LinkedInProfileScraper;

echo "Hello world".PHP_EOL;

$scraper = new LinkedInProfileScraper();
$result = $scraper->scrape();
var_dump($result);
echo PHP_EOL;
echo "Scraper script ended".PHP_EOL;