<?php
/**
 * Created by affiliatetheme-zanox.
 * User: Giacomo
 * Date: 18.04.2015
 * Time: 23:50
 */

namespace EcZanox;


class Program extends BaseObject implements ObjectInterface{
    /**
     * @var string
     */
    protected $_id;
    protected $_name;
    protected $_active;

    public function __construct(array $item = array()){
        if (!empty($item)) {
            $this->_originalData = $item;
            $this->_id   = $item['@id'];
            $this->_name = $item['$'];
            $this->_active = $item['@active'];
        }
    }

    public function getId(){
        return $this->_id;
    }

    public function getName(){
        return $this->_name;
    }

    public function isActive()
    {
        return $this->_active === 'true';
    }
}