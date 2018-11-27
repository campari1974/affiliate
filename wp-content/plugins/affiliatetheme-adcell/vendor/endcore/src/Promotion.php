<?php
/**
 * Project      affiliatetheme-adcell
 * @author      Giacomo Barbalinardo <info@ready24it.eu>
 * @copyright   2016
 */

namespace EcAdcell;

use GuzzleHttp\Client;
use League\Csv\Reader;

class Promotion
{
    protected $originalData;
    protected $slotId;


    /**
     * Program constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->originalData = $data;
    }

    public function getId()
    {
        return $this->originalData->promotionId;
    }

    public function getAppId()
    {
        return $this->originalData->programId;
    }

    public function getDescription()
    {
        return $this->originalData->description;
    }

    public function getChangeTime()
    {
        return new \DateTime($this->originalData->changeTime);
    }

    public function getCsvUrl()
    {
        return 'http:' . $this->originalData->csvUrl;
    }

    public function getSlotId()
    {
        if ($this->slotId === null) {
            $urlParts = explode('/', $this->getCsvUrl());
            $position = array_search('slotId', $urlParts) + 1;
            $slotId = array_slice($urlParts, $position, 1);
            if (count($slotId) === 1) {
                $this->slotId = $slotId[0];
            }
        }

        return $this->slotId;
    }

    public function getFileName()
    {
        return $this->getId() . '-' . $this->getSlotId() . '.csv';
    }

    public function getStoragePath()
    {
        //return realpath(__DIR__ .'/../../../storage/');
        return ADCELL_CSV_PATH;
    }

    public function getFilePath()
    {
        return $this->getStoragePath() . '/' . $this->getFileName();
    }

    public function fileExists()
    {
        return file_exists($this->getFilePath());
    }

    public function fileIsUpToDate()
    {
        $fileTime = filemtime($this->getFilePath());
        if (new \DateTime("@$fileTime") > $this->getChangeTime()) {
            return true;
        }

        return false;
    }

    public function getLatestCsv()
    {
        // check if File exists
        if (!$this->fileExists() || !$this->fileIsUpToDate()) {
            // Download the File
            $client = new Client();
            $resource = fopen($this->getFilePath(), 'w');
            $client->request('GET', $this->getCsvUrl(), ['sink' => $resource]);
        }

        return Reader::createFromPath($this->getFilePath());
    }
}