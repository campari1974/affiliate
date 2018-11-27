<?php
/**
 * Project: affiliate_affilinet
 * (c) 2014 Giacomo Barbalinardo <info@ready24it.eu>
 * Date: 03.12.2014
 * Time: 01:57
 */

namespace Endcore;

class Shop {

    /**
     * @var stdClass
     */
    protected $_shop;

    public function __construct($shop){
        $this->_shop = $shop;
    }

    /**
     * @return int
     */
    public function getShopId(){
        return $this->_shop->ShopId;
    }

    /**
     * @return int
     */
    public function getProgramId(){
        return $this->_shop->ProgramId;
    }

    /**
     * @return string
     */
    public function getName(){
        return $this->_shop->ShopTitle;
    }

    /**
     * @return string
     */
    public function getLastUpdate(){
        return $this->_shop->LastUpdate;
    }

    /**
     * @return string
     */
    public function getLogoUrl(){
        return $this->_shop->Logo->URL;
    }

    /**
     * @return int
     */
    public function getTotalProducts(){
        return $this->_shop->ProductCount;
    }

    public function getJson()
    {
        return 'toimplement';
    }
    
} 