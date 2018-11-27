<?php

namespace Zanox\Api;

use SoapClient;
use SoapFault;

/**
 * Abstract ApiMethods Class
 *
 * Supported Version: PHP >= 5.0
 *
 * @author      Thomas Nicolai (thomas.nicolai@sociomantic.com)
 * @author      Lars Kirchhoff (lars.kirchhoff@sociomantic.com)
 *
 * @see         http://wiki.zanox.com/en/Web_Services
 * @see         http://apps.zanox.com
 *
 * @package     ApiClient
 * @version     2009-09-01
 * @copyright   Copyright (c) 2007-2011 zanox.de AG
 *
 * @uses        PEAR HTTP_Request
 * @uses        PEAR XML_Serializer
 */
abstract class AbstractApiMethods implements MethodsInterface
{

    /**
     * xml & json parser
     *
     * @var     object $parser parser instance
     */
    private $parser;


    /**
     * api version
     *
     * @var     string $version api version
     */
    private $version;


    /**
     * api protocol type
     *
     * @var     string $protocol api protocol type
     *                                          (XML, JSON or SOAP)
     */
    private $protocol;


    /**
     * ApiAuthorization instance
     *
     * @var     object $auth authorization class instance
     */
    private $auth;


    /**
     * Restful http verb
     *
     * @var     string $connectId http verb (GET/PUT/DELETE etc.)
     */
    private $httpVerb;


    /**
     * Restful http protocol type
     *
     * @var     string $httpProtocol http protocol type (HTTP/HTTPS)
     */
    private $httpProtocol;


    /**
     * Restful http compression
     *
     * @var     boolean $httpCompression http compression turned on/off
     */
    private $httpCompression;


    /**
     * Proxy host
     *
     * @var     string $proxyHost proxy host
     */
    private $proxyHost;


    /**
     * Proxy port
     *
     * @var     string $proxyPort proxy port
     */
    private $proxyPort;


    /**
     * Proxy login
     *
     * @var     string $proxyLogin proxy login
     */
    private $proxyLogin;


    /**
     * Proxy password
     *
     * @var     string $proxyLogin proxy password
     */
    private $proxyPassword;


    /**
     * Constructor: Sets the api version and protocol
     *
     * @param      string $protocol api protocol type
     * @param      string $version api version
     *
     *
     * @return \Zanox\Api\AbstractApiMethods
     */
    final public function __construct($protocol, $version)
    {
        $this->setProtocol($protocol);
        $this->setVersion($version);

        $this->auth = new Authorization();
        $this->parser = new Parser();
    }


    /**
     * Sets the api version and protocol
     *
     *
     * @return     string                      api version
     */
    final public function getVersion()
    {
        return $this->version;
    }


    /**
     * Sets the api version
     *
     * @param      string $version api version
     */
    final public function setVersion($version)
    {
        $this->version = $version;
    }


    /**
     * Returns api protocol type
     *
     *
     * @return     string                      api protocol type
     */
    final public function getProtocol()
    {
        return $this->version;
    }


    /**
     * Set api protocol type
     *
     * @param      string $protocol api protocol type
     */
    final public function setProtocol($protocol)
    {
        $this->protocol = $protocol;
    }


    /**
     * Set http protocol type
     *
     * @param      string $httpProtocol http protocol type (HTTP or HTTPS)
     */
    final public function setHttpProtocol($httpProtocol)
    {
        $this->httpProtocol = $httpProtocol;
    }


    /**
     * Set http protocol type
     */
    final public function enableCompression()
    {
        $this->httpCompression = true;
    }


    /**
     * Set http protocol type
     *
     * @param      string $host proxy host name
     * @param      int $port proxy port
     * @param      string $login proxy login
     * @param      string $password proxy password
     */
    final public function setProxy($host, $port, $login, $password)
    {
        $this->proxyHost = $host;
        $this->proxyPort = $port;
        $this->proxyLogin = $login;
        $this->proxyPassword = $password;
    }


    /**
     * Set connectId
     *
     * @param      string $connectId zanox connectId
     */
    final public function setConnectId($connectId)
    {
        $this->auth->setConnectId($connectId);
    }


    /**
     * Set SecretKey
     *
     * @param      string $secretKey zanox secret key
     */
    final public function setSecretKey($secretKey)
    {
        $this->auth->setSecretKey($secretKey);
    }


    /**
     * Set SecretKey
     *
     * @param      string $publicKey zanox secret key
     */
    final public function setPublicKey($publicKey)
    {
        $this->auth->setPublicKey($publicKey);
    }


    /**
     * Sets the HTTP RESTful action verb.
     *
     * The given action might be GET, POST, PUT or DELETE. Be aware
     * that no any action can be performed on any resource.
     *
     * @param      string $verb http action verb
     */
    final public function setRestfulAction($verb)
    {
        $this->httpVerb = $verb;
    }


    /**
     * Enables the API authentication.
     *
     * Authentication is only required and therefore enabled for some privacy related
     * functions like accessing your profile or reports.
     *
     *
     * @param bool $status
     */
    final public function setSecureApiCall($status = false)
    {
        $this->auth->setSecureApiCall($status);
    }


    /**
     * Serializes item.
     *
     * Transforms array into json or xml string while using the parser class.
     * This is neccessary in order to update or create new items.
     *
     * @param      string $rootName name of root node
     * @param      array $itemArray array of item elements
     * @param      array $attr root node attribute
     *
     *
     * @return     string
     */
    final public function serialize($rootName, $itemArray, $attr = array())
    {
        $attr['xmlns'] = 'http://api.zanox.com/namespace/' . $this->version . '/';

        if ($this->protocol == Constants::PROTOCOL_JSON) {
            $body = $this->parser->serializeJson($rootName, $itemArray, $attr);
        } else {
            $body = $this->parser->serializeXml($rootName, $itemArray, $attr);
        }

        return $body;
    }


    /**
     * Unserializes item.
     *
     * Transforms json or xml string into array while using the parser class.
     *
     * @param      string $string xml or json string
     *
     *
     * @return     string
     */
    final public function unserialize($string)
    {
        if ($this->protocol == Constants::PROTOCOL_JSON) {
            $body = $this->parser->unserializeJson($string);
        } else {
            $body = $this->parser->unserializeXml($string);
        }

        return $body;
    }


    /**
     * Performs SOAP request.
     *
     * @param      string $service
     * @param      string $method soap method
     * @param      array $params soap method parameter
     *
     * @throws \Zanox\Api\ClientException
     *
     * @return     object                  soap result object or false on error
     */
    public function doSoapRequest($service, $method, $params = array())
    {
        $options['trace'] = true;
        $options['features'] = SOAP_SINGLE_ELEMENT_ARRAYS;

        if ($this->proxyHost) {
            $options['proxy_host'] = $this->proxyHost;
            $options['proxy_port'] = $this->proxyPort;
            $options['proxy_login'] = $this->proxyLogin;
            $options['proxy_password'] = $this->proxyPassword;
        }

        if ($this->httpCompression) {
            $options['compression'] = SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP;
        }

        $soap = new SoapClient($this->getWsdlUrl($service), $options);

        $params['connectId'] = $this->auth->getConnectId();

        if ($service == Constants::SERVICE_CONNECT) {
            $params['publicKey'] = $this->auth->getPublicKey();
        }

        if ($this->auth->isSecureApiCall()) {
            $time = gmdate('Y-m-d\TH:i:s') . ".000Z";

            $this->auth->setTimeStamp($time);
            $nonce = $this->auth->getNonce();

            $params['timestamp'] = $time;
            $params['nonce'] = $nonce;
            $params['signature'] = $this->auth->getSignature(
                $service,
                $method,
                $nonce
            );
        }

        try {
            $result = $soap->__soapCall($method, array($params));

            if (empty($result->code) || $result->code == 200) {
                return $result;
            }
        } catch (SoapFault $stacktrace) {
            $stacktrace = "\n\n[STACKTRACE]\n" . $stacktrace;
            $soapRequest = "\n\n[REQUEST]\n" . $soap->__getLastRequest();
            $soapResponse = "\n\n[RESPONSE]\n" . $soap->__getLastResponse();

            throw new ClientException($stacktrace . $soapRequest . $soapResponse);
        }

        return false;
    }


    /**
     * Performs REST request.
     *
     * The function creates the RESTful request URL out of the given resource URI
     * and the given REST interface.  A REST URI for example to request a program
     * with the id 49 looks like this: /programs/program/49
     *
     * @param      array $resource RESTful resource e.g. /programs
     * @param      array $parameter HTTP query parameter e.g. /programs?q=telecom
     * @param      string $body HTTP xml body message
     *
     *
     * @return     string      $result         returns http response
     */
    public function doRestfulRequest($resource, $parameter = [], $body = '')
    {
        $uri = "/" . implode("/", $resource) . "/";

        $header['authorization'] = 'ZXWS ' . $this->auth->getConnectId();

        $header['user-agent'] = Constants::USERAGENT;
        $header['host'] = Constants::HOST;

        if ($this->auth->isSecureApiCall()) {
            $header['nonce'] = $this->auth->getNonce();
            $header['date'] = gmdate('D, d M Y H:i:s T');

            $this->auth->setTimeStamp($header['date']);

            $sign = $this->auth->getSignature($this->httpVerb, $uri, $header['nonce']);

            $header['authorization'] = $header['authorization'] . ":" . $sign;
        }

        if ($this->httpVerb == Constants::PUT || $this->httpVerb == Constants::POST) {
            $header['content-length'] = strlen($body);
        }

        if ($body) {
            $header['content-type'] = $this->getContentType();
        }

        $uri = $this->getRestfulPath() . $uri;

        if (is_array($parameter)) {
            $query = http_build_query($parameter, '', '&');

            if (strlen($query) > 0) {
                $uri .= '?' . $query;
            }
        }

        $result = $this->httpRequest($uri, $header, $body);

        if ($result) {
            return $result;
        }

        return false;
    }


    /**
     * HTTP REST Connection (GET/POST)
     *
     * @param          string $uri uri path
     * @param          array $header list of header params
     * @param          string|bool $body POST data
     *
     * @throws \Zanox\Api\ClientException
     *
     * @return         mixed                       response data or false
     */
    private function httpRequest($uri, $header, $body = false)
    {
        $responseHeader = '';
        $responseContent = '';

        if ($this->proxyHost) {
            $fp = fsockopen($this->proxyHost, $this->proxyPort);
        } else {
            if ($this->httpProtocol == Constants::HTTPS) {
                $fp = fsockopen(Constants::SSL_PREFIX . Constants::HOST, Constants::HTTPS_PORT);
            } else {
                $fp = fsockopen(Constants::HOST, Constants::HTTP_PORT);
            }
        }

        if (!$fp) {
            throw new ClientException("Coudn't open socket!");
        }

        if ($this->proxyHost) {
            $requestHeader = $this->httpVerb . " " . $uri .
                " HTTP/1.0\r\nHost: " . $this->proxyHost . "\r\n\r\n";
        } else {
            $requestHeader = $this->httpVerb . " " . $uri . " HTTP/1.1\r\n";
        }

        foreach ($header as $key => $value) {
            $requestHeader .= ucwords($key) . ": " . $value . "\r\n";
        }

        if ($this->httpCompression) {
            $requestHeader .= "Accept-Encoding: gzip, deflate, compress;q=0.9\r\n";
        }

        $requestHeader .= "connection: close\r\n\r\n";

        if ($body) {
            $requestHeader .= $body;
        }

        fwrite($fp, $requestHeader);

        do {
            if (feof($fp)) {
                break;
            }
            $responseHeader .= fread($fp, 1);
        } while (!preg_match('/\\r\\n\\r\\n$/', $responseHeader));

        if ($this->isValidHeader($responseHeader)) {
            if (!stristr($responseHeader, "Transfer-Encoding: chunked")) {
                while (!feof($fp)) {
                    $responseContent .= fgets($fp, 128);
                }
            } else {
                while (($chunk_length = hexdec(fgets($fp)))) {
                    $responseContentChunk = '';

                    $read_length = 0;

                    while ($read_length < $chunk_length) {
                        $responseContentChunk .= fread(
                            $fp,
                            $chunk_length -
                            $read_length
                        );

                        $read_length = strlen($responseContentChunk);
                    }

                    $responseContent .= $responseContentChunk;

                    fgets($fp);

                }
            }

            return chop($responseContent);
        } else {
            throw new ClientException(
                "\n" . $requestHeader . "\n" .
                $responseHeader
            );
        }
    }


    /**
     * Returns wsdl api endpoint
     *
     * @param      string $service soap service
     *
     *
     * @return     string                      wsdl url including version
     */
    private function getWsdlUrl($service)
    {
        if ($this->httpProtocol == Constants::HTTPS) {
            $prefix = Constants::HTTPS_PREFIX;
        } else {
            $prefix = Constants::HTTP_PREFIX;
        }

        switch ($service) {
            case Constants::SERVICE_PUBLISHER:
                if ($this->version) {
                    return $prefix . Constants::HOST . Constants::URI_WSDL . '/' . $this->version;
                }

                return $prefix . Constants::HOST . Constants::URI_WSDL;
                break;
            case Constants::SERVICE_CONNECT:
                return $prefix . Constants::OAUTH_HOST . Constants::URI_WSDL;
                break;
            case Constants::SERVICE_DATA:
                return $prefix . Constants::DATA_HOST . Constants::URI_WSDL;
                break;
        }

        return null;
    }


    /**
     * Returns restful api endpoint path
     *
     *
     * @return     string                      endpoint without host
     */
    private function getRestfulPath()
    {
        if ($this->protocol == Constants::PROTOCOL_JSON) {
            $uri = Constants::URI_JSON;
        } else {
            $uri = Constants::URI_XML;
        }

        if ($this->version) {
            $uri = $uri . '/' . $this->version;
        }

        return $uri;
    }


    /**
     * Returns if http response is valid.
     *
     * Method checks if request response returns HTTP status code 200
     * or not. If the status code is different from 200 the method
     * returns false.
     *
     * @param      string $responseHeader request uri
     *
     *
     * @return     string                  encoded string
     */
    private function isValidHeader($responseHeader)
    {
        $header = explode("\n", $responseHeader);

        if (count($header) > 0) {
            $status_line = explode(" ", $header[0]);

            if (count($status_line) >= 3 && $status_line[1] == '200') {
                return true;
            }
        }

        return false;
    }


    /**
     * Returns restful content type
     *
     *
     * @return     string                      http content type
     */
    private function getContentType()
    {
        if ($this->protocol == Constants::PROTOCOL_JSON) {
            return Constants::CONTENT_JSON;
        }

        return Constants::CONTENT_XML;
    }
}

