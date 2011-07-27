<?php

namespace Lastfm;

use Lastfm\Transport;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $client = new Client();
        $this->assertNull($client->getApiKey());
        $this->assertInstanceOf('Lastfm\Transport', $client->getTransport());

        $transport = $this->getMock('Lastfm\Transport');
        $client = new Client('theApiKey', $transport);
        $this->assertEquals('theApiKey', $client->getApiKey());
        $this->assertEquals($transport, $client->getTransport());
    }

    public function testSetTransport()
    {
        $client = new Client();
        $transport = $this->getMock('Lastfm\Transport');
        $client->setTransport($transport);
        $this->assertEquals($transport, $client->getTransport());
    }

    public function testSetApiKey()
    {
        $client = new Client();
        $client->setApiKey('theApiKey');
        $this->assertEquals('theApiKey', $client->getApiKey());
    }

    public function testRequest()
    {
        $transport = $this->getMock('Lastfm\Transport', array('request'));
        $transport
            ->expects($this->once())
            ->method('request')
            ->with($this->equalTo(Transport::HTTP_METHOD_GET), $this->equalTo('Foo.bar'), $this->equalTo(array('foo' => 'bar')))
            ->will($this->returnValue('the_response'))
        ;
        $client = new Client(null, $transport);
        $this->assertEquals('the_response', $client->request(Transport::HTTP_METHOD_GET, 'Foo.bar', array('foo' => 'bar'), true));

        $transport = $this->getMock('Lastfm\Transport', array('request'));
        $transport
            ->expects($this->once())
            ->method('request')
            ->with($this->equalTo(Transport::HTTP_METHOD_GET), $this->equalTo('foo.bar'), $this->equalTo(array('api_key' => 'theApiKey', 'foo' => 'bar')))
            ->will($this->returnValue('the_response'))
        ;
        $client = new Client('theApiKey', $transport);
        $this->assertEquals('the_response', $client->request(Transport::HTTP_METHOD_GET, 'foo.bar', array('foo' => 'bar'), true));

        $transport = $this->getMock('Lastfm\Transport', array('request'));
        $transport
            ->expects($this->once())
            ->method('request')
            ->with($this->equalTo(Transport::HTTP_METHOD_GET), $this->equalTo('foo.bar'), $this->equalTo(array('api_key' => 'theApiKey', 'foo' => 'bar')))
            ->will($this->returnValue('the_response'))
        ;
        $client = new Client('theApiKey', $transport);
        $this->assertequals('the_response', $client->request(Transport::HTTP_METHOD_GET, 'foo.bar', array('foo' => 'bar'), true));
    }

    public function testGet()
    {
        $client = $this->getMock('Lastfm\Client', array('request'));
        $client
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->equalTo(Transport::HTTP_METHOD_GET),
                $this->equalTo('Foo.bar'),
                $this->equalTo(array('foo' => 'bar'))
            )
            ->will($this->returnValue('THE_REQUEST_RETURN_VALUE'))
        ;

        $this->assertEquals('THE_REQUEST_RETURN_VALUE', $client->get('Foo.bar', array('foo' => 'bar')));
    }

    public function testPost()
    {
        $client = $this->getMock('Lastfm\Client', array('request'));
        $client
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->equalTo(Transport::HTTP_METHOD_POST),
                $this->equalTo('Foo.bar'),
                $this->equalTo(array('foo' => 'bar'))
            )
            ->will($this->returnValue('THE_REQUEST_RETURN_VALUE'))
        ;

        $this->assertEquals('THE_REQUEST_RETURN_VALUE', $client->post('Foo.bar', array('foo' => 'bar')));
    }

    public function testGetTrackService()
    {
        $client = new Client();
        $this->assertInstanceOf('Lastfm\Service\Track', $client->getTrackService());
    }
}
