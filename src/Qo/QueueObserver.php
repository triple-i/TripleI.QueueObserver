<?php

/**
 * 監視の起点となるクラス
 *
 * @package Qo
 **/
namespace Qo;

use Qo\Aws\Ec2\InstanceBuilder;
use Qo\Aws\Sqs\Queue;
use Qo\Aws\Sqs\Receiver;

use Qo\Error\Exception\QoException;

class QueueObserver
{

    /**
     * @var boolean
     **/
    private $dry_run = false;


    /**
     * @var Receiver
     **/
    private $receiver;


    /**
     * @var Instancebuilder
     **/
    private $builder;


    /**
     * @return void
     **/
    public function enableDryRun ()
    {
        $this->dry_run = true;
    }


    /**
     * @param  Receiver $receiver
     * @return void
     **/
    public function setReceiver (Receiver $receiver)
    {
        $this->receiver = $receiver;
    }


    /**
     * @param  Instancebuilder $builder
     * @return void
     **/
    public function setInstanceBuilder (InstanceBuilder $builder)
    {
        $this->builder = $builder;
    }


    /**
     * @return void
     **/
    public function execute ()
    {
        $this->_validateParameters();
        $this->_monitoring();

        return true;
    }


    /**
     * パラメータのバリデーション
     *
     * @return void
     **/
    private function _validateParameters ()
    {
        if (is_null($this->receiver)) {
            throw new QoException('Receiverクラスが指定されていません');
        }

        if (is_null($this->builder)) {
            throw new QoException('InstanceBuilderクラスが指定されていません');
        }
    }


    /**
     * @return void
     **/
    private function _monitoring ()
    {
        while ($this->dry_run === false) {
            $msg = $this->receiver->execute();

            if (! is_null($msg)) {
                $this->_buildEc2Instance($msg);
            }

            sleep(1);
        }
    }


    /**
     * @param  obejct $params
     * @return void
     **/
    private function _buildEc2Instance ($params)
    {
        $this->builder->execute();
    }

}
