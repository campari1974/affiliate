<?php
/**
 * Created by affiliatetheme-zanox.
 * User: Giacomo
 * Date: 18.04.2015
 * Time: 23:50
 */

namespace EcZanox;


class Product extends BaseObject implements ObjectInterface{
    /**
     * @var string
     */
    protected $_id;
    protected $_name;

    /**
     * @var float
     */
    protected $_price;

    /**
     * @var string
     */
    protected $_currency;
    protected $_description;
    protected $_longDescription;
    protected $_ean;

    protected $_ppv = '';
    protected $_ppc = '';

    /**
     * @var array
     */
    protected $_images = array();


    public function __construct(array $item = array()){
        if (!empty($item)) {
            $this->_originalData = $item;
            $this->_id    = $item['@id'];
            $this->_name  = $item['name'];
            $this->_price = $item['price'];
			
            $this->_currency        = $item['currency'];
            $this->_description     = $item['description'];
            $this->_longDescription = isset($item['descriptionLong']) ? $item['descriptionLong'] : null;
            $this->_ean             = isset($item['ean']) ? $item['ean'] : '';
            $this->_program         = isset($item['program']) ? $item['program']['$'] : '';

            if(array_key_exists('trackingLink', $item['trackingLinks']) && count($item['trackingLinks']['trackingLink']) > 0) {
                $this->_ppv = $item['trackingLinks']['trackingLink'][0]['ppv'];
                $this->_ppc = $item['trackingLinks']['trackingLink'][0]['ppc'];
            }

            $this->_images = $item['image'];
        }
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getShortDescription()
    {
        if ($this->_longDescription == '') {
            return substr($this->_description, 0, 100) . '...';
        }
        return substr($this->_longDescription, 0, 100) . '...';
    }

    public function getDescription()
    {
        if ($this->_longDescription == '') {
            return $this->_description;
        }
        return $this->_longDescription;
    }

    public function getPrice(){
        return $this->_price;
    }

    public function getFormattedPrice($delimiter = ',')
    {
        return str_replace('.', $delimiter, (string) $this->_price) . ' ' . $this->getCurrencySymbol();
    }
	
	public function getCurrency()
    {
        
        return strtolower($this->_currency);
    }

    public function getCurrencySymbol()
    {
        $symbol = '';
						
        switch ($this->getCurrency()) {
			case 'chf':
				$symbol = 'CHF';
				break;
				
            default:
                $symbol = '€';
                break;
        }

        return $symbol;
    }

    public function getEan()
    {
        return $this->_ean;
    }

    public function getUrl(){
        return $this->_ppc;
    }

    public function getTrackingUrl(){
        return $this->_ppv;
    }

    public function getImages()
    {
        return $this->_images;
    }

    public function getProgram()
    {
        return $this->_program;
    }
}