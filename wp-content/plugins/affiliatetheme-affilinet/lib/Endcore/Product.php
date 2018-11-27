<?php
/**
 * Project: affiliate_affilinet
 * (c) 2014 Giacomo Barbalinardo <info@ready24it.eu>
 * Date: 04.12.2014
 * Time: 16:31
 */

namespace Endcore;


class Product
{
    /**
     * @var stdClass
     */
    protected $_product;

    public function __construct($product)
    {
        $this->_product = $product;
    }

    public function getId()
    {
        return $this->_product->Id;
    }

    public function getArticleNumber()
    {
        return $this->_product->ArticleNumber;
    }

    public function getName()
    {
        return $this->_product->ProductName;
    }

    public function getDescription()
    {
        return $this->_product->Description;
    }

    public function getShortDescription()
    {
        return $this->_product->DescriptionShort;
    }

    public function getRank()
    {
        return $this->_product->Score;
    }

    public function getLogo()
    {
        return $this->_product->Logos->Logo->URL;
    }

    public function getImage()
    {
        return $this->_product->Images->ImageCollection->Image->URL;
    }

    public function getPrice()
    {
        return $this->_product->PriceInformation->PriceDetails->Price;
    }

    public function getCurrency()
    {
        return strtolower($this->_product->PriceInformation->Currency);
    }

    public function getFormattedPrice()
    {
        return $this->getPrice() . ' ' . $this->getCurrency();
    }

    public function getBrand()
    {
        return $this->_product->Brand;
    }

    public function getManufacturer()
    {
        return $this->_product->Manufacturer;
    }

    public function getCategory()
    {
        return $this->_product->ShopCategoryPath;
    }

    public function getEan()
    {
        if(strlen($this->_product->EAN) == 14) {
            return substr($this->_product->EAN, 1);
        }
        return $this->_product->EAN;
    }

    public function getUrl($useHttps = true)
    {
        if ($useHttps) {
            return $this->getHttpsUrl();
        }

        return $this->_product->Deeplink1;
    }

    function getHttpsUrl()
    {
        $from = '/'.preg_quote('http://', '/').'/';
        return preg_replace($from, 'https://', $this->_product->Deeplink1, 1);
    }

    public function getShopId()
    {
        return $this->_product->ShopId;
    }

    public function getShopName()
    {
        return $this->_product->ShopTitle;
    }

    public function getProperties()
    {
        return $this->_product->Properties->Property;
    }

    /**
     * @return string
     */
    public function getShopLogo()
    {
        if (isset($this->_product->Logos->Logo)) {
            return $this->_product->Logos->Logo->URL;
        }
        return '';
    }

    /**
     * @return string
     */
    public function getJson(){
        return 'toimplement';
    }
}