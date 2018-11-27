<?php

namespace Zanox;

use Traversable;
use Zanox\Api\AbstractApiMethods;
use Zanox\Api\Adapter\Methods20090701Interface;
use Zanox\Api\Adapter\Methods20110301Interface;
use Zanox\Api\Adapter\Methods20150501Interface;
use Zanox\Api\ClientException;
use Zanox\Api\Constants;
use Zanox\Api\MethodsInterface;
use Zend\Stdlib\ArrayUtils;

/**
 * zanox Api Client (ConnectId Version)
 *
 * Publisher api client library for accessing zanox affiliate
 * network services.
 *
 * Supported Version: PHP >= 5.1.0
 *
 * The zanox API client contains methods and routines to access all publisher
 * Web Services functionalities via a common abstract interface. This includes
 * the hash-signed SOAP and REST request authentication of client messages
 * as well as the encapsulation of all by zanox provided API methods.
 *
 * ---
 *
 * Usage Example: Restful XML API
 * <code>
 *
 *      require_once 'client/ApiClient.php';
 *
 *      $api = ApiClient::factory(PROTOCOL_XML, VERSION_DEFAULT);
 *
 *      $connectId = '__your_connect_id__';
 *      $secretKey = '__your_secrect_key__';
 *      $publicKey = '__your_public_key__';
 *
 *      $api->setConnectId($connectId);
 *      $api->setSecretKey($secretKey);
 *      $api->setPublicKey($publicKey);
 *
 *      $xml = $api->getPrograms();
 *
 * </code>
 *
 * ---
 *
 * Usage Example: Restful JSON API
 * <code>
 *
 *      require_once 'client/ApiClient.php';
 *
 *      $api = ApiClient::factory(PROTOCOL_JSON, VERSION_DEFAULT);
 *
 *      $connectId = '__your_connect_id__';
 *      $secretKey = '__your_secrect_key__';
 *      $publicKey = '__your_public_key__';
 *
 *      $api->setConnectId($connectId);
 *      $api->setSecretKey($secretKey);
 *      $api->setPublicKey($publicKey);
 *
 *      $xml = $api->searchProducts('iphone');
 *
 * </code>
 *
 * ---
 *
 * Usage Example: SOAP API
 * <code>
 *
 *      require_once 'client/ApiClient.php';
 *
 *      $api = ApiClient::factory(PROTOCOL_SOAP, VERSION_DEFAULT);
 *
 *      $connectId = '__your_connect_id__';
 *      $secretKey = '__your_secrect_key__';
 *      $publicKey = '__your_public_key__';
 *
 *      $api->setConnectId($connectId);
 *      $api->setSecretKey($secretKey);
 *      $api->setPublicKey($publicKey);
 *
 *      $xml = $api->getAdspaces();
 *
 * </code>
 *
 * ---
 *
 * Usage Example: Using API via Proxy Server
 * <code>
 *
 *      require_once 'client/ApiClient.php';
 *
 *      $api = ApiClient::factory(PROTOCOL_SOAP, VERSION_DEFAULT);
 *
 *      $api->setProxy("example.org", 8080, "login", "password");
 *
 * </code>
 *
 * ---
 *
 * Usage Example: Using API via HTTPS
 * <code>
 *
 *      require_once 'client/ApiClient.php';
 *
 *      $api = ApiClient::factory(PROTOCOL_SOAP, VERSION_DEFAULT);
 *
 *      $api->setHttpProtocol(HTTPS);
 *
 * </code>
 *
 * ---
 *
 * Usage Example: Using the Build-In Xml/Json Parser
 * <code>
 *
 *      require_once 'client/ApiClient.php';
 *
 *      $api = ApiClient::factory(PROTOCOL_JSON, VERSION_DEFAULT);
 *
 *      $xml = $api->getProduct('31f3bf210db1883e6bc3f7ab5dd096c7');
 *
 *      $array = $api->unserialize($xml);
 *
 * </code>
 *
 * ---
 *
 * @author      Thomas Nicolai (thomas.nicolai@sociomantic.com)
 * @author      Lars Kirchhoff (lars.kirchhoff@sociomantic.com)
 *
 * @see         http://wiki.zanox.com/en/Web_Services
 * @see         http://apps.zanox.com
 *
 * @category    Web Services
 * @package     ApiClient
 * @version     2009-09-01
 * @copyright   Copyright 2007-2009 zanox.de AG
 *
 * @uses        PEAR Crypt_HMAC (required for PHP < 5.1.2)
 */

/**
 * ApiClient
 */
abstract class ApiClient
{
    /**
     * @var array
     */
    protected static $classMap = array(
        Constants::SOAP_INTERFACE => array(
            Constants::VERSION_2009_07_01 => '\Zanox\Api\Adapter\Soap\Methods20090701',
            Constants::VERSION_2011_03_01 => '\Zanox\Api\Adapter\Soap\Methods20110301',
            Constants::VERSION_2015_05_01 => '\Zanox\Api\Adapter\Soap\Methods20150501',
        ),
        Constants::RESTFUL_INTERFACE => array(
            Constants::VERSION_2009_07_01 => '\Zanox\Api\Adapter\Rest\Methods20090701',
            Constants::VERSION_2011_03_01 => '\Zanox\Api\Adapter\Rest\Methods20110301',
            Constants::VERSION_2015_05_01 => '\Zanox\Api\Adapter\Rest\Methods20150501',
        ),
    );

    /**
     * You can choose between three different api protocols. JSON, XML and
     * SOAP are supported by the zanox api. If no version is given JSON is used.
     *
     * ---
     *
     * Usage example: creating api instance
     * <code>
     *      // use soap api interface and the default version
     *      $api = ApiClient::factory();
     * </code>
     *
     * @param array $options
     * @return MethodsInterface|Methods20150501Interface|Methods20110301Interface|Methods20090701Interface|AbstractApiMethods
     * @throws \Zanox\Api\ClientException
     */
    public static function factory(array $options = array())
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        }

        if (!is_array($options)) {
            throw new ClientException(
                sprintf(
                    '%s expects an array or Traversable argument; received "%s"',
                    __METHOD__,
                    (is_object($options) ? get_class($options) : gettype($options))
                )
            );
        }
        
        $interface = Constants::SOAP_INTERFACE;
        if (isset($options['interface'])) {
            $interface = $options['interface'];
        }

        $versionMap = null;
        if (isset(static::$classMap[$interface])) {
            $versionMap = static::$classMap[$interface];
        }

        if (!$versionMap) {
            throw new ClientException(
                sprintf(
                    '%s expects the "interface" attribute to resolve to an existing interface type; received "%s"',
                    __METHOD__,
                    $interface
                )
            );
        }

        $version = Constants::VERSION_DEFAULT;
        if (isset($options['version'])) {
            $version = $options['version'];
        }

        $class = null;
        if (isset($versionMap[$version])) {
            $class = $versionMap[$version];
        }

        $protocol = Constants::PROTOCOL_DEFAULT;
        if (isset($options['protocol'])) {
            $protocol = $options['protocol'];
        }

        if (!class_exists($class)) {
            throw new ClientException(sprintf(
                    '%s expects the "version" attribute to resolve to an existing version; received "%s"',
                    __METHOD__,
                    $version
                ));
        }

        unset($options['interface']);
        unset($options['version']);

        /** @var MethodsInterface|Methods20150501Interface|Methods20110301Interface|Methods20090701Interface|AbstractApiMethods $adapter */
        $adapter = new $class($protocol, $version);

        if (!$adapter instanceof MethodsInterface) {
            throw new ClientException(
                sprintf(
                    '%s expects the "class" attribute to resolve to a valid Zanox\MethodsInterface instance; received "%s"',
                    __METHOD__,
                    $class
                )
            );
        }

        return $adapter;
    }
}


