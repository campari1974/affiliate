<?php
/**
 * Project: affiliate_affilinet
 * (c) 2014 Giacomo Barbalinardo <info@ready24it.eu>
 * Date: 04.12.2014
 * Time: 18:42
 */

namespace Endcore\Result;

use Endcore\Product;

class SingleProductList extends SeekableList {

    /**
     * SingleProductList constructor.
     * @param $response
     * @throws \Exception
     */
    public function __construct($response)
    {
        $this->_totalResults = $response->ProductsSummary->Records;
        if ($this->_totalResults === 0) {
            throw new \Exception('Product not found.');
        }

        $this->_results = $response->Products->Product;
    }

    public function current()
    {
        return new Product($this->_results);
    }
} 