<?php
/**
 * Created by affiliatetheme-zanox.
 * User: Giacomo
 * Date: 18.04.2015
 * Time: 16:26
 */

namespace EcZanox;

use Zanox\Api;
use Zanox\Api\Constants;
use Zanox\ApiClient;

class Zanox {

    public $api;

    public function __construct(){
        $this->api = ApiClient::factory(
            array(
                'interface' => Constants::RESTFUL_INTERFACE,
                'protocol' => Constants::PROTOCOL_JSON,
                'version'  => Constants::VERSION_2015_05_01
            )
        );

        $this->api->setConnectId(ZANOX_API_ID);
        $this->api->setSecretKey(ZANOX_API_SECRET_KEY);
    }

    /**
     * @param int $page
     * @param int $items
     * @return AdspaceCollection
     */
    public function getAdspaces($page = 0, $items = 999)
    {
        $json = $this->api->getAdspaces($page, $items);
        $response = new ResponseTransformer($json, 'adspaceItems', 'adspaceItem');
        $adSpaces = $response->getCollection();
        $collection =  new AdspaceCollection();
        $collection->addCollection($adSpaces);
        $collection->setExtras($response->getExtras());

        return $collection;
    }

    public function getProgrammApplications($adspaceId = null, $programId = null,
                                            $status = 'confirmed', $page = 0, $items = 999)
    {
        $json = $this->api->getProgramApplications($programId, $adspaceId, $status, $page, $items);
        $response = new ResponseTransformer($json, 'programApplicationItems', 'programApplicationItem');
        $programApplications = $response->getCollection();
        $collection = new ProgramApplicationCollection();
        $collection->addCollection($programApplications);
        $collection->setExtras($response->getExtras());

        return $collection;
    }

    public function getMerchantCategories($programId)
    {
        $json = $this->api->getMerchantCategories($programId);
        $response = new ResponseTransformer($json, 'categories', 'category');
        $categories = $response->getCollection();
        $collection =  new CategoryCollection();
        $collection->addCollection($categories);
        $collection->setExtras($response->getExtras());

        return $collection;
    }

    public function searchProducts($query, $searchType = 'phrase', $ean = null, $region = null,
                                   $merchantCategoryId = null, $programId = array(), $hasImages = true,
                                   $minPrice = 0, $maxPrice = null, $adspaceId = null, $page = 0, $items = 10)
    {
        $json = $this->api->searchProducts($query, $searchType, $ean, $region,
            $merchantCategoryId, $programId, $hasImages,
            $minPrice, $maxPrice, $adspaceId, $page, $items);
        $response = new ResponseTransformer($json, 'productItems', 'productItem');
        $products = $response->getCollection();
        $collection = new ProductCollection();
        $collection->addCollection($products);
        $collection->setExtras($response->getExtras());

        return $collection;
    }

    public function getProduct($productId, $adspaceId = NULL)
    {
        $json = $this->api->getProduct($productId, $adspaceId);
        $response = new ResponseTransformer($json, 'productItem', null, false);
        $products = $response->getSingleItem();
        if(!$products) { return; }
        $collection = new ProductCollection();
        $collection->addCollection($products);
        $collection->setExtras($response->getExtras());

        return $collection;
    }
}