<?php

namespace Lastfm;

class Client
{
    const STATUS_SUCCESS = 'ok';
    const STATUS_ERROR   = 'error';

    private $apiKey;
    private $transport;

    private $trackService;

    /**
     * Constructor
     *
     * @param  Transport $transport
     */
    public function __construct($apiKey = null, Transport $transport = null)
    {
        $this->setApiKey($apiKey);

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

        $result = json_decode($rawResult, false);

        if (!is_array($result)) {
            throw new \RuntimeException('Unable to deserialize API response.');
        }

        if (isset($result['error'])) {
            if (isset($result['message'])) {
                $message = sprintf('Api error (%d): %s', $result['error'], $result['message']);
            } else {
                $message = sprintf('Api error (%d) with no message.', $result['error']);
            }

            throw new \RuntimeException($message);
        }

        return $result;
    }

    /**
     * Returns a Track service instance
     *
     * @return \Lastfm\Service\Track
     */
    public function getTrackService()
    {
        if (null === $this->trackService) {
            $this->trackService = new Service\Track($this);
        }

        return $this->trackService;
    }
}
