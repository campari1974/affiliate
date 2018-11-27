<?php
/**
 * Project      affiliatetheme-belboon
 * @author      Giacomo Barbalinardo <info@ready24it.eu>
 * @copyright   2015
 */

namespace Endcore\Belboon;


class Feed
{
    /**
     * @var stdClass
     */
    protected $feed;

    public function __construct($entity){
        $this->feed = $entity;
    }

    /**
     * @return int
     */
    public function getId(){
        return $this->feed['id'];
    }

    /**
     * @return string
     */
    public function getName(){
        return $this->feed['program_name'];
    }
}