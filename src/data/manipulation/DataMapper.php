<?php
namespace rwslinkman\linkedinprofilescraper\data\manipulation;

use rwslinkman\linkedinprofilescraper\data\LinkedinPositionRawData;
use Symfony\Component\Panther\DomCrawler\Crawler;

class DataMapper
{
    public static function mapComposite(Crawler $node): array
    {
        $positionCompany = self::mapCompositeCompany($node);

        $compositeListCrawler = $node->filter("ul.pvs-list li ul.pvs-list div.pvs-entity");
        $compositePositions = $compositeListCrawler->each(function(Crawler $itemCrawler) {
            $compositeItemData = $itemCrawler->filter("span[aria-hidden=true]");
            $positionTitle = $compositeItemData->getElement(0)->getText();
            $positionDurationAndLength = $compositeItemData->getElement(1)->getText();
            $positionLocation = $compositeItemData->getElement(2)->getText();
            return new LinkedinPositionRawData($positionTitle, "", $positionLocation, $positionDurationAndLength);
        });
        foreach($compositePositions as $position) {
            $position->setCompany($positionCompany);
        }
        return $compositePositions;
    }

    public static function mapRegular(Crawler $node): ?LinkedinPositionRawData
    {
        $positionData = $node->filter("span[aria-hidden=true]");
        if($positionData->count() == 0) return null;

        $positionTitle = $positionData->getElement(0)->getText();
        $positionCompany = $positionData->getElement(1)->getText();
        $positionDurationAndLength = $positionData->getElement(2)->getText();
        $positionLocation = null;
        if($positionData->count() > 3) {
            $positionLocation = $positionData->getElement(3)->getText();
        }
        return new LinkedinPositionRawData($positionTitle, $positionCompany, $positionLocation, $positionDurationAndLength);
    }

    /**
     * @param Crawler $node
     * @return string
     */
    private static function mapCompositeCompany(Crawler $node): string
    {
        $companyResults = $node->filter("div > a")->each(function (Crawler $compCrawler, $i) {
            if ($i !== 1) return null;
            $itemData = $compCrawler->filter("span[aria-hidden=true]");
            $data = $itemData->each(function (Crawler $ic) {
                return $ic->getText();
            });
            return $data[0];
        });
        $companyResults = array_filter($companyResults);
        sort($companyResults);
        return $companyResults[0];
    }
}