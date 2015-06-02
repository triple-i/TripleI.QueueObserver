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
     * @var string
     **/
    private $queue_url = 'https://queue-observer.com/RECEIVER_TEST';


    /**
     * @var SqsClient_Mock
     **/
    private $sqs_client;


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
     * @expectedExceptionMessage    キューのURLが指定されていません
     * @group receiver-not-set-queue-url
     * @group receiver
     **/
    public function キューのURLを指定していない場合 ()
    {
        $this->receiver->execute();
    }


    /**
     * @test
     * @expectedException           \Qo\Error\Exception\QoException
     * @expectedExceptionMessage    SqsClientクラスが指定されていません
     * @group receiver-not-set-sqs-client
     * @group receiver
     **/
    public function SQSクライアントクラスが指定されていない場合 ()
    {
        $this->receiver->setQueueUrl($this->queue_url);
        $this->receiver->execute();
    }


    /**
     * @test
     * @group receiver-receive-empty-message
     * @group receiver
     **/
    public function 受信したメッセージが空だった場合 ()
    {
        $model = $this->getGuzzleModelMock();
        $model->expects($this->any())
            ->method('getPath')->willReturn(null);

        $this->sqs_client->expects($this->any())
            ->method('receiveMessage')->willReturn($model);

        $this->receiver->setQueueUrl($this->queue_url);
        $this->receiver->setSqsClient($this->sqs_client);
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
        $msg = '{
            "message_id": "fb676f597607583fb402789d0b91d3ad17f58cb6",
            "action": "fo",
            "publish_type": "fopdf_only",
            "book_name": "Gemini-Sample",
            "client": "default"
        }';

        // 受信ハンドル
        $receipt_handle = 'receipt_handle';

        $model = $this->getGuzzleModelMock();
        $model->expects($this->any())
            ->method('getPath')->willReturn([
                'Body' => $msg,
                'ReceiptHandle' => $receipt_handle
            ]);

        $this->sqs_client->expects($this->any())
            ->method('receiveMessage')->willReturn($model);

        $this->receiver->setQueueUrl($this->queue_url);
        $this->receiver->setSqsClient($this->sqs_client);
        $message = $this->receiver->execute();

        $this->assertInstanceOf('stdClass', $message);
        $this->assertEquals(
            'fb676f597607583fb402789d0b91d3ad17f58cb6', $message->message_id
        );
        $this->assertEquals('receipt_handle', $message->receipt_handle);
    }
}

