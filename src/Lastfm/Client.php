<?php

namespace Lastfm;

/**
 * The Last.fm client
 *
 * @package Last.fm
 * @author  Antoine Hérault <antoine.herault@gmail.com>
 */
class Client
{
    const STATUS_SUCCESS = 'ok';
    const STATUS_ERROR   = 'error';

    private $apiKey;
    private $secret;
    private $session;
    private $transport;

    private $services;

    /**
     * Constructor
     *
     * @param  string    $apiKey    Your API key
     * @param  string    $secret    Your secret
     * @param  Session   $session   A Session instance
     * @param  Transport $transport A Transport instance
     */
    public function __construct($apiKey = null, $secret = null, Session $session = null, Transport $transport = null)
    {
        $this->setApiKey($apiKey);
        $this->setSecret($secret);
        $this->setSession($session);

        if (null === $transport) {
            $transport = new Transport\Curl();
        }

        $this->setTransport($transport);
    }

    /**
     * Defines the api key
     *
     * @param  string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Returns the api key
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Defines the secret
     *
     * @param  string $secret
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    /**
     * Return the secret
     *
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Defines the session
     *
     * @param  Session $session
     */
    public function setSession(Session $session = null)
    {
        $this->session = $session;
    }

    /**
     * Returns the session
     *
     * @return Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Defines the underlying transport
     *
     * @param  Transport $transport
     */
    public function setTransport(Transport $transport)
    {
        $this->transport = $transport;
    }

    /**
     * Returns the underlying transport
     *
     * @return Transport
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * Shortcut method to perform a GET request
     *
     * @param  string $apiMethod
     * @param  array  $parameters
     *
     * @return array
     */
    public function get($apiMethod, array $parameters = array())
    {
        return $this->request(Transport::HTTP_METHOD_GET, $apiMethod, $parameters);
    }

    /**
     * Shortcut method to perform a POST request
     *
     * @param  string $apiMethod
     * @param  array  $parameters
     *
     * @return array
     */
    public function post($apiMethod, array $parameters = array())
    {
        return $this->request(Transport::HTTP_METHOD_POST, $apiMethod, $parameters);
    }

    /**
     * Performs an API request and returns the result
     *
     * @param  string  $httpMethod The HTTP method (one of the Transport::HTTP_METHOD_* constants)
     * @param  string  $apiMethod  The API method
     * @param  array   $parameters An array of parameters
     * @param  boolean $raw        Whether to return the raw result
     *
     * @return mixed
     */
    public function request($httpMethod, $apiMethod, array $parameters = array(), $raw = false)
    {
        if (null !== $this->apiKey) {
            $parameters['api_key'] = $this->apiKey;
        }

        if (!$raw) {
            $parameters['format'] = 'json';
        }

        $rawResult = $this->transport->request($httpMethod, $apiMethod, $parameters);

        if ($raw) {
            return $rawResult;
        }

        $result = json_decode($rawResult, true);

        if (!is_array($result)) {
            throw new Exception\InvalidResponse('Unable to deserialize API response.');
        }

        if (isset($result['error'])) {
            if (isset($result['message'])) {
                $message = sprintf('Api error (%d): %s', $result['error'], $result['message']);
            } else {
                $message = sprintf('Api error (%d) with no message.', $result['error']);
            }

            throw new Exception\ErrorResponse($message, $result['error']);
        }

        if (1 === count($result)) {
            $result = reset($result);
        }

        return 1 === count($result) ? reset($result) : $result;
    }

    /**
     * Returns a Track service instance
     *
     * @return \Lastfm\Service\Track
     */
    public function getTrackService()
    {
        if (!isset($this->services['track'])) {
            $this->services['track'] = new Service\Track($this);
        }

        return $this->services['track'];
    }

    /**
     * Returns an Album service instance
     *
     * @return \Lastfm\Service\Album
     */
    public function getAlbumService()
    {
        if (!isset($this->services['album'])) {
            $this->services['album'] = new Service\Album($this);
        }

        return $this->services['album'];
    }
}
