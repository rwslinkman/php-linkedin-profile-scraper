<?php

namespace rwslinkman\linkedinprofilescraper\data\manipulation;

class DataCleaner
{
    private const SPLIT_CHAR = "·";

    // "jul. 2015 - jun. 2018 · 3 jr"
    //  "Philips Innovation Services · Tijdelijk contract"

    public static function splitTimeSpentForDuration(string $durationAndLength) {
        $parts = explode(self::SPLIT_CHAR, $durationAndLength);
        $section = $parts[1];
        return trim($section);
    }

    public static function splitTimeSpentForJobStart(string $durationAndLength) {
        $parts = explode(self::SPLIT_CHAR, $durationAndLength);
        $section = trim($parts[0]);
        $moreParts = explode("-", $section);
        return trim($moreParts[0]);
    }

    public static function splitTimeSpentForJobEnd(string $durationAndLength) {
        $parts = explode(self::SPLIT_CHAR, $durationAndLength);
        $section = trim($parts[0]);
        $moreParts = explode("-", $section);
        return trim($moreParts[1]);
    }

    public static function cleanCompanyExtras(string $company) {
        $parts = explode(self::SPLIT_CHAR, $company);
        return trim($parts[0]);
    }
}