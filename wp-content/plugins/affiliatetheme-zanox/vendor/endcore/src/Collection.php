<?php
/**
 * Created by affiliatetheme-zanox.
 * User: Giacomo
 * Date: 18.04.2015
 * Time: 16:16
 */

namespace EcZanox;


abstract class Collection {
    protected $_position = 0;
    protected $_items    = array();
    protected $_extras   = array();

    public function __construct() {
        $this->_position = 0;
    }

    public function setExtras(array $extras = array())
    {
        $this->_extras = $extras;
    }

    public function getExtras()
    {
        return $this->_extras;
    }

    public function addItem(ObjectInterface $item)
    {
        $this->_items[] = $item;
    }

    public function getItem($index)
    {
        if (!isset($this->_items[$index])) {
            throw new \Exception('Item not found');
        }

        return $this->_items[$index];
    }

    abstract function addCollection(array $items = array());

    public function getCollection()
    {
        return $this->_items;
    }

    public function rewind() {
        $this->_position = 0;
    }

    public function current() {
        return $this->_items[$this->_position];
    }

    public function key() {
        return $this->_position;
    }

    public function next() {
        ++$this->_position;
    }

    public function valid() {
        return isset($this->_items[$this->_position]);
    }

    public function count(){
        return count($this->_items);
    }
}