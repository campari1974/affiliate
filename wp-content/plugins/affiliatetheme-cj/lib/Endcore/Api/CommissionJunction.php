<?php
/**
 * Project: affiliate_cj
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
use SimpleXMLElement;

class CommissionJunction {

    /**
     * @var
     */
    protected $_token;

    public function __construct()
    {

    }

	public function getShopList(){
		try{
			$devkey = get_option('cj_devkey');
			if($devkey == '')
			{
				echo new JsonResponse("No Devkey Specified");
				die;
			}
			$websiteid = get_option('cj_website_id');
			if($websiteid == '')
			{
				echo new JsonResponse("No Website ID Specified");
				die;
			}
			$fetchedresults = 0;
			$page = 1;
			$url="https://advertiser-lookup.api.cj.com/v3/advertiser-lookup?advertiser-ids=joined";
			$response = at_cj_curl_URL($url);
			$resultXML=new SimpleXMLElement($response);
			$json = json_encode($resultXML);
			$xml_fixed = json_decode($json);
			if($xml_fixed->{'error-message'}){
				echo new JsonResponse($xml_fixed->{'error-message'});
				die;
			}
			$totalresults = $xml_fixed->advertisers->{"@attributes"}->{'total-matched'};
			$fetchedresults += 25;
			$advertisers = $xml_fixed->advertisers->advertiser;
			while($fetchedresults <= $totalresults){
				$page++;
				$url = "https://advertiser-lookup.api.cj.com/v3/advertiser-lookup?advertiser-ids=joined&page-number=$page";
				$response = at_cj_curl_URL($url);
				$resultXML=new SimpleXMLElement($response);
				$json = json_encode($resultXML);
				$xml_fixed = json_decode($json);

				$recordsReturned = $xml_fixed->advertisers->{"@attributes"}->{'records-returned'};

				if( $recordsReturned == 0 ) {
					break;
				}

				$fetchedresults += $recordsReturned;
				$advertisers2 = $xml_fixed->advertisers->advertiser;
				$advertisers = array_merge($advertisers,$advertisers2);
			}
			return $advertisers;
		}
		catch (\Exception $e) {
			echo new JsonResponse($e->getMessage());
			die;
		}
	}

    public function searchProducts($query = '*', $pageId = 0, $shopIds = null, $min_price = 0, $max_price = 99999, $sort = '', $order = 'desc', $items = 25)
    {
        //remove empty spaces from query
        $query = str_replace(" ","%20",$query);
        urlencode($query);
        //get your website key
        $websiteId = get_option('cj_website_id');
        //turn array into number1,number2,...
        //if no shops were selected search all joined shops
        if($shopIds[0]=="null"||count($shopIds) == 0) {
            $shopIds = "joined";
        }
        else
           $shopIds = implode(",",$shopIds);
        $url="https://product-search.api.cj.com/v2/product-search?website-id=$websiteId&advertiser-ids=$shopIds&keywords=$query&low-price=$min_price&high-price=$max_price&records-per-page=$items&sort-by=$sort&sort-order=$order&page-number=$pageId";
        $response= at_cj_curl_URL($url);
        $resultXML=new SimpleXMLElement($response);
        $json = json_encode($resultXML);
        $xml_fixed = json_decode($json);
        return $xml_fixed;
    }

    public function searchProductsBySKU($sku, $q = '', $pageId = 1)
    {
        $websiteId = get_option('cj_website_id');
        //only from joined advertisers
        $url="https://product-search.api.cj.com/v2/product-search?website-id=$websiteId&advertiser-sku=$sku&advertiser-ids=joined";
        $response= at_cj_curl_URL($url);
        $resultXML=new SimpleXMLElement($response);
        $json = json_encode($resultXML);
        $xml_fixed = json_decode($json);
        return $xml_fixed;
    }

    public function lookupProduct($adid, $sku, $echo = true)
    {
        $websiteId = get_option('cj_website_id');
        $url="https://product-search.api.cj.com/v2/product-search?website-id=$websiteId&advertiser-ids=$adid&advertiser-sku=$sku";
        $response= at_cj_curl_URL($url);
        $resultXML=new SimpleXMLElement($response);
        $json = json_encode($resultXML);

        $xml_fixed = json_decode($json);

        if(is_array($xml_fixed->products->product)) {
            $xml_fixed->products->product = end($xml_fixed->products->product);
        }

        return $xml_fixed;
    }
} 