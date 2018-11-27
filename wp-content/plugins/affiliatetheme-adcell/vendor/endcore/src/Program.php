<?php
/**
 * Project      affiliatetheme-adcell
 * @author      Giacomo Barbalinardo <info@ready24it.eu>
 * @copyright   2016
 */

namespace EcAdcell;


class Program
{
    protected $originalData;

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
        return $this->originalData->programId;
    }

    public function getName()
    {
        return $this->originalData->programName;
    }

    public function getLogo()
    {
        return $this->originalData->programLogoUrl;
    }
}