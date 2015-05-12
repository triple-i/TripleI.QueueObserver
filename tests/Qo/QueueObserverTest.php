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
     * @return void
     **/
    public function setUp ()
    {
        $this->qo = new QueueObserver();

        $this->receiver = $this->getReceiverMock();
        $this->builder  = $this->getInstanceBuilderMock();
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
     * @group qo-execute
     * @group qo
     **/
    public function 正常な処理 ()
    {
        $this->qo->setReceiver($this->receiver);
        $this->qo->setInstanceBuilder($this->builder);
        $this->qo->enableDryRun();
        $result = $this->qo->execute();

        $this->assertTrue($result);
    }

}
