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
     * @var Ec2Client
     **/
    private $ec2_client;


    /**
     * @var Runner
     **/
    private $runner;


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
     * @param  Ec2Client $ec2_client
     * @return void
     **/
    public function setEc2Client (Ec2Client $ec2_client)
    {
        $this->ec2_client = $ec2_client;
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
     * 指定メッセージをタグとしてEC2インスタンスを立ち上げる
     *
     * @return boolean
     **/
    public function execute ()
    {
        $this->_validateParameters();

        $ami_id = $this->_getTripleiCoreAmiId();
        $this->_buildInstance($ami_id);

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

        if (is_null($this->ec2_client)) {
            throw new QoException('EC2クライアントクラスが指定されていません');
        }

        if (is_null($this->runner)) {
            throw new QoException('Runnerクラスが指定されていません');
        }
    }


    /**
     * TripleiCoreのAMIイメージIDを取得する
     *
     * @return string
     **/
    private function _getTripleiCoreAmiId ()
    {
        $images = $this->ec2_client->describeImages([
            'Owners' => ['self'],
            'Filters' => [
                [
                    'Name' => 'tag:Name',
                    'Values' => ['TripleI/Core']
                ]
            ]
        ]);

        $values = [];
        foreach ($images->get('Images') as $image) {
            $values[$image['Name']] = $image['ImageId'];
        }
        krsort($values);
        $image_id = reset($values);

        if (! $image_id) throw new QoException('TripleI/Coreイメージが見つかりませんでした');
    }


    /**
     * インスタンスを立ち上げる
     *
     * @return void
     **/
    private function _buildInstance ()
    {
        // TripleI.ServerConfigs の Adapter コマンドを介してインスタンスを立ち上げる
        $command = '/home/fedora/TripleI.ServerConfigs/bin/adapter '.
            'aws ec2 gemini-app production';

        $this->runner->execute($command);
    }
}

