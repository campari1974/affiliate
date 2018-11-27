<?php
/**
 * Created by affiliatetheme-zanox.
 * User: Giacomo
 * Date: 18.04.2015
 * Time: 17:18
 */

namespace EcZanox;


class Adspace extends BaseObject implements ObjectInterface{

    /**
     * @var string
     */
    protected $_id;
    protected $_name;

    public function __construct(array $item = array()){
        if (!empty($item)) {
            $this->_originalData = $item;
            $this->_id   = $item['@id'];
            $this->_name = $item['name'];
        }
    }

    public function getId(){
        return $this->_id;
    }

    public function getName(){
        return $this->_name;
    }
}