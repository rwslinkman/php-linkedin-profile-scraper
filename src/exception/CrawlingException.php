<?php
namespace rwslinkman\linkedinprofilescraper\exception;

class CrawlingException extends \Exception
{
    private string $screenshotLocation;

    public function __construct(string $message, string $screenshotLocation) {
        parent::__construct($message);
        $this->screenshotLocation = $screenshotLocation;
    }

    /**
     * @return string
     */
    public function getScreenshotLocation(): string
    {
        return $this->screenshotLocation;
    }
}