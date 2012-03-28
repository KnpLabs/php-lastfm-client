<?php

namespace Lastfm\Service;

use Lastfm\Transport;

class TrackTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $client = $this->getMock('Lastfm\Client');
        $track = new Track($client);
    }

    public function testGetInfo()
    {
        $client = $this->getMock('Lastfm\Client', array('request'));
        $client
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->equalTo(Transport::HTTP_METHOD_GET),
                $this->equalTo('track.getInfo'),
                $this->equalTo(array('mbid' => 'foobar'))
            )
            ->will($this->returnValue('TheClientReturnValue'))
        ;
        $track = new Track($client);
        $this->assertEquals('TheClientReturnValue', $track->getInfo(array('mbid' => 'foobar')));
    }
}
