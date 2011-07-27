<?php

namespace Lastfm\Api;

class TrackTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $client = $this->getMock('Lastfm\Client');
        $track = new Track($client);
    }

    public function testGetInfo()
    {
        $client = $this->getMock('Lastfm\Client');
        $client
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo('Track.getInfo'), $this->equalTo(array('mbid' => 'foobar')))
            ->will($this->returnValue('TheClientReturnValue'))
        ;
        $track = new Track($client);
        $this->assertEquals('TheClientReturnValue', $track->getInfo(array('mbid' => 'foobar')));
    }
}
