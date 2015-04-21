<?php

/**
 * EC2インスタンスの立ち上げを担うクラス
 *
 * @package    Qo
 * @subpackage Aws
 **/
namespace Qo\Aws\Ec2;

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
    public function setEc2Client ($ec2_client)
    {
        $this->ec2_client = $ec2_client;
    }


    /**
     * 指定メッセージをタグとしてEC2インスタンスを立ち上げる
     *
     * @return void
     **/
    public function execute ()
    {
        $this->_validateParameters();

        $ami_id = $this->_getTripleiCoreAmiId();
        // $this->_buildInstance($ami_id);
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
    }


    /**
     * TripleiCoreのAMIイメージIDを取得する
     *
     * @return string
     **/
    private function _getTripleiCoreAmiId ()
    {
        $results = $this->ec2_client->describeImages([
            'Owners' => ['self'],
            'Filters' => [
                [
                    'Name' => 'tag:Name',
                    'Values' => ['TripleI/Core']
                ]
            ]
        ]);
    }


    /**
     * インスタンスを立ち上げる
     *
     * @return void
     **/
    private function _buildInstance ()
    {
        // todo
        // コマンド実行する仲介クラスを作ってTerraformで起動する予定
    }
}

