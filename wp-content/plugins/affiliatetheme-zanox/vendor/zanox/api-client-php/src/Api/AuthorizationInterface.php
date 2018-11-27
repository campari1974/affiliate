<?php

namespace Zanox\Api;

/**
 * AuthorizationInterface Interface
 *
 * The AuthorizationInterface Interface defines the methods that need to be implemented
 * in order to support the required hash-based signing of messages.
 *
 * Supported Version: PHP >= 5.0
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
 * @copyright   Copyright (c) 2007-2011 zanox.de AG
 */
interface AuthorizationInterface
{

    /**
     * Set connectId
     *
     *
     * @param      string $timestamp time stamp
     */
    public function setTimestamp($timestamp);


    /**
     * Returns the current REST timestamp.
     *
     * If there hasn't already been set a datetime we create one automatically.
     * As a format the HTTP Header protocol RFC format is taken.
     *
     * @see        see HTTP RFC for the datetime format
     *
     * @return     string                      message timestamp
     */
    public function getTimestamp();


    /**
     * Set connectId
     *
     *
     * @param      string $connectId zanox connectId
     */
    public function setConnectId($connectId);


    /**
     * Returns connectId
     *
     *
     * @return     string                      zanox connect id
     */
    public function getConnectId();


    /**
     * Sets the public key.
     *
     *
     * @param      string $publicKey public key
     */
    public function setPublicKey($publicKey);


    /**
     * Returns the public key
     *
     *
     * @return     string                      zanox public key
     */
    public function getPublicKey();


    /**
     * Set SecretKey
     *
     *
     * @param      string $secretKey zanox secret key
     */
    public function setSecretKey($secretKey);


    /**
     * Sets the API version to use.
     *
     * @param      string $version API version
     */
    public function setVersion($version);


    /**
     * Enables the API authentication.
     *
     * Authentication is only required and therefore enabled for some privacy
     * related methods like accessing your profile or reports.
     *
     * @param bool $status
     * @return
     */
    public function setSecureApiCall($status = false);


    /**
     * Returns message security status.
     *
     * Method returns true if message needs to signed with crypted hmac
     * string and nonce. Otherwise false is returned.
     *
     *
     * @return     bool                        true if secure message
     */
    public function isSecureApiCall();


    /**
     * Returns the crypted hash signature for a api message.
     *
     * Builds the signed string consisting of the rest action verb, the uri used
     * and the timestamp of the message. Be aware of the 15 minutes timeframe
     * when setting the time manually.
     *
     *
     * @param      string $service service name or restful action
     * @param      string $method method or uri
     * @param      string $nonce nonce of request
     *
     * @return     string                      encoded string
     */
    public function getSignature($service, $method, $nonce);


    /**
     * Returns nonce.
     *
     * @see         http://en.wikipedia.org/wiki/Cryptographic_nonce
     *
     *
     * @return      string                      nonce
     */
    public function getNonce();

}

