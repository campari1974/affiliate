<?php
/**
 * Project: affiliate_affilinet
 * (c) 2014 Giacomo Barbalinardo <info@ready24it.eu>
 * Date: 05.12.2014
 * Time: 16:06
 */

namespace Endcore\Api;


use Endcore\Category;
use Endcore\Product;
use Endcore\Shop;

class JsonResponse {

    /**
     * @var string
     */
    protected $_message;

    /**
     * @var array
     */
    protected $_products = array();

    /**
     * @var array
     */
    protected $_shops = array();

    /**
     * @var array
     */
    protected $_categories = array();

    public function __construct($message = '')
    {
        $this->_message = $message;
    }

    /**
     * @param Product $product
     */
    public function addProduct(Product $product){
        $this->_products[] = $product->getJson();
    }

    /**
     * @param Shop $shop
     */
    public function addShop(Shop $shop){
        $this->_shops[] = $shop->getJson();
    }

    /**
     * @param Category $category
     */
    public function addCategory(Category $category){
        $this->_categories[] = $category->getJson();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $response = array();

        if ($this->_products) {
            $response['items'] = $this->_products;
        }

        if ($this->_shops) {
            $response['shops'] = $this->_shops;
        }

        if ($this->_categories) {
            $response['categories'] = $this->_categories;
        }

        if ($this->_message) {
            $response['message'] = $this->_message;
        }

        return json_encode($response);
    }
} 