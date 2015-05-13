<?php


use Qo\Mock\MockTestCase;
use Qo\Aws\Sqs\Sweeper;

class SweeperTest extends MockTestCase
{

    /**
     * @var Sweeper
     **/
    private $sweeper;


    /**
     * @var string
     **/
    private $queue_url = 'https://queue-observer.com/SWEEPER_TEST';


    /**
     * @var SqsClient
     **/
    private $sqs_client;


    /**
     * @var void
     **/
    public function setUp ()
    {
        $this->sweeper    = new Sweeper();
        $this->sqs_client = $this->getSqsMock();
    }


    /**
     * @test
     * @expectedException          Qo\Error\Exception\QoException
     * @expectedExceptionMessage   キューメッセージを指定してください
     * @group sweeper-not-set-msg
     * @group sweeper
     **/
    public function キューメッセージが指定されていない場合 ()
    {
        $this->sweeper->execute();
    }


    /**
     * @test
     * @expectedException           \Qo\Error\Exception\QoException
     * @expectedExceptionMessage    キューのURLが指定されていません
     * @group sweeper-not-set-queue-url
     * @group sweeper
     **/
    public function キューのURLを指定していない場合 ()
    {
        $msg = new \stdClass;

        $this->sweeper->setMessage($msg);
        $this->sweeper->execute();
    }


    /**
     * @test
     * @expectedException          Qo\Error\Exception\QoException
     * @expectedExceptionMessage   SqsClientクラスが指定されていません
     * @group sweeper-not-set-sqs
     * @group sweeper
     **/
    public function SqsClientクラスが指定されていない場合 ()
    {
        $msg = new \stdClass;

        $this->sweeper->setMessage($msg);
        $this->sweeper->setQueueUrl($this->queue_url);
        $this->sweeper->execute();
    }


    /**
     * @test
     * @group sweeper-execute
     * @group sweeper
     **/
    public function 正常な処理 ()
    {
        $msg = new \stdClass;
        $msg->receipt_handle = 'receipt_handle';

        $this->sweeper->setMessage($msg);
        $this->sweeper->setQueueUrl($this->queue_url);
        $this->sweeper->setSqsClient($this->sqs_client);
        $result = $this->sweeper->execute();

        $this->assertTrue($result);
    }

}

