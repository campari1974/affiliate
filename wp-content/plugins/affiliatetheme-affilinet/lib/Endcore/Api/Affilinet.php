<?php
/**
 * Project: affiliate_affilinet
 * (c) 2014 Giacomo Barbalinardo <info@ready24it.eu>
 * Date: 02.12.2014
 * Time: 19:21
 */

namespace Endcore\Api;

use Endcore\Result\CategoryList;
use Endcore\Result\ShopList;
use Endcore\Result\ProductList;
use Endcore\Result\ProductNewList;
use Endcore\Result\SingleProductList;

class Affilinet {

    /**
     * @var
     */
    protected $_token;

    public function __construct()
    {
        try {
            $logon = new \SoapClient(WSDL_LOGON_V3);
            $this->_token = $logon->Logon(
                array(
                    'Username' => ANET_USER,
                    'Password' => ANET_PASS,
                    'WebServiceType' => 'Product'
                )
            );
        } catch (\Exception $e) {
            echo new JsonResponse($e->getMessage());
            die;
        }
    }

    public function getShopList(){
        try {
            $list = new \SoapClient(WSDL_PROD_V3);
            $response = $list->GetShopList(array(
                'CredentialToken' => $this->_token,
                'PublisherId' => ANET_USER,
                'PageSettings' => array('PageSize' => 999)
            ));

            return new ShopList($response);
        } catch (\Exception $e) {
            echo new JsonResponse($e->getMessage());
            die;
        }
    }

    public function getCategoryList($shopId)
    {
        try {
            $list = new \SoapClient(WSDL_PROD_V3);
            $response = $list->GetCategoryList(array(
                'CredentialToken' => $this->_token,
                'ShopId' => $shopId,
                'PublisherId' => ANET_USER,
            ));

            return new CategoryList($response);
        } catch (\Exception $e) {
            echo new JsonResponse($e->getMessage());
            die;
        }
    }

    public function searchProductsInCategory($shopId, $categoryId, $query, $pageId)
    {
        try {
            $params = array(
                'ShopId' => $shopId,
                'CategoryId' => $categoryId,
                'IncludeChildNodes' => true,
                'UseAffilinetCategories' => false,
                'Query' => $query,
                'WithImageOnly' => false,
                'Details' => true,
                'ImageSize' => 'Image180Logo468',
                'CurrentPage' => $pageId,
                'PageSize' => '25',
                'MinimumPrice' => '0',
                'MaximumPrice' => '99999',
                'SortBy' => 'Rank',
                'SortOrder' => 'Descending'
            );

            $list = new \SoapClient(WSDL_PROD_V3);
            $response = $list->SearchProductsInCategory(array(
                'CredentialToken' => $this->_token,
                'SearchProductsInCategoryRequestMessage' => $params
            ));

            return new ProductList($response);
        } catch (\Exception $e) {
            echo new JsonResponse($e->getMessage());
            die;
        }
    }

    public function searchProducts($query = '*', $pageId = 1, $categoryId = null, $shopIds = null, $min_price = 0, $max_price = 99999, $sort = 'Score', $order = 'descending', $items = 25)
    {
        try {
            $logon = new \SoapClient(WSDL_LOGON_V3);
            $this->_token = $logon->Logon(
                array(
                    'Username' => ANET_USER,
                    'Password' => ANET_PASS,
                    'WebServiceType' => 'Product'
                )
            );
        } catch (\Exception $e) {
            echo new JsonResponse($e->getMessage());
            die;
        }


        try {
            $params = array(
                'ShopIds' => $shopIds,
                'CategoryIds' => $categoryId,
                'IncludeChildNodes' => true,
                'UseAffilinetCategories' => false,
                'Query' => $query,
                'WithImageOnly' => false,
                'Details' => true,
                'ImageScales' => array('OriginalImage'),
                'LogoScales' => array('Logo468'),
                'PageSettings' => array(
                    'CurrentPage' => $pageId,
                    'PageSize'    => $items
                ),
                'MinimumPrice' => $min_price,
                'MaximumPrice' => $max_price,
                'SortBy' => $sort,
                'SortOrder' => $order
            );

            $list = new \SoapClient(WSDL_PROD_V3);
            $response = $list->SearchProducts(
                array_merge(array(
                    'PublisherId' => ANET_USER,
                    'CredentialToken' => $this->_token,
                ), $params)
            );

            return new ProductNewList($response);
        } catch (\Exception $e) {
            echo new JsonResponse($e->getMessage());
            die;
        }
    }

