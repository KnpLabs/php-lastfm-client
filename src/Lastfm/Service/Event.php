<?php

namespace Lastfm\Service;

use Lastfm\Service;
use Lastfm\Transport;

/**
 * Event service class
 *
 * @package Last.fm
 * @author  Antoine Hérault <antoine.herault@gmail.com>
 */
class Event extends Service
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->addMethod('attend', array(
            'http_method'               => Transport::HTTP_METHOD_POST,
            'requires_authentication'   => true
        ));
        $this->addMethod('getAttendees');
        $this->addMethod('getInfo');
        $this->addMethod('getShouts');
        $this->addMethod('share', array(
            'http_method'               => Transport::HTTP_METHOD_POST,
            'requires_authentication'   => true
        ));
        $this->addMethod('shout', array(
            'http_method'               => Transport::HTTP_METHOD_POST,
            'requires_authentication'   => true
        ));
    }
}
