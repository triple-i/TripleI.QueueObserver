<?php


use Qo\Aws\Sqs\Queue;

class QueueTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     * @expectedException           \Qo\Error\Exception\QoException
     * @expectedExceptionMessage    存在しないキュー名称です
     * @group queue-wrong-queue-name
     * @group queue
     **/
    public function 無効なキュー名称を指定した場合 ()
    {
        $name = 'foo';
        $url = Queue::getUrl($name);
    }


    /**
     * @test
     * @group queue-get-url
     * @group queue
     **/
    public function 指定キューのURLを取得する ()
    {
        $url = Queue::getUrl('GEMINI_PUBLISH_VER2');
        $this->assertEquals($url, 'https://sqs.ap-northeast-1.amazonaws.com/637549107398/GEMINI_PUBLISH_VER2');

        $url = Queue::getUrl('gemini_queue');
        $this->assertEquals($url, 'https://sqs.ap-northeast-1.amazonaws.com/637549107398/GEMINI_QUEUE');
    }


    /**
     * @test
     * @group queue-get-test-url
     * @group queue
     **/
    public function デバッグ用キューのURLを取得する ()
    {
        $url = Queue::getUrl('GEMINI_PUBLISH_TEST');
        $this->assertEquals($url, 'https://sqs.ap-northeast-1.amazonaws.com/637549107398/GEMINI_PUBLISH_TEST');
    }
}
