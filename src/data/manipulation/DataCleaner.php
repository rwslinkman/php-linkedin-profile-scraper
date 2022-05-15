<?php

namespace rwslinkman\linkedinprofilescraper\data\manipulation;

class DataCleaner
{
    private const SPLIT_CHAR = "·";
    private const SPLIT_CHAR_JOB = "-";

    public static function splitTimeSpentForDuration(string $durationAndLength): string
    {
        $parts = explode(self::SPLIT_CHAR, $durationAndLength);
        $section = $parts[1];
        return trim($section);
    }

    public static function splitTimeSpentForJobStart(string $durationAndLength): string
    {
        $parts = explode(self::SPLIT_CHAR, $durationAndLength);
        $section = trim($parts[0]);
        $moreParts = explode(self::SPLIT_CHAR_JOB, $section);
        return trim($moreParts[0]);
    }

    public static function splitTimeSpentForJobEnd(string $durationAndLength): string
    {
        $parts = explode(self::SPLIT_CHAR, $durationAndLength);
        $section = trim($parts[0]);
        $moreParts = explode(self::SPLIT_CHAR_JOB, $section);
        return trim($moreParts[1]);
    }

    public static function cleanCompanyExtras(string $company): string
    {
        $parts = explode(self::SPLIT_CHAR, $company);
        return trim($parts[0]);
    }
}