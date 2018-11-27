<?php
/**
 * Created by affiliatetheme-zanox.
 * User: Giacomo
 * Date: 18.04.2015
 * Time: 17:18
 */

namespace EcZanox;


class ProgramApplication extends BaseObject implements ObjectInterface{

    /**
     * @var string
     */
    protected $_id;
    protected $_program;

    public function __construct(array $item = array()){
        if (!empty($item)) {
            $this->_originalData = $item;
            $this->_id   = $item['@id'];
            $this->_program = new Program($item['program']);
        }
    }

    public function getId(){
        return $this->_id;
    }

    public function getProgram(){
        return $this->_program;
    }
}