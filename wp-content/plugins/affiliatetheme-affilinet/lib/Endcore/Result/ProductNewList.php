<?php
/**
 * Created by affiliatetheme-affilinet.
 * User: Giacomo
 * Date: 10.06.2015
 * Time: 23:01
 */

namespace Endcore\Result;

use Endcore\ProductNew;


class ProductNewList extends SeekableList
{
    protected $_totalPages;

    public function __construct($response)
    {
        $this->_totalResults = $response->ProductsSummary->Records;
        if ($this->_totalResults === 0) {
            throw new \Exception('No Products found.');
        }

        $this->_totalPages = $response->ProductsSummary->TotalPages;
        $this->_results = $response->Products->Product;
    }

    public function current()
    {
        if(!is_array($this->_results)) {
            return new ProductNew($this->_results);
        }

        return new ProductNew($this->_results[$this->_currentIndex]);
    }

    public function getTotalPages()
    {
        return $this->_totalPages;
    }
}