<?php
/**
 * Project      affiliatetheme-belboon
 * @author      Giacomo Barbalinardo <info@ready24it.eu>
 * @copyright   2015
 */

namespace Endcore\Belboon;

class Platform {

    /**
     * @var stdClass
     */
    protected $platform;

    public function __construct($entity){
        $this->platform = $entity;
    }

    /**
     * @return int
     */
    public function getPlatformId(){
        return $this->platform['id'];
    }

    /**
     * @return string
     */
    public function getName(){
        return $this->platform['name'];
    }
} 