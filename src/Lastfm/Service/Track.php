<?php

namespace Lastfm\Service;

use Lastfm\Client;

/**
 * Track API class
 *
 * @package Lastfm
 * @author  Antoine Hérault <antoine.herault@gmail.com>
 */
class Track
{
    private $client;

    /**
     * Constructor
     *
     * @param  Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getInfo(array $parameters = array())
    {
        return $this->client->get('Track.getInfo', $parameters);
    }
}
