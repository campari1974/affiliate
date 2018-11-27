<?php
/**
 * Project: affiliate_affilinet
 * (c) 2014 Giacomo Barbalinardo <info@ready24it.eu>
 * Date: 04.12.2014
 * Time: 04:57
 */

namespace Endcore\Result;

use Endcore\Product;

class ProductList extends SeekableList{

    protected $_totalPages;

    public function __construct($response)
    {
        $this->_totalResults = $response->ProductSearchResult->Records;
        if ($this->_totalResults === 0) {
            throw new \Exception('No Products found.');
        }

        $this->_totalPages = $response->ProductSearchResult->TotalPages;
        $this->_results = $response->ProductSearchResult->Products->Product;
    }

    public function current()
    {
        return new Product($this->_results[$this->_currentIndex]);
    }

    public function getTotalPages()
    {
        return $this->_totalPages;
    }
} 