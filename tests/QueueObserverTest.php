<?php

namespace TripleI\QueueObserver;

class QueueObserverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var QueueObserver
     */
    protected $skeleton;

    protected function setUp()
    {
        $this->skeleton = new QueueObserver;
    }

    public function testNew()
    {
        $actual = $this->skeleton;
        $this->assertInstanceOf('\TripleI\QueueObserver\QueueObserver', $actual);
    }

    /**
     * @expectedException \TripleI\QueueObserver\Exception\LogicException
     */
    public function testException()
    {
        throw new Exception\LogicException;
    }
}
