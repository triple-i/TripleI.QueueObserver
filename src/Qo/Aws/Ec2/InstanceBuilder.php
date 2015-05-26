<?php

/**
 * EC2インスタンスの立ち上げを担うクラス
 *
 * @package    Qo
 * @subpackage Aws
 **/
namespace Qo\Aws\Ec2;

use Aws\Ec2\Ec2Client;
use Qo\Command\Runner;
use Qo\Error\Exception\QoException;

class InstanceBuilder
{

    /**
     * @var object
     **/
    private $msg;


    /**
     * @var Runner
     **/
    private $runner;


    /**
     * @var Ec2Client
     **/
    private $ec2_client;


    /**
     * @param  object $msg
     *
     * @return void
     **/
    public function setMessage ($msg)
    {
        $this->msg = $msg;
    }


    /**
     * @param  Runnner $runner
     * @return void
     **/
    public function setRunner (Runner $runner)
    {
        $this->runner = $runner;
    }


    /**
     * @param  Ec2Client $ec2_client
     * @return void
     **/
    public function setEc2Client (Ec2Client $ec2_client)
    {
        $this->ec2_client = $ec2_client;
    }


    /**
     * 指定メッセージをタグとしてEC2インスタンスを立ち上げる
     *
     * @return boolean
     **/
    public function execute ()
    {
        $this->_validateParameters();
        $this->_buildInstance();

        return true;
    }


    /**
     * パラメータが正しく設定されているかを判別する
     *
     * @return void
     **/
    private function _validateParameters ()
    {
        if (is_null($this->msg)) {
            throw new QoException('キューメッセージを指定してください');
        }

        if (is_null($this->runner)) {
            throw new QoException('Runnerクラスが指定されていません');
        }

        if (is_null($this->ec2_client)) {
            throw new QoException('Ec2クライアントクラスが指定されていません');
        }
    }


    /**
     * インスタンスを立ち上げる
     *
     * @return void
     **/
    private function _buildInstance ()
    {
        // TripleI.ServerConfigs の Adapter コマンドを介して GeminiApp インスタンスを立ち上げる
        $command = '/home/fedora/TripleI.ServerConfigs/bin/adapter '.
            'aws build gemini-app production';

        $output = $this->runner->execute($command);

        // 先頭行は Adapter 越しの実態コマンドのログだから破棄する
        $output = preg_replace('/^[^\n]*\n/', '', $output);
        $output = json_decode(trim($output));

        // 生成したインスタンス情報
        $instances = $output->Instances;

        // タグを付与する
        $this->_taggingInstance($instances);
    }


    /**
     * @param  array $instances
     * @return void
     **/
    private function _taggingInstance ($instances)
    {
        $resources = array_map(function ($i) {
            return $i->InstanceId;
        }, $instances);

        $instance_name = sprintf('GeminiApp-%s', $this->msg->timestamp);
        $r = $this->ec2_client->createTags([
            'Resources' => $resources,
            'Tags' => [[
                'Key'   => 'Name',
                'Value' => $instance_name
            ], [
                'Key'   => 'message_id',
                'Value' => $this->msg->message_id
            ], [
                'Key'   => 'action',
                'Value' => $this->msg->action
            ], [
                'Key'   => 'publish_type',
                'Value' => $this->msg->publish_type
            ], [
                'Key'   => 'book_name',
                'Value' =>$this->msg->book_name
            ], [
                'Key'   => 'client',
                'Value' => $this->msg->client
            ], [
                'Key'   => 'user',
                'Value' => $this->msg->user
            ]]
        ]);
    }
}

