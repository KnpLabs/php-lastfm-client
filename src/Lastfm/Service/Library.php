<?php

namespace Lastfm\Service;

use Lastfm\Service;
use Lastfm\Transport;

/**
 * Library service class
 *
 * @package Last.fm
 * @author  Antoine Hérault <antoine.herault@gmail.com>
 */
class Library extends Service
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->addMethod('addAlbum', array(
            'http_method'               => Transport::HTTP_METHOD_POST,
            'requires_authentication'   => true
        ));
        $this->addMethod('addArtist', array(
            'http_method'               => Transport::HTTP_METHOD_POST,
            'requires_authentication'   => true
        ));
        $this->addMethod('addTrack', array(
            'http_method'               => Transport::HTTP_METHOD_POST,
            'requires_authentication'   => true
        ));
        $this->addMethod('getAlbums');
        $this->addMethod('getArtists');
        $this->addMethod('getTracks');
        $this->addMethod('removeAlbum', array(
            'http_method'               => Transport::HTTP_METHOD_POST,
            'requires_authentication'   => true
        ));
        $this->addMethod('removeAlbum', array(
            'http_method'               => Transport::HTTP_METHOD_POST,
            'requires_authentication'   => true
        ));
        $this->addMethod('removeArtist', array(
            'http_method'               => Transport::HTTP_METHOD_POST,
            'requires_authentication'   => true
        ));
        $this->addMethod('removeScrobble', array(
            'http_method'               => Transport::HTTP_METHOD_POST,
            'requires_authentication'   => true
        ));
        $this->addMethod('removeTrack', array(
            'http_method'               => Transport::HTTP_METHOD_POST,
            'requires_authentication'   => true
        ));
    }
}
