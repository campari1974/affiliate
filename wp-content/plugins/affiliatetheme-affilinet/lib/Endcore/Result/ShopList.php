<?php
/**
 * Project: affiliate_affilinet
 * (c) 2014 Giacomo Barbalinardo <info@ready24it.eu>
 * Date: 03.12.2014
 * Time: 00:43
 */

namespace Endcore\Result;

use Endcore\Shop;

class ShopList extends SeekableList
{
    public function __construct($response)
    {
        $this->_totalResults = $response->GetShopListSummary->Records;
        if ($this->_totalResults === 0) {
            throw new \Exception('No Shops found.');
        }

        $this->_results = $response->Shops->Shop;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        if(is_array($this->_results)) {
            return new Shop($this->_results[$this->_currentIndex]);
        }
        return new Shop($this->_results);
    }

    public function getData()
    {
        $result = array();
        $current_shop = get_option('affilinet_shop');
        if(is_array($this->_results)) {
            foreach ($this->_results as $shop_tmp) {
                $shop = new Shop($shop_tmp);
                $current = 'false';

                if ($current_shop == $shop->getShopId())
                    $current = 'true';

                $result['items'][] = array(
                    'id' => $shop->getShopId(),
                    'name' => $shop->getName(),
                );
            }
        }
        else
        {
            $shop = new Shop($this->_results);
            $result['items'][] = array(
                'id' => $shop->getShopId(),
                'name' => $shop->getName(),
            );
        }
    return $result;
    }
} 