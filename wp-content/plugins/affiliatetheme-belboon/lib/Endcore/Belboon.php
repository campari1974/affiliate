<?php

/**
 * Project      affiliatetheme-belboon
 * @author      api@adcell.de
 * @author      Giacomo Barbalinardo <info@ready24it.eu>
 * @copyright   2015
 */
namespace Endcore\Belboon;

class Belboon {

    protected $user = BELBOON_USER;
    protected $password = BELBOON_PASS;

    /**
     * Url zur API
     *
     * @var string
     */
    protected $apiUrl = 'http://smartfeeds.belboon.com/SmartFeedServices.php?wsdl';

    /**
     * Version der API
     *
     * @var string
     */
    protected $apiVersion = 'v1';

    /**
     * @var \SoapClient
     */
    protected $client;

    public function __construct()
    {
        $this->client = new \SoapClient($this->getApiBaseUrl());
    }


    /**
     * Liefert die BasisUrl aus
     *
     * @return string
     */
    protected function getApiBaseUrl() {
        return $this->apiUrl;
    }

    /**
     * Login.
     *
     * @return \stdClass
     * @throws \Exception
     */
    protected function login()
    {
        $login = $this->client->login(
            $this->user, $this->password
        );

        if ($login->HasError) {
            throw new \Exception('Error: ' . $login->ErrorMsg);
        }

        return $login;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    protected function getSessionHash()
    {
        $login = $this->login();
        return $login->Records['sessionHash'];
    }

    /**
     * @param string $query
     * @param int $page
     * @param array $options
     * @return mixed
     */
    public function searchProducts($query = '', $page = 1, $options = array())
    {
        $page = $page - 1;
        $offset = $page * 10;

        $options = array_merge(
            $options,
            array(
                'offset' => $offset,
                'limit' => 10,
            )
        );

        $response = $this->client->searchProducts(
            $this->getSessionHash(),
            ''.$query,
            $options
        );
        try {
            return new ProductList($response);
        }
        catch (\Exception $e){
            throw $e;
        }
    }

    public function getProductById($id)
    {
        $response = $this->client->getProductById(
            $this->getSessionHash(),
            $id
        );

        return new ProductList($response);
    }

    /**
     * @return mixed
     */
    public function getPlatforms()
    {
        $response = $this->client->getPlatforms(
            $this->getSessionHash()
        );

        return new PlatformList($response);
    }

    /**
     * @return mixed
     */
    public function getFeeds($platform = '')
    {
        $options = array(
            'limit' => 99
        );

        if($platform) {
            $options['platforms'] = array($platform);
        }

        $response = $this->client->getFeeds(
            $this->getSessionHash(),
            $options
        );

        return new FeedList($response);
    }
}

?>