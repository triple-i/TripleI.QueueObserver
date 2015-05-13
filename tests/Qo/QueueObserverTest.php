<?php

use Qo\QueueObserver;
use Qo\Mock\MockTestCase;

class QueueObserverTest extends MockTestCase
{

    /**
     * @var QueueObserver
     **/
    private $qo;


    /**
     * @var Receiver
     **/
    private $receiver;


    /**
     * @var InstanceBuilder
     **/
    private $builder;


    /**
     * @var Sweeper
     **/
    private $sweeper;


    /**
     * @return void
     **/
    public function setUp ()
    {
        $this->qo = new QueueObserver();

        $this->receiver = $this->getReceiverMock();
        $this->builder  = $this->getInstanceBuilderMock();
        $this->sweeper  = $this->getSweeperMock();
    }


    /**
     * @test
     * @expectedException          Qo\Error\Exception\QoException
     * @expectedExceptionMessage   Receiverクラスが指定されていません
     * @group qo-not-set-receiver
     * @group qo
     **/
    public function Receiverクラスが指定されていない場合 ()
    {
        $this->qo->execute();
    }


    /**
     * @test
     * @expectedException          Qo\Error\Exception\QoException
     * @expectedExceptionMessage   InstanceBuilderクラスが指定されていません
     * @group qo-not-set-builder
     * @group qo
     **/
    public function InstanceBuilderクラスが指定されていない場合 ()
    {
        $this->qo->setReceiver($this->receiver);
        $this->qo->execute();
    }


    /**
     * @test
     * @expectedException          Qo\Error\Exception\QoException
     * @expectedExceptionMessage   Sweeperクラスが指定されていません
     * @group qo-not-set-sweeper
     * @group qo
     **/
    public function Sweeperクラスが指定されていない場合 ()
    {
        $this->qo->setReceiver($this->receiver);
        $this->qo->setInstanceBuilder($this->builder);
        $this->qo->execute();
    }


    /**
     * @test
     * @large
     * @group qo-execute
     * @group qo
     **/
    public function 正常な処理 ()
    {
        $msg = new \stdClass;
        $msg->message_id = 'fb676f597607583fb402789d0b91d3ad17f58cb6';
        $msg->action = 'fo';
        $msg->publish_type = 'fopdf_only';
        $msg->book_name = 'Gemini-Sample';
        $msg->client = 'default';
        $msg->timestamp = '20150512171808';
        $msg->user = 'info@iii-planning.com';

        $this->receiver->expects($this->any())
            ->method('execute')
            ->will($this->returnValue($msg));

        $this->qo->setReceiver($this->receiver);
        $this->qo->setInstanceBuilder($this->builder);
        $this->qo->setSweeper($this->sweeper);
        $this->qo->enableDryRun();
        $result = $this->qo->execute();

        $this->assertTrue($result);
    }

}
