<?php
/**
 * Project      affiliatetheme-belboon
 * @author      Giacomo Barbalinardo <info@ready24it.eu>
 * @copyright   2015
 */

namespace Endcore\Belboon;


class FeedList extends SeekableList
{
    public function __construct($response)
    {
        $this->_totalResults = $response->NumRecords;
        if ($this->_totalResults === 0) {
            throw new \Exception('No Platforms found.');
        }

        $this->_results = $response->Records;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return new Feed($this->_results[$this->_currentIndex]);
    }
} 