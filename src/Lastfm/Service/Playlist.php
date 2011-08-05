<?php

namespace Lastfm\Service;

use Lastfm\Service;
use Lastfm\Transport;

/**
 * Playlist service class
 *
 * @package Last.fm
 * @author  Antoine Hérault <antoine.herault@gmail.com>
 */
class Playlist extends Service
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->addMethod('addTrack', array(
            'http_method'               => Transport::HTTP_METHOD_POST,
            'requires_authentication'   => true
        ));
        $this->addMethod('create', array(
            'http_method'               => Transport::HTTP_METHOD_POST,
            'requires_authentication'   => true
        ));
        $this->addMethod('fetch');
    }
}
