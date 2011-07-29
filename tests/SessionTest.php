<?php

namespace Lastfm;

class SessionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $session = new Session();
        $this->assertNull($session->getUsername());

        $session = new Session('Foobar', 'TheKey');
        $this->assertEquals('Foobar', $session->getUsername());
        $this->assertEquals('TheKey', $session->getKey());
    }

    public function testSetUsername()
    {
        $session = new Session();
        $session->setUsername('Foobar');
        $this->assertEquals('Foobar', $session->getUsername());
    }

    public function testSetKey()
    {
        $session = new Session();
        $session->setKey('TheKey');
        $this->assertEquals('TheKey', $session->getKey());
    }
}
