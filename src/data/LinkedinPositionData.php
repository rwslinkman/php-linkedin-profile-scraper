<?php

namespace rwslinkman\linkedinprofilescraper\data;

class LinkedinPositionData
{
    private string $jobTitle;
    private string $company;
    private string $location;
    private string $jobStartedAt;
    private string $jobEndedAt;
    private string $duration;

    /**
     * @param string $jobTitle
     * @param string $company
     * @param string $location
     * @param string $jobStartedAt
     * @param string $jobEndedAt
     * @param string $duration
     */
    public function __construct(string $jobTitle, string $company, string $location, string $jobStartedAt, string $jobEndedAt, string $duration)
    {
        $this->jobTitle = $jobTitle;
        $this->company = $company;
        $this->location = $location;
        $this->jobStartedAt = $jobStartedAt;
        $this->jobEndedAt = $jobEndedAt;
        $this->duration = $duration;
    }

    /**
     * @return string
     */
    public function getJobTitle(): string
    {
        return $this->jobTitle;
    }

    /**
     * @param string $jobTitle
     */
    public function setJobTitle(string $jobTitle): void
    {
        $this->jobTitle = $jobTitle;
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
     * @return string
     */
    public function getLocation(): string
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
    public function getJobStartedAt(): string
    {
        return $this->jobStartedAt;
    }

    /**
     * @param string $jobStartedAt
     */
    public function setJobStartedAt(string $jobStartedAt): void
    {
        $this->jobStartedAt = $jobStartedAt;
    }

    /**
     * @return string
     */
    public function getJobEndedAt(): string
    {
        return $this->jobEndedAt;
    }

    /**
     * @param string $jobEndedAt
     */
    public function setJobEndedAt(string $jobEndedAt): void
    {
        $this->jobEndedAt = $jobEndedAt;
    }

    /**
     * @return string
     */
    public function getDuration(): string
    {
        return $this->duration;
    }

    /**
     * @param string $duration
     */
    public function setDuration(string $duration): void
    {
        $this->duration = $duration;
    }
}