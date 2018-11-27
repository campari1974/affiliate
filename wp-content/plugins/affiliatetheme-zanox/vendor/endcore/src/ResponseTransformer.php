<?php
/**
 * Created by affiliatetheme-zanox.
 * User: Giacomo
 * Date: 18.04.2015
 * Time: 16:07
 */

namespace EcZanox;


class ResponseTransformer {

    /**
     * @var string
     */
    protected $_response;

    /**
     * @var string
     */
    protected $_searchContainer;

    /**
     * @var bool
     */
    protected $_special = false;

    /**
     * @var array
     */
    protected $_errors = array();

    /**
     * @var string
     */
    protected $_searchType;

    /**
     * @var int
     */
    protected $_actualPage;
    protected $_itemsCount;
    protected $_totalItems;

    public function __construct($response, $searchContainer, $searchType = null, $transformable = true){

        if (ZANOX_FIX_ENCODING) {
            $response = Encoding::replaceWrongEncoding($response);
        }
        $this->_response = json_decode($response, true);

        if (empty($this->_response)) {
            $this->_actualPage = 0;
            $this->_itemsCount = 0;
            $this->_totalItems = 0;
            $this->_addErrorMessage('Empty response.');
            return;
        }

        $this->_searchContainer = $searchContainer;
        $this->_searchType = $searchType;

        if ($transformable) {
            $this->_transform();
        } else {
            $this->_actualPage = 0;
            $this->_itemsCount = 0;
            $this->_totalItems = 0;
        }
    }

    protected function _transform(){
        if (array_key_exists('items', $this->_response) && $this->_response['items'] == 0) {
            $this->_addErrorMessage('No Items found.');
            return;
        }

        if (!array_key_exists($this->_searchContainer, $this->_response)) {
            $this->_addErrorMessage('SearchContainer not found.');
            return;
        }

        if ($this->_searchType !== null
            && !array_key_exists($this->_searchType, $this->_response[$this->_searchContainer])) {

            if (is_array($this->_response[$this->_searchContainer])
                && count($this->_response[$this->_searchContainer]) > 0
                && array_key_exists($this->_searchType, $this->_response[$this->_searchContainer][0])
            ) {
                $this->_setExtras();
                $this->_special = true;
                return;
            }

            $this->_addErrorMessage('SearchType not found.');
            return;
        }

        $this->_setExtras();
    }

    /**
     * @param string $msg
     */
    protected function _addErrorMessage($msg)
    {
        $this->_errors[] = $msg;
    }

    public function getSingleItem()
    {
        return $this->_response[$this->_searchContainer];
    }

    /**
     * @return array
     */
    public function getCollection()
    {
        if ($this->_hasErrors()) {
            return array();
        }

        if ($this->_special) {
            return $this->_response[$this->_searchContainer][0][$this->_searchType];
        }

        return $this->_response[$this->_searchContainer][$this->_searchType];
    }

    /**
     * @return bool
     */
    protected function _hasErrors()
    {
        return count($this->_errors) > 0;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    protected function _setExtras()
    {
        $this->_actualPage = $this->_response['page'];
        $this->_itemsCount = $this->_response['items'];
        $this->_totalItems = $this->_response['total'];
    }

    /**
     * @return array
     */
    public function getExtras()
    {
        return array(
            'page' => $this->_actualPage,
            'items' => $this->_itemsCount,
            'total' => $this->_totalItems,
			'pages' => ($this->_totalItems ? intval($this->_totalItems/$this->_itemsCount) : '0'),
            'error' => $this->_errors
        );
    }
}