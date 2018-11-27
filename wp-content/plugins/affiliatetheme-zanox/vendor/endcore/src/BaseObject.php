<?php
/**
 * Created by affiliatetheme-zanox.
 * User: Giacomo
 * Date: 18.04.2015
 * Time: 17:50
 */

namespace EcZanox;


class BaseObject {
    /**
     * @var array
     */
    protected $_originalData = array();

    /**
     * @return array
     */
    public function getOriginalData()
    {
        return $this->_originalData;
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->_originalData);
    }
}