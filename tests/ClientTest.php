<?php

namespace Lastfm;

use Lastfm\Transport;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $client = new Client();
        $this->assertNull($client->getApiKey());
        $this->assertNull($client->getSecret());
        $this->assertNull($client->getSession());
        $this->assertInstanceOf('Lastfm\Transport', $client->getTransport());

        $session = $this->getMock('Lastfm\Session');
        $transport = $this->getMock('Lastfm\Transport');
        $client = new Client('theApiKey', 'theSecret', $session, $transport);
        $this->assertEquals('theApiKey', $client->getApiKey());
        $this->assertEquals('theSecret', $client->getSecret());
        $this->assertEquals($session, $client->getSession());
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

    public function testSetSecret()
    {
        $client = new Client();
        $client->setSecret('theSecret');
        $this->assertEquals('theSecret', $client->getSecret());
    }

    public function testSetSession()
    {
        $session = $this->getMock('Lastfm\Session');
        $client = new Client();
        $client->setSession($session);
        $this->assertEquals($session, $client->getSession());
        $client->setSession(null);
        $this->assertNull($client->getSession());
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
        $client = new Client(null, null, null, $transport);
        $this->assertEquals('the_response', $client->request(Transport::HTTP_METHOD_GET, 'Foo.bar', array('foo' => 'bar'), false, false, true));

        $transport = $this->getMock('Lastfm\Transport', array('request'));
        $transport
            ->expects($this->once())
            ->method('request')
            ->with($this->equalTo(Transport::HTTP_METHOD_GET), $this->equalTo('foo.bar'), $this->equalTo(array('api_key' => 'theApiKey', 'foo' => 'bar')))
            ->will($this->returnValue('the_response'))
        ;
        $client = new Client('theApiKey', 'theSecret', null, $transport);
        $this->assertEquals('the_response', $client->request(Transport::HTTP_METHOD_GET, 'foo.bar', array('foo' => 'bar'), false, false, true));

        $transport = $this->getMock('Lastfm\Transport', array('request'));
        $transport
            ->expects($this->once())
            ->method('request')
            ->with($this->equalTo(Transport::HTTP_METHOD_GET), $this->equalTo('foo.bar'), $this->equalTo(array('api_key' => 'theApiKey', 'foo' => 'bar')))
            ->will($this->returnValue('the_response'))
        ;
        $client = new Client('theApiKey', 'theSecret', null, $transport);
        $this->assertequals('the_response', $client->request(Transport::HTTP_METHOD_GET, 'foo.bar', array('foo' => 'bar'), false, false, true));

        $transport = $this->getMock('Lastfm\Transport', array('request'));
        $transport
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->equalTo(Transport::HTTP_METHOD_GET),
                $this->equalTo('foo.bar'),
                $this->equalTo(array('api_key' => 'theApiKey', 'foo' => 'bar', 'api_sig' => md5('api_keytheApiKeyfoobarmethodfoo.bartheSecret')))
            )
            ->will($this->returnValue('the_response'))
        ;
        $client = new Client('theApiKey', 'theSecret', null, $transport);
        $this->assertequals('the_response', $client->request(Transport::HTTP_METHOD_GET, 'foo.bar', array('foo' => 'bar'), true, false, true));

        $transport = $this->getMock('Lastfm\Transport', array('request'));
        $transport
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->equalTo(Transport::HTTP_METHOD_GET),
                $this->equalTo('foo.bar'),
                $this->equalTo(array('api_key' => 'theApiKey', 'foo' => 'bar', 'sk' => 'theSessionKey'))
            )
            ->will($this->returnValue('the_response'))
        ;
        $session = new Session('John', 'theSessionKey');
        $client = new Client('theApiKey', 'theSecret', $session, $transport);
        $this->assertequals('the_response', $client->request(Transport::HTTP_METHOD_GET, 'foo.bar', array('foo' => 'bar'), false, true, true));
    }

    /**
     * @expectedException LogicException
     */
    public function testRequestAddSessionWhenTheClientHasNoConfiguredSession()
    {
        $transport = $this->getMock('Lastfm\Transport');
        $client = new Client('theApiKey', 'theSecret', null, $transport);
        $client->request(Transport::HTTP_METHOD_GET, 'foo.bar', array('foo' => 'bar'), false, true);
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
                $this->equalTo(array('foo' => 'bar')),
                $this->equalTo(false),
                $this->equalTo(false),
                $this->equalTo(false)
            )
            ->will($this->returnValue('THE_REQUEST_RETURN_VALUE'))
        ;

        $this->assertEquals('THE_REQUEST_RETURN_VALUE', $client->get('Foo.bar', array('foo' => 'bar')));

        $client = $this->getMock('Lastfm\Client', array('request'));
        $client
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->equalTo(Transport::HTTP_METHOD_GET),
                $this->equalTo('Foo.bar'),
                $this->equalTo(array('foo' => 'bar')),
                $this->equalTo(true),
                $this->equalTo(true),
                $this->equalTo(true)
            )
            ->will($this->returnValue('THE_REQUEST_RETURN_VALUE'))
        ;

        $this->assertEquals('THE_REQUEST_RETURN_VALUE', $client->get('Foo.bar', array('foo' => 'bar'), true, true, true));
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
                $this->equalTo(array('foo' => 'bar')),
                $this->equalTo(false),
                $this->equalTo(false),
                $this->equalTo(false)
            )
            ->will($this->returnValue('THE_REQUEST_RETURN_VALUE'))
        ;

        $this->assertEquals('THE_REQUEST_RETURN_VALUE', $client->post('Foo.bar', array('foo' => 'bar')));

        $client = $this->getMock('Lastfm\Client', array('request'));
        $client
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->equalTo(Transport::HTTP_METHOD_POST),
                $this->equalTo('Foo.bar'),
                $this->equalTo(array('foo' => 'bar')),
                $this->equalTo(true),
                $this->equalTo(true),
                $this->equalTo(true)
            )
            ->will($this->returnValue('THE_REQUEST_RETURN_VALUE'))
        ;

        $this->assertEquals('THE_REQUEST_RETURN_VALUE', $client->post('Foo.bar', array('foo' => 'bar'), true, true, true));
    }

    /**
     * @dataProvider dataForGetService
     */
    public function testGetService($name, $className)
    {
        $client = new Client();
        $method = sprintf('get%sService', ucfirst($name));

        $r = new \ReflectionMethod($client, $method);

        $this->assertInstanceOf($className, $r->invoke($client));
    }

    public function dataForGetService()
    {
        return array(
            array('album', 'Lastfm\Service\Album'),
            array('artist', 'Lastfm\Service\Artist'),
            array('auth', 'Lastfm\Service\Auth'),
            array('chart', 'Lastfm\Service\Chart'),
            array('event', 'Lastfm\Service\Event'),
            array('geo', 'Lastfm\Service\Geo'),
            array('group', 'Lastfm\Service\Group'),
            array('library', 'Lastfm\Service\Library'),
            array('playlist', 'Lastfm\Service\Playlist'),
            array('radio', 'Lastfm\Service\Radio'),
            array('tag', 'Lastfm\Service\Tag'),
            array('tasteometer', 'Lastfm\Service\Tasteometer'),
            array('track', 'Lastfm\Service\Track'),
            array('user', 'Lastfm\Service\User'),
            array('venue', 'Lastfm\Service\Venue')
        );
    }

    /**
     * @dataProvider dataForCreateMethodSignature
     */
    public function testCreateMethodSignature($secret, $parameters, $expectedSignature)
    {
        $client = new Client('theApiKey', $secret);

        $r = new \ReflectionMethod($client, 'createMethodSignature');
        $r->setAccessible(true);

        $this->assertEquals($expectedSignature, $r->invokeArgs($client, array($parameters)));
    }

    public function dataForCreateMethodSignature()
    {
        return array(
            array(
                'theSecret',
                array(),
                md5('theSecret')
            ),
            array(
                'theSecret',
                array('foo' => 'bar'),
                md5('foobartheSecret')
            ),
            array(
                'theSecret',
                array('foo' => 'bar', 'baz' => 'bat'),
                md5('bazbatfoobartheSecret')
            ),
        );
    }

    /**
     * @expectedException LogicException
     */
    public function testCreateMethodSignatureWhenTheClientHasNoConfiguredSecret()
    {
        $client = new Client('theApiKey');

        $r = new \ReflectionMethod($client, 'createMethodSignature');
        $r->setAccessible(true);

        $r->invokeArgs($client, array(array('foo' => 'bar')));
    }
}
