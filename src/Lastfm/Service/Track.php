<?php

namespace Lastfm\Service;

use Lastfm\Service;
use Lastfm\Transport;

/**
 * Track service class
 *
 * @package Lastfm
 * @author  Antoine Hérault <antoine.herault@gmail.com>
 */
class Track extends Service
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
        $this->addMethod('ban', array(
            'http_method'               => Transport::HTTP_METHOD_POST,
            'requires_authentication'   => true
        ));
        $this->addMethod('getBuylinks');
        $this->addMethod('getCorrection');
        $this->addMethod('getFingerprintMetadata');
        $this->addMethod('getInfo');
        $this->addMethod('getShouts');
        $this->addMethod('getSimilar');
        $this->addMethod('getTags', array(
            'requires_authentication'   => true
        ));
        $this->addMethod('getTopFans');
        $this->addMethod('getTopTags');
        $this->addMethod('love', array(
            'http_method'               => Transport::HTTP_METHOD_POST,
            'requires_authentication'   => true
        ));
        $this->addMethod('removeTag', array(
            'http_method'               => Transport::HTTP_METHOD_POST,
            'requires_authentication'   => true
        ));
        $this->addMethod('scrobble', array(
            'http_method'               => Transport::HTTP_METHOD_POST,
            'requires_authentication'   => true
        ));
        $this->addMethod('search');
        $this->addMethod('share', array(
            'http_method'               => Transport::HTTP_METHOD_POST,
            'requires_authentication'   => true
        ));
        $this->addMethod('unban', array(
            'http_method'               => Transport::HTTP_METHOD_POST,
            'requires_authentication'   => true
        ));
        $this->addMethod('unlove', array(
            'http_method'               => Transport::HTTP_METHOD_POST,
            'requires_authentication'   => true
        ));
        $this->addMethod('updateNowPlaying', array(
            'http_method'               => Transport::HTTP_METHOD_POST,
            'requires_authentication'   => true
        ));
    }
}
