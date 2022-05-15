<?php
namespace rwslinkman\linkedinprofilescraper\data;

class LinkedinPositionRawData
{
    private string $title;
    private string $company;
    private string|null $location;
    private string $timeSpent;

    /**
     * @param string $title
     * @param string $company
     * @param string|null $location
     * @param string $timeSpent
     */
    public function __construct(string $title, string $company, string|null $location, string $timeSpent)
    {
        $this->title = $title;
        $this->company = $company;
        $this->location = $location;
        $this->timeSpent = $timeSpent;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getCompany(): string
    {
        return $this->company;
    }

    /**
     * @param string $company
     */
    public function setCompany(string $company): void
    {
        $this->company = $company;
    }

    /**
     * @return string|null
     */
    public function getLocation(): string|null
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    /**
     * @return string
     */
    public function getTimeSpent(): string
    {
        return $this->timeSpent;
    }

    /**
     * @param string $timeSpent
     */
    public function setTimeSpent(string $timeSpent): void
    {
        $this->timeSpent = $timeSpent;
    }
}