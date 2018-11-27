<?php
/**
 * Project      affiliatetheme-belboon
 * @author      Giacomo Barbalinardo <info@ready24it.eu>
 * @copyright   2015
 */

namespace Endcore\Belboon;


class Product
{
    /**
     * @var stdClass
     */
    protected $product;
    protected $feeds;
    protected $feedName;

    public function __construct($entity)
    {
        $this->product = $entity;
    }

    /**
     * @return int
     */
    public function getId(){

        return $this->product['belboon_productnumber'];
    }

    public function getArticleNumber()
    {
        return $this->product['productnumber'];
    }

    public function getName()
    {
        return $this->product['productname'];
    }

    public function getDescription()
    {
        return $this->product['productdescriptionlong'];
    }

    public function getShortDescription()
    {
        if ($this->product['productdescriptionshort'] == '') {
            return substr($this->getDescription(), 0, 100) . '...';
        }

        return $this->product['productdescriptionshort'];
    }

    public function getOldprice()
    {
        return $this->product['oldprice'];
    }

    public function getAvailability()
    {
        return $this->product['availability'];
    }

    public function getImage()
    {
        if ($this->product['imagesmallurl'] != '') {
            return $this->product['imagesmallurl'];
        }

        return null;
    }

    public function getBigImage()
    {
        if ($this->product['imagebigurl'] != '') {
            return $this->product['imagebigurl'];
        }

        return $this->getImage();
    }

    public function getImages()
    {
        return $this->product[''];
    }

    public function getPrice()
    {
        return $this->product['currentprice'];
    }

    public function getShippingPrice()
    {
        return $this->product['shipping'];
    }

    public function getCurrency()
    {
        if(!$this->product['currency']) {
            return 'EUR';
        }
        
        return $this->product['currency'];
    }

    /**
     * @return string
     */
    public function getDisplayPrice()
    {
        return $this->getPrice() . ' ' . $this->getCurrency();
    }

    public function getBrand()
    {
        return $this->product['brandname'];
    }

    public function getManufacturer()
    {
        return $this->product['manufacturername'];
    }

    public function getCategory()
    {
        return $this->product['belboonproductcategory'];
    }

    public function getEan()
    {
        if(strlen($this->product['ean']) == 14) {
            return substr($this->product['ean'], 1);
        }
        return $this->product['ean'];
    }

    public function getUrl()
    {
        return $this->product['deeplinkurl'];
    }

    public function getShopId()
    {
        return $this->product['feed_id'];
    }

    public function getShopName()
    {
        if ($this->feedName == null) {
            if ($this->feeds === null) {
                $api = new Belboon();
                $this->feeds = $api->getFeeds();
            }

            foreach ($this->feeds as $feed) {
                if ($feed->getId() == $this->product['feed_id']) {
                    $this->feedName = $feed->getName();
                }
            }
        }

        return $this->feedName;
    }

    /**
     * @return string
     */
    public function getJson(){
        return 'toimplement';
    }
}