<?php

namespace Lastfm\Service;

use Lastfm\Service;
use Lastfm\Transport;

/**
 * Radio service class
 *
 * @package Last.fm
 * @author  Antoine Hérault <antoine.herault@gmail.com>
 */
class Radio extends Service
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->addMethod('getPlaylist', array(
            'requires_authentication'   => true
        ));
        $this->addMethod('search');
        $this->addMethod('tune', array(
            'http_method'               => Transport::HTTP_METHOD_POST,
            'requires_authentication'   => true
        ));
    }
}
