<?php
/**
 * Project: affiliate_affilinet
 * (c) 2014 Giacomo Barbalinardo <info@ready24it.eu>
 * Date: 04.12.2014
 * Time: 01:28
 */

namespace Endcore;


class Category {
    protected $_category;

    public function __construct($category){
        $this->_category = $category;
    }

    public function getCategoryId(){
        return $this->_category->Id;
    }

    public function getParentCategoryId(){
        return $this->_category->IdPath;
    }

    public function getName(){
        return $this->_category->Title;
    }

    public function getCategoryPath(){
        return str_replace(',', ' > ', $this->_category->TitlePath);
    }

    public function getTotalProducts(){
        return $this->_category->ProductCount;
    }

    public function getJson()
    {
        return 'toimplement';
    }
} 