<?php


use Qo\Aws\Sqs\Receiver;
use Qo\Mock\MockTestCase;

class RecieverTest extends MockTestCase
{

    /**
     * @var Receiver
     **/
    private $receiver;


    /**
     * @var SqsClient_Mock
     **/
    private $sqs_client;


    /**
     * @var string
     **/
    private $queue_url = 'https://queue-observer.com/RECEIVER_TEST';


    /**
     * @return void
     **/
    public function setUp ()
    {
        $this->receiver   = new Receiver();
        $this->sqs_client = $this->getSqsMock();
    }


    /**
     * @test
     * @expectedException           \Qo\Error\Exception\QoException
     * @expectedExceptionMessage    SQSクライアントクラスが指定されていません
     * @group receiver-not-set-sqs-client
     * @group receiver
     **/
    public function SQSクライアントクラスが指定されていない場合 ()
    {
        $this->receiver->execute();
    }


    /**
     * @test
     * @expectedException           \Qo\Error\Exception\QoException
     * @expectedExceptionMessage    キューのURLが指定されていません
     * @group receiver-not-set-queue-url
     * @group receiver
     **/
    public function キューのURLを指定していない場合 ()
    {
        $this->receiver->setSqsClient($this->sqs_client);
        $this->receiver->execute();
    }


    /**
     * @test
     * @group receiver-receive-empty-message
     * @group receiver
     **/
    public function 受信したメッセージが空だった場合 ()
    {
        $res = $this->getMock('Guzzle\Service\Resource\Model', ['getPath']);
        $res->expects($this->any())->method('getPath')->willReturn(null);

        $this->sqs_client->expects($this->any())
            ->method('receiveMessage')->willReturn($res);

        $this->receiver->setSqsClient($this->sqs_client);
        $this->receiver->setQueueUrl($this->queue_url);
        $message = $this->receiver->execute();

        $this->assertNull($message);
    }


    /**
     * @test
     * @group receiver-execute
     * @group receiver
     **/
    public function 正常な処理 ()
    {
        $res = $this->getMock('Guzzle\Service\Resource\Model', ['getPath']);
        $res->expects($this->any())
            ->method('getPath')->willReturn([
                'Body' => '{"message":"test", "user":"test"}'
            ]);

        $this->sqs_client->expects($this->any())
            ->method('receiveMessage')->willReturn($res);

        $this->receiver->setSqsClient($this->sqs_client);
        $this->receiver->setQueueUrl($this->queue_url);
        $message = $this->receiver->execute();

        $this->assertInstanceOf('stdClass', $message);
    }
}