    public function searchProductsByEan($ean, $q = '', $pageId = 1, $shopIds = null)
    {
        // possible fix for affilinet
        if(strlen($ean) == 13) {
            $ean = '0' . $ean;
        }

        try {
            $logon = new \SoapClient(WSDL_LOGON_V3);
            $this->_token = $logon->Logon(
                array(
                    'Username' => ANET_USER,
                    'Password' => ANET_PASS,
                    'WebServiceType' => 'Product'
                )
            );
        } catch (\Exception $e) {
            echo new JsonResponse($e->getMessage());
            die;
        }


        try {
            $params = array(
                'ShopIds' => $shopIds,
                'IncludeChildNodes' => true,
                'UseAffilinetCategories' => false,
                'WithImageOnly' => false,
                'Details' => true,
                'ImageScales' => array('OriginalImage'),
                'LogoScales' => array('Logo468'),
                'PageSettings' => array(
                    'CurrentPage' => $pageId,
                    'PageSize'    => 60
                ),
                'MinimumPrice' => '0',
                'MaximumPrice' => '99999',
                'SortBy' => 'Score',
                'SortOrder' => 'descending',
            );

            if($q) {
                $query = array(
                    'Query' => $q
                );

                $params = array_merge($params, $query);
            } else if($ean) {
                $filter = array(
                    'FilterQueries' => array(
                        array(
                            'DataField' => 'EAN',
                            'FilterValue' => $ean
                        )
                    )
                );

                $params = array_merge($params, $filter);
            }

            $list = new \SoapClient(WSDL_PROD_V3);
            $response = $list->SearchProducts(
                array_merge(array(
                    'PublisherId' => ANET_USER,
                    'CredentialToken' => $this->_token,
                ), $params)
            );

            return new ProductNewList($response);
        } catch (\Exception $e) {
            echo new JsonResponse($e->getMessage());
            die;
        }
    }

    /**
     * Same as searchProductsByEan but throws the Exception so the CronJob doesn't die
     *
     * @param $ean
     * @param string $q
     * @param int $pageId
     * @return ProductNewList
     * @throws \Exception
     */
    public function searchProductsByEanForUpdate($ean, $q = '', $pageId = 1)
    {
        // possible fix for affilinet
        if(strlen($ean) == 13) {
            $ean = '0' . $ean;
        }

        try {
            $logon = new \SoapClient(WSDL_LOGON_V3);
            $this->_token = $logon->Logon(
                array(
                    'Username' => ANET_USER,
                    'Password' => ANET_PASS,
                    'WebServiceType' => 'Product'
                )
            );
        } catch (\Exception $e) {
            throw $e;
        }


        try {
            $params = array(
                'IncludeChildNodes' => true,
                'UseAffilinetCategories' => false,
                'WithImageOnly' => false,
                'Details' => true,
                'ImageScales' => array('OriginalImage'),
                'LogoScales' => array('Logo468'),
                'PageSettings' => array(
                    'CurrentPage' => $pageId,
                    'PageSize'    => 60
                ),
                'MinimumPrice' => '0',
                'MaximumPrice' => '99999',
                'SortBy' => 'Score',
                'SortOrder' => 'descending',
            );

            if($q) {
                $query = array(
                    'Query' => $q
                );

                $params = array_merge($params, $query);
            } else if($ean) {
                $filter = array(
                    'FilterQueries' => array(
                        array(
                            'DataField' => 'EAN',
                            'FilterValue' => $ean
                        )
                    )
                );

                $params = array_merge($params, $filter);
            }

            $list = new \SoapClient(WSDL_PROD_V3);
            $response = $list->SearchProducts(
                array_merge(array(
                    'PublisherId' => ANET_USER,
                    'CredentialToken' => $this->_token,
                ), $params)
            );

            return new ProductNewList($response);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function lookupProduct($productId, $echo = true)
    {
        try {
            $logon = new \SoapClient(WSDL_LOGON_V3);
            $this->_token = $logon->Logon(
                array(
                    'Username' => ANET_USER,
                    'Password' => ANET_PASS,
                    'WebServiceType' => 'Product'
                )
            );
        } catch (\Exception $e) {
            echo new JsonResponse($e->getMessage());
            die;
        }

        try {
            $params = array(
                'ProductIds'   => array($productId),
                'Details'      => true,
                'ImageScales'  => array('OriginalImage'),
                'LogoScales'   => array('Logo468'),
                'SortBy'       => 'Rank',
                'SortOrder'    => 'Descending',
                'PageSettings' => array(
                    'CurrentPage' => '1',
                    'PageSize'    => '10',
                )
            );

            $list = new \SoapClient(WSDL_PROD_V3);
            $response = $list->GetProducts(
                array_merge(array(
                    'PublisherId'     => ANET_USER,
                    'CredentialToken' => $this->_token,
                ), $params)
            );

            return new SingleProductList($response);
        } catch (\Exception $e) {
            if($echo) {
                echo new JsonResponse($e->getMessage());
                die;
            } else {
                return 'error';
            }
        }
    }
} 