<?php
/**
 * Created by affiliatetheme-zanox.
 * User: Giacomo
 * Date: 18.04.2015
 * Time: 23:50
 */

namespace EcZanox;


class Category extends BaseObject implements ObjectInterface{
    /**
     * @var string
     */
    protected $_id;
    protected $_name;

    public function __construct($item){
        if (!empty($item)) {
            $this->_originalData = $item;
        }
    }

    public function getId(){
        return $this->_id;
    }
}