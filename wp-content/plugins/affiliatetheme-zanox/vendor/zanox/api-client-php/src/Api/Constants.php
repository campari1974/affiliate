<?php

namespace Zanox\Api;

/**
 * Api Constants Definitions.
 *
 * Supported Version: PHP >= 5.1.0
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
 */
class Constants
{

    /**
     * Api Protocol Types
     */
    const PROTOCOL_XML = 'xml';
    const PROTOCOL_JSON = 'json';
    const PROTOCOL_SOAP = 'soap';


    /**
     * Default Api Protocol
     *
     */
    const RESTFUL_INTERFACE = 'RestfulMethods';
    const SOAP_INTERFACE = 'SoapMethods';


    /**
     * Supported Api Versions
     */
    const VERSION_2009_07_01 = '2009-07-01';
    const VERSION_2011_03_01 = '2011-03-01';
    const VERSION_2015_05_01 = '2011-03-01';


    /**
     * Default Version & Protocol
     *
     */
    const PROTOCOL_DEFAULT = self::PROTOCOL_XML;
    const VERSION_DEFAULT = self::VERSION_2011_03_01;


    /**
     * Api Endpoint Definitions
     */
    const HTTP = 'HTTP';
    const HTTPS = 'HTTPS';
    const HTTP_PREFIX = 'http://';
    const HTTPS_PREFIX = 'https://';
    const SSL_PREFIX = 'ssl://';
    const HTTP_PORT = 80;
    const HTTPS_PORT = 443;
    const HOST = 'api.zanox.com';
    const OAUTH_HOST = 'auth.zanox.com';
    const DATA_HOST = 'data.zanox.com';
    const URI_WSDL = '/wsdl';
    const URI_SOAP = '/soap';
    const URI_XML = '/xml';
    const URI_JSON = '/json';


    /**
     * User Agent
     */
    const USERAGENT = 'zxPhp ApiClient';


    /**
     * Service Names
     */
    const SERVICE_PUBLISHER = 'publisherservice';
    const SERVICE_CONNECT = 'connectservice';
    const SERVICE_DATA = 'dataservice';


    /**
     * HTTP Restful Actions
     */
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';


    /**
     * HTTP Content Types
     */
    const CONTENT_XML = 'text/xml';
    const CONTENT_JSON = 'application/json';


    /**
     * ApiClient Internal Error Types
     */
    const CLI_ERROR_VERSION = 'Unsupported API version';
    const CLI_ERROR_PROTOCOL_VERSION = 'Unsupported API protocol/version';
    const CLI_ERROR_PROTOCOL_CLASS = 'Protocol class does not exist';
    const CLI_ERROR_PROTOCOL_CLASSFILE = 'Could not find protocol class file';
    const CLI_ERROR_PROTOCOL = 'Unsupported API protocol';

}
