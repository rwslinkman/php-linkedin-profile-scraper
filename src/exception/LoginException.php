<?php
namespace rwslinkman\linkedinprofilescraper\exception;

use Exception;

class LoginException extends Exception
{
    private string $screenshotLocation;

    /**
     * @param string $message
     * @param string $screenshotLocation
     */
    public function __construct(string $message, string $screenshotLocation)
    {
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