<?php

namespace Lastfm;

class ServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testAddMethod()
    {
        $service = $this->getMockForAbstractClass('Lastfm\Service', array($this->getClientMock()));

        $r = new \ReflectionMethod($service, 'addMethod');
        $r->setAccessible(true);

        $r->invokeArgs($service, array('foo'));
        $r->invokeArgs($service, array('bar', true, Transport::HTTP_METHOD_POST));

        $r = new \ReflectionMethod($service, 'getMethods');
        $r->setAccessible(true);

        $this->assertEquals(
            array(
                'foo'   => array(
                    'requires_authentication'   => false,
                    'http_method'               => Transport::HTTP_METHOD_GET
                ),
                'bar'   => array(
                    'requires_authentication'   => true,
                    'http_method'               => Transport::HTTP_METHOD_POST
                )
            ),
            $r->invoke($service)
        );
    }

    public function testHasMethod()
    {
        $service = $this->getMockForAbstractClass('Lastfm\Service', array($this->getClientMock()));

        $r = new \ReflectionMethod($service, 'addMethod');
        $r->setAccessible(true);

        $r->invokeArgs($service, array('foo'));

        $hasMethod = new \ReflectionMethod($service, 'hasMethod');
        $hasMethod->setAccessible(true);

        $this->assertTrue($hasMethod->invokeArgs($service, array('foo')));
        $this->assertFalse($hasMethod->invokeArgs($service, array('bar')));

        $r->invokeArgs($service, array('bar'));

        $this->assertTrue($hasMethod->invokeArgs($service, array('foo')));
        $this->assertTrue($hasMethod->invokeArgs($service, array('bar')));
    }

    public function testGetMethodOptions()
    {
        $service = $this->getMockForAbstractClass('Lastfm\Service', array($this->getClientMock()));

        $r = new \ReflectionMethod($service, 'addMethod');
        $r->setAccessible(true);

        $r->invokeArgs($service, array('foo'));
        $r->invokeArgs($service, array('bar', true, Transport::HTTP_METHOD_POST));

        $getMethodOptions = new \ReflectionMethod($service, 'getMethodOptions');
        $getMethodOptions->setAccessible(true);

        $this->assertEquals(
            array(
                'requires_authentication'   => false,
                'http_method'               => Transport::HTTP_METHOD_GET
            ),
            $getMethodOptions->invokeArgs($service, array('foo'))
        );

        $this->assertEquals(
            array(
                'requires_authentication'   => true,
                'http_method'               => Transport::HTTP_METHOD_POST
            ),
            $getMethodOptions->invokeArgs($service, array('bar'))
        );
    }

    public function testCall()
    {
        $client = $this->getClientMock();
        $client
            ->expects($this->once())
            ->method('request')
            ->with($this->equalTo(Transport::HTTP_METHOD_POST), $this->equalTo('TheService.theMethod'), $this->equalTo(array('foo' => 'bar')))
            ->will($this->returnValue('TheClientReturnValue'));
        ;

        $service = $this->getMock('Lastfm\Service', array('configure', 'getName'), array($client));
        $service
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('TheService'))
        ;

        $r = new \ReflectionMethod($service, 'addMethod');
        $r->setAccessible(true);
        $r->invokeArgs($service, array('theMethod', false, Transport::HTTP_METHOD_POST));

        $this->assertEquals('TheClientReturnValue', $service->theMethod(array('foo' => 'bar')));


        $message = '->__call() raises an exception when an undefined method is called';
        try {
            $service->badMethod(array('foo' => 'bar'));
            $exception = false;
        } catch (\Exception $e) {
            $exception = true;
        }

        if (true === $exception) {
            $this->anything($message);
        } else {
            $this->fail($message);
        }
    }

    public function getClientMock()
    {
        return $this->getMock('Lastfm\Client');
    }
}
