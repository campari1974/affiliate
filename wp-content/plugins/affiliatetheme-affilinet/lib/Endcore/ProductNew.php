<?php
/**
 * Created by affiliatetheme-affilinet.
 * User: Giacomo
 * Date: 10.06.2015
 * Time: 23:12
 */

namespace Endcore;


class ProductNew
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
        return $this->_product->ProductId;
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
        if(count($this->_product->Logos)) {
            return $this->_product->Logos[0]->URL;
        }

        return null;
    }

    public function getImage()
    {
        if(isset($this->_product->Images->ImageCollection->Image)) {
            return $this->_product->Images->ImageCollection->Image->URL;
        }

        return null;
    }

    public function getImages()
    {
        return $this->_product->Images;
    }

    public function getPrice()
    {
        return $this->_product->PriceInformation->PriceDetails->Price;
    }

    public function getShippingPrice()
    {
        return $this->_product->PriceInformation->ShippingDetails->Price;
    }

    public function getCurrency()
    {
        return $this->_product->PriceInformation->Currency;
    }

    /**
     * @deprecated use getDisplayPrice
     * @return string
     */
    public function getFormattedPrice()
    {
        return $this->getPrice() . ' ' . $this->getCurrency();
    }

    public function getDisplayPrice()
    {
        return $this->_product->PriceInformation->DisplayPrice;
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

    public function getUrl()
    {
        return $this->_product->Deeplink1;
    }

    public function getShopId()
    {
        return $this->_product->ShopId;
    }

    public function getShopName()
    {
        return $this->_product->ShopTitle;
    }

    /**
     * @return string
     */
    public function getJson(){
        return 'toimplement';
    }
}