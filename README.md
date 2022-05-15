# PHP LinkedIn Profile Scraper
Scraper tool to fetch public LinkedIn profile data and convert it to PHP data objects

###Disclaimer: This tool is used for personal data logging. Not intended to harvest other people's data.

Uses `symfony/panther` to manipulate browser into visiting LinkedIn page of `$targetProfile` user.    
Full example can be found in `index.php`.   

### Example
```php
$scraper = new LinkedInProfileScraper($visitorEmail, $visitorPassword);
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
```