<?php
/**
 * Project: affiliate_tradedoubler
 * (c) 2014 Giacomo Barbalinardo <info@ready24it.eu>
 * Date: 02.12.2014
 * Time: 19:21
 */

namespace Endcore\Api;

use SimpleXMLElement;

class Tradedoubler {

    /**
     * @var
     */
    protected $_token;

    public function __construct()
    {

    }

    public function getShopList(){
        try{
            $token = TRADEDOUBLER_PRODUCTS_TOKEN;
            if($token == '')
            {
                echo new JsonResponse("No Token Specified");
                die;
            }
            $url="http://api.tradedoubler.com/1.0/productFeeds.json/?token=$token";
            $response = at_tradedoubler_curl_URL($url);
            $response = at_tradedoubler_fix_umlaute($response);
            if(mb_detect_encoding($response) != 'UTF-8') {          $response = utf8_encode($response); }
            if($response == "2: Token not authorized for request."){
                echo new JsonResponse("2: Token not authorized for request.");
                die();
            }
            $response = json_decode($response,true);
            return $response;
        }
        catch (\Exception $e) {
            echo new JsonResponse($e->getMessage());
            die;
        }
    }

    public function getCategoryList(){
        try{
            $token = TRADEDOUBLER_PRODUCTS_TOKEN;
            $url="http://api.tradedoubler.com/1.0/productCategories.json?token=$token";
            $response = at_tradedoubler_curl_URL($url);
            $response = at_tradedoubler_fix_umlaute($response);
            if(mb_detect_encoding($response) != 'UTF-8') {          $response = utf8_encode($response); }
            $response = json_decode($response,true);
            $result = array();
            $result += at_tradedoubler_get_categories_recursive($response['categoryTrees'][0],0);
            return $result;
        }
        catch (\Exception $e) {
            echo new JsonResponse($e->getMessage());
            die;
        }
    }

    public function searchProducts($query = '*', $pageId = 0, $shopIds = null, $min_price = 0, $max_price = 99999, $sort = '', $order = 'Desc', $items = 25, $categoryId='')
    {
        //remove empty spaces from query
        $token=TRADEDOUBLER_PRODUCTS_TOKEN;

        //turn array into number1,number2,...
        //if no shops were selected search all joined shops
        if($shopIds[0]=="null"||count($shopIds) == 0) {
            $shopIds = "";
        }
        else
           $shopIds = implode(",",$shopIds).";";

        $sortString = ($sort == '')?'':$sort.$order;

        $url="http://api.tradedoubler.com/1.0/products.json;fid=$shopIds;q=$query;minPrice=$min_price;maxPrice=$max_price;pageSize=$items;page=$pageId;orderBy=$sortString;tdCategoryId=$categoryId?token=$token";
        $response= at_tradedoubler_curl_URL($url);
        $response = at_tradedoubler_fix_umlaute($response);
        if(mb_detect_encoding($response) != 'UTF-8') {          $response = utf8_encode($response); }
        $response = json_decode($response,true);
        return $response;
    }

    public function searchProductsByEAN($ean, $q = '', $pageId = 1,$per_page= 25)
    {
        $token=TRADEDOUBLER_PRODUCTS_TOKEN;
        //only from joined advertisers
        $url="http://api.tradedoubler.com/1.0/products.json;ean=$ean;page=$pageId;pageSize=$per_page;?token=$token";
        $response= at_tradedoubler_curl_URL($url);
        $response = at_tradedoubler_fix_umlaute($response);
        $response = json_decode($response,true);
        return $response;
    }

    public function lookupProduct($id, $echo = true)
    {
        $token=TRADEDOUBLER_PRODUCTS_TOKEN;
        //only from joined advertisers
        $url="http://api.tradedoubler.com/1.0/products.json;tdId=$id?token=$token";
        $response= at_tradedoubler_curl_URL($url);

        $response = at_tradedoubler_fix_umlaute($response);

        $response = json_decode($response,true);
        return $response;
    }
} 