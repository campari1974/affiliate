<?php
/**
 * Project      affiliatetheme-adcell
 * @author      Giacomo Barbalinardo <info@ready24it.eu>
 * @copyright   2016
 */

namespace EcAdcell;


use League\Csv\Reader;

class Search
{
    protected $api;
    protected $fetchedRowsIndex = array();

    public function __construct()
    {
        $this->api = new Adcell();
    }

    /**
     * Determine if a given string contains a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    public static function contains($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ($needle != '' && strpos($haystack, $needle) !== false) {
                return true;
            }
        }

        return false;
    }

    public function findByPromotionIdAndKeyword($promotionId, $keyword)
    {
        /** @var Promotion $promotion */
        $promotion = $this->api->getPromotionTypeCsvById($promotionId);

        /** @var Reader $csvFile */
        $csvFile = $promotion->getLatestCsv();

        $filter = function ($row, $index) use ($keyword){
            if ($this->contains(strtolower($row[1]), strtolower($keyword))) {
                $this->fetchedRowsIndex[] = array(
                    'id' => $index
                );
                return $row;
            }
        };
        $csvFile->setDelimiter(';')
                ->addFilter($filter);

        $results = $csvFile->fetchAll();

        return new ItemList($this->fetchedRowsIndex, $results, $csvFile->fetchOne(0), $promotionId);
    }

    public function getItemByUniqueId($uniqueId)
    {
        $uniqueIds = explode(':', $uniqueId);
        $promotionId = $uniqueIds[0];
        $rowId = $uniqueIds[1];

        /** @var Promotion $promotion */
        $promotion = $this->api->getPromotionTypeCsvById($promotionId);

        /** @var Reader $csvFile */
        $csvFile = $promotion->getLatestCsv();
        $csvFile->setDelimiter(';');

        $results = $csvFile->fetchOne($rowId);

        $extras = array(
            'promoId' => $promotionId,
            'id'      => $rowId
        );

		$csvFile_arr = $csvFile->fetchOne(0);
		
		$count = min(count($csvFile_arr), count($results));
			
        return new Item(array_combine(array_slice($csvFile_arr, 0, $count), array_slice($results, 0, $count)) + $extras);
    }

}