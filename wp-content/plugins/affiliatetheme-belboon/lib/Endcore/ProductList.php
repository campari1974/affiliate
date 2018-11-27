<?php
/**
 * Project      affiliatetheme-belboon
 * @author      Giacomo Barbalinardo <info@ready24it.eu>
 * @copyright   2015
 */

namespace Endcore\Belboon;


class ProductList extends SeekableList
{
    protected $_totalPages;

    public function __construct($response)
    {
        $this->_totalResults = $response->NumRecords;
        if ($this->_totalResults === 0) {
            throw new \Exception('No Products found.');
        }

        $this->_results = $response->Records;
        $this->_totalPages = ceil($response->NumRecordsTotal / $this->_totalResults);
    }

    /**
     * @return Product
     */
    public function current()
    {
        return new Product($this->_results[$this->_currentIndex]);
    }

    public function getTotalPages()
    {
        return $this->_totalPages;
    }
} 