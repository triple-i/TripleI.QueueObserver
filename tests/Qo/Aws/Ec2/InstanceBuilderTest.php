<?php


use Qo\Aws\Ec2\InstanceBuilder;
use Qo\Mock\MockTestCase;

class InstanceBuilderTest extends MockTestCase
{

    /**
     * @var InstanceBuilder
     **/
    private $builder;


    /**
     * @var Ec2Client
     **/
    private $ec2_client;


    /**
     * @return void
     **/
    public function setUp ()
    {
        $this->builder    = new InstanceBuilder();
        $this->ec2_client = $this->getEc2Mock();
    }


    /**
     * @test
     * @expectedException           \Qo\Error\Exception\QoException
     * @expectedExceptionMessage    キューメッセージを指定してください
     * @group builder-not-set-msg
     * @group builder
     **/
    public function キューメッセージが指定されていない場合 ()
    {
        $this->builder->execute();
    }


    /**
     * @test
     * @expectedException          \Qo\Error\Exception\QoException
     * @expectedExceptionMessage   EC2クライアントクラスが指定されていません
     * @group builder-not-set-ec2-client
     * @group builder
     **/
    public function EC2クライアントクラスを指定していない場合 ()
    {
        $msg = new \stdClass;

        $this->builder->setMessage($msg);
        $this->builder->execute();
    }


    /**
     * @test
     * @large
     * @group builder-not-found-ami
     * @group builder
     **/
    // public function TripleiCoreのAMIが見つからなかった場合 ()
    // {
        // $msg = new \stdClass;

        // $this->builder->setMessage($msg);
        // $this->builder->setEc2Client($this->ec2_client);
        // $this->builder->execute();
    // }
}

