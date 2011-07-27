<?php

namespace Lastfm\Transport;

use Lastfm\Transport;

class CurlTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $transport = new Curl();
        $this->assertInstanceOf('Lastfm\Transport', $transport, 'the Curl transport class implements the Transport interface');
        $this->assertEquals('http://ws.audioscrobbler.com/2.0/', $transport->getEndPoint());
        $this->assertEquals(10, $transport->getTimeout());
        $this->assertEquals('Last.fm PHP Client', $transport->getUserAgent());

        $transport = new Curl('http://foobar.baz', 5, 'Foobar');
        $this->assertEquals('http://foobar.baz', $transport->getEndPoint());
        $this->assertEquals(5, $transport->getTimeout());
        $this->assertEquals('Foobar', $transport->getUserAgent());
    }

    public function testSetEndPoint()
    {
        $transport = new Curl();
        $transport->setEndPoint('foobar');
        $this->assertEquals('foobar', $transport->getEndPoint(), '->setEndPoint() defines the end point');
    }

    public function testSetUserAgent()
    {
        $transport = new Curl();
        $transport->setUserAgent('Foobar');
        $this->assertEquals('Foobar', $transport->getUserAgent());
    }

    public function testSetTimeout()
    {
        $transport = new Curl();
        $transport->setTimeout(5);
        $this->assertEquals(5, $transport->getTimeout());
    }

    /**
     * @dataProvider dataForRequest
     */
    public function testRequest($httpMethod, $apiMethod, $parameters, $expectedCurlOptions)
    {
        $transport = $this->getMock('Lastfm\Transport\Curl', array('doRequest'), array('http://the.end.point', 5, 'The User Agent'));
        $transport
            ->expects($this->once())
            ->method('doRequest')
            ->with($this->equalTo($expectedCurlOptions))
        ;
        $transport->request($httpMethod, $apiMethod, $parameters);
    }

    public function dataForRequest()
    {
        return array(
            array(
                Transport::HTTP_METHOD_GET,
                'Foo.bar',
                array('foo' => 'bar', 'baz' => 'bat'),
                array(
                    CURLOPT_URL         => 'http://the.end.point?foo=bar&baz=bat&method=Foo.bar',
                    CURLOPT_TIMEOUT     => 5,
                    CURLOPT_USERAGENT   => 'The User Agent'
                )
            ),
            array(
                Transport::HTTP_METHOD_POST,
                'Foo.bar',
                array('foo' => 'bar', 'baz' => 'bat'),
                array(
                    CURLOPT_URL         => 'http://the.end.point',
                    CURLOPT_TIMEOUT     => 5,
                    CURLOPT_USERAGENT   => 'The User Agent',
                    CURLOPT_POST        => true,
                    CURLOPT_POSTFIELDS  => array('method' => 'Foo.bar', 'foo' => 'bar', 'baz' => 'bat')
                )
            ),
        );
    }

    /**
     * @dataProvider dataForBuildUrl
     */
    public function testBuildUrl($endPoint, $parameters, $expectedUrl)
    {
        $transport = new Curl($endPoint);

        $r = new \ReflectionMethod($transport, 'buildUrl');
        $r->setAccessible(true);

        $this->assertEquals($expectedUrl, $r->invokeArgs($transport, array($parameters)));
    }

    public function dataForBuildUrl()
    {
        return array(
            array(
                'http://the.end.point',
                array('foo' => 'bar', 'baz' => 'bat'),
                'http://the.end.point?foo=bar&baz=bat',
            ),
            array(
                'http://the.end.point?ban=bag',
                array('foo' => 'bar', 'baz' => 'bat'),
                'http://the.end.point?ban=bag&foo=bar&baz=bat',
            ),
            array(
                'http://the.end.point#frag',
                array('foo' => 'bar', 'baz' => 'bat'),
                'http://the.end.point?foo=bar&baz=bat#frag',
            ),
        );
    }
}
