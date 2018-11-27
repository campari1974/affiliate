<?php
/**
 * Project      affiliatetheme-adcell
 * @author      Giacomo Barbalinardo <info@ready24it.eu>
 * @copyright   2016
 */

namespace EcAdcell;


class Item
{
    protected $originalData;

    /**
     * Program constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->originalData = $data;
    }

    public function getPromotionId()
    {
        return $this->originalData['promoId'];
    }
    
    public function getId()
    {
        return $this->originalData['promoId'] . ':' . $this->originalData['id'];
    }
    public function getName()
    {
        return $this->originalData['Produkt-Titel'];
    }

    public function getShortDescription()
    {
        return $this->originalData['Produktbeschreibung'];
    }

    public function getDescription()
    {
        return $this->originalData['Produktbeschreibung lang'];
    }

    public function getPrice()
    {
        //return $this->originalData['Preis (Brutto)'];
        return number_format(floatval(str_replace(',', '.', str_replace('.', '', $this->originalData['Preis (Brutto)']))), 2, '.', ',');
    }

    public function getDisplayPrice()
    {
        return $this->getPrice() . ' ' . $this->getCurrency();
    }

    public function getArticleNumber()
    {
        return $this->originalData['Anbieter Artikelnummer AAN'];
    }

    public function getEan()
    {
        return $this->originalData['europäische Artikelnummer EAN'];
    }

    public function getCurrency()
    {
        return $this->originalData['Währung'];
    }

    public function getUrl()
    {
        return $this->originalData['Deeplink'];
    }

    public function getPreviewImage()
    {
        return $this->originalData['Vorschaubild-URL'];
    }

    public function hasPreviewImage()
    {
        return $this->getPreviewImage() != '';
    }

    public function getImage()
    {
        return $this->originalData['Produktbild-URL'];
    }

    public function hasImage()
    {
        return $this->getImage() != '';
    }

    public function getCategory()
    {
        return $this->originalData['Produktkategorie'];
    }

    public function getShipping()
    {
        return $this->originalData['Lieferzeit/Verfügbarkeit'];
    }
}