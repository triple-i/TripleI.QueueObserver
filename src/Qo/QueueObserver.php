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
use Qo\Aws\Sqs\Sweeper;

use Qo\Error\Exception\QoException;

class QueueObserver
{

    /**
     * @var Receiver
     **/
    private $receiver;


    /**
     * @var Instancebuilder
     **/
    private $builder;


    /**
     * @var Sweeper
     **/
    private $sweeper;


    /**
     * @var boolean
     **/
    private $debug = false;


    /**
     * @return void
     **/
    public function enableDebug ()
    {
        $this->debug = true;
    }


    /**
     * @return void
     **/
    public function disableDebug ()
    {
        $this->debug = false;
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
     * @param  Sweeper $sweeper
     * @return void
     **/
    public function setSweeper (Sweeper $sweeper)
    {
        $this->sweeper = $sweeper;
    }


    /**
     * @return void
     **/
    public function execute ()
    {
        $this->_validateParameters();

        if (TEST === true || $this->debug === true) {
            $this->_monitoringWithDebugMode();
        } else {
            $this->_monitoring();
        }

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

        if (is_null($this->sweeper)) {
            throw new QoException('Sweeperクラスが指定されていません');
        }
    }


    /**
     * @return void
     **/
    private function _monitoring ()
    {
        while (true) {
            $msg = $this->_receiveMessage();
            sleep(1);
        }
    }


    /**
     * @return void
     **/
    private function _monitoringWithDebugMode ()
    {
        $msg = null;

        while (is_null($msg)) {
            $msg = $this->_receiveMessage();
            sleep(1);
        }
    }


    /**
     * @return object or null
     **/
    private function _receiveMessage ()
    {
        $msg = $this->receiver->execute();

        if (! is_null($msg)) {
            $this->_buildEc2Instance($msg);
            $this->_deleteQueue($msg);
        }

        return $msg;
    }


    /**
     * @param  obejct $msg
     * @return void
     **/
    private function _buildEc2Instance ($msg)
    {
        if ($this->debug) return false;
        $this->builder->setMessage($msg);
        $this->builder->execute();
    }


    /**
     * @param  object $msg
     * @return void
     **/
    private function _deleteQueue ($msg)
    {
        if ($this->debug) return false;
        $this->sweeper->setMessage($msg);
        $this->sweeper->execute();
    }

}
