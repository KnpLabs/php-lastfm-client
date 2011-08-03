<?php

namespace Lastfm\Service;

use Lastfm\Service;
use Lastfm\Transport;

/**
 * Album service class
 *
 * @package Lastfm
 * @author  Antoine Hérault <antoine.herault@gmail.com>
 */
class Album extends Service
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->addMethod('addTags', array(
            'http_method'               => Transport::HTTP_METHOD_POST,
            'requires_authentication'   => true
        ));
        $this->addMethod('getBuyLinks');
        $this->addMethod('getInfo');
        $this->addMethod('getShouts');
        $this->addMethod('getTags', array(
            'requires_authentication'   => true
        ));
        $this->addMethod('getTopTags');
        $this->addMethod('removeTag', array(
            'http_method'               => Transport::HTTP_METHOD_POST,
            'requires_authentication'   => true
        ));
        $this->addMethod('search');
        $this->addMethod('share', array(
            'http_method'               => Transport::HTTP_METHOD_POST,
            'requires_authentication'   => true
        ));
    }
}
