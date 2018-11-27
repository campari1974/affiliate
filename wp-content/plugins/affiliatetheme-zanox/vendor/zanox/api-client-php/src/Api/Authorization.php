<?php

namespace Zanox\Api;

/**
 * Api Authorization
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
 * @copyright   Copyright � 2007-2009 zanox.de AG
 *
 * @uses        PEAR Crypt_HMAC (required PHP < 5.1.2)
 */
class Authorization implements AuthorizationInterface
{

    /**
     * zanox connect ID
     *
     * @var     string $connectId zanox connect id
     *

     */
    private $connectId = '';


    /**
     * zanox shared secret key
     *
     * @var     string $secrectKey secret key to sign messages
     */
    private $secretKey = '';


    /**
     * zanox public key
     *
     * @var     string $applicationId application id for oauth
     */
    private $publicKey = '';


    /**
     * Timestamp of the message
     *
     * @var     string $timestamp timestamp to sign the message
     */
    private $timestamp = false;


    /**
     * api version
     *
     * @var     string $version api version
     */
    private $version = false;


    /**
     * message security
     *
     * @var     boolean
     */
    private $msg_security = false;


    /**
     * Contructor
     *

     */
    function __construct()
    {
    }


    /**
     * Set connectId
     *
     * @param      string $timestamp time stamp
     *
     *

     */
    final public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }


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
    final public function getTimestamp()
    {
        return $this->timestamp;
    }


    /**
     * Set the connectId
     *
     * @param      string $connectId zanox connectId
     *
     *

     */
    final public function setConnectId($connectId)
    {
        $this->connectId = $connectId;
    }


    /**
     * Returns the connectId
     *
     * @return     string                      zanox connect id
     */
    final public function getConnectId()
    {
        return $this->connectId;
    }


    /**
     * Sets the public key.
     *
     * @param      string $publicKey public key
     *
     *

     */
    final public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;
    }


    /**
     * Returns the public key
     *
     * @return     string                      zanox public key
     */
    final public function getPublicKey()
    {
        return $this->publicKey;
    }


    /**
     * Set SecretKey
     *
     * @param      string $secretKey zanox secret key
     *
     *

     */
    final public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;
    }


    /**
     * Sets the API version to use.
     *
     * @param      string $version API version
     *
     *

     */
    final public function setVersion($version)
    {
        $this->version = $version;
    }


    /**
     * Enables the API authentication.
     *
     * Authentication is only required and therefore enabled for some privacy
     * related methods like accessing your profile or reports.
     *
     * @param bool $status
     */
    final public function setSecureApiCall($status = false)
    {
        $this->msg_security = $status;
    }


    /**
     * Returns message security status.
     *
     * Method returns true if message needs to signed with crypted hmac
     * string and nonce. Otherwise false is returned.
     *
     * @return     bool                        true if secure message
     */
    final public function isSecureApiCall()
    {
        return $this->msg_security;
    }


    /**
     * Returns the crypted hash signature for the message.
     *
     * Builds the signed string consisting of the rest action verb, the uri used
     * and the timestamp of the message. Be aware of the 15 minutes timeframe
     * when setting the time manually.
     *
     * @param      string $service service name or restful action
     * @param      string $method method or uri
     * @param      string $nonce nonce of request
     *
     * @return     string                      encoded string
     */
    final public function getSignature($service, $method, $nonce)
    {
        $sign = $service . strtolower($method) . $this->timestamp;

        if (!empty($nonce)) {
            $sign .= $nonce;
        }

        $hmac = $this->hmac($sign);

        if ($hmac) {
            return $hmac;
        }

        return false;
    }


    /**
     * Returns hash based nonce.
     *
     * @see    http://en.wikipedia.org/wiki/Cryptographic_nonce
     *
     * @return     string                           md5 hash-based nonce
     */
    final public function getNonce()
    {
        $mt = microtime();
        $rand = mt_rand();

        return md5($mt . $rand);
    }


    /**
     * Encodes the given message parameters with Base64.
     *
     * @param      string $str string to encode
     *
     * @return     string                          encoded string
     */
    private function encodeBase64($str)
    {
        $encode = '';

        for ($i = 0; $i < strlen($str); $i += 2) {
            $encode .= chr(hexdec(substr($str, $i, 2)));
        }

        return base64_encode($encode);
    }


    /**
     * Creates secured HMAC signature of the message parameters.
     *
     * Uses the hash_hmac function if available (PHP needs to be >= 5.1.2).
     * Otherwise it uses the PEAR/CRYP_HMAC library to sign and crypt the
     * message. Make sure you have at least one of the options working on your
     * system.
     *
     * @param      string $mesgparams message to sign
     *
     * @return     string                          signed sha1 message hash
     */
    private function hmac($mesgparams)
    {
        $hmac = hash_hmac('sha1', utf8_encode($mesgparams), $this->secretKey);
        $hmac = $this->encodeBase64($hmac);

        return $hmac;
    }

}

