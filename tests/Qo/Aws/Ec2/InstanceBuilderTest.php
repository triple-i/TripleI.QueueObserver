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
     * @var Runner
     **/
    private $runner;


    /**
     * @return void
     **/
    public function setUp ()
    {
        $this->builder    = new InstanceBuilder();
        $this->ec2_client = $this->getEc2Mock();
        $this->runner     = $this->getRunnerMock();
    }


    /**
     * @test
     * @expectedException           Qo\Error\Exception\QoException
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
     * @expectedException          Qo\Error\Exception\QoException
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
     * @expectedException          Qo\Error\Exception\QoException
     * @expectedExceptionMessage   Runnerクラスが指定されていません
     * @group builder-not-set-runner
     * @group builder
     **/
    public function Runnerクラスを指定していない場合 ()
    {
        $msg = new \stdClass;

        $this->builder->setMessage($msg);
        $this->builder->setEc2Client($this->ec2_client);
        $this->builder->execute();
    }


    /**
     * @test
     * @large
     * @expectedException          Qo\Error\Exception\QoException
     * @expectedExceptionMessage   TripleI/Coreイメージが見つかりませんでした
     * @group builder-not-found-ami
     * @group builder
     **/
    public function TripleiCoreのAMIが見つからなかった場合 ()
    {
        $msg = new \stdClass;
        $results = [];

        $images = $this->getMock('Guzzle\Service\Resource\Model');
        $images->expects($this->any())
            ->method('get')
            ->will($this->returnValue($results));

        $this->ec2_client->expects($this->any())
            ->method('describeImages')
            ->will($this->returnValue($images));

        $this->builder->setMessage($msg);
        $this->builder->setEc2Client($this->ec2_client);
        $this->builder->setRunner($this->runner);
        $this->builder->execute();
    }


    /**
     * @test
     * @large
     * @group builder-execute
     * @group builder
     **/
    public function 正常な処理 ()
    {
        $msg = new \stdClass;

        $results = [[
            'Name' => 'TripleI/Core 20150101',
            'ImageId' => 'ImageId-20150101'
        ], [
            'Name' => 'TripleI/Core 20150401',
            'ImageId' => 'ImageId-20150401'
        ]];

        $images = $this->getMock('Guzzle\Service\Resource\Model');
        $images->expects($this->any())
            ->method('get')
            ->will($this->returnValue($results));

        $this->ec2_client->expects($this->any())
            ->method('describeImages')
            ->will($this->returnValue($images));

        $this->builder->setMessage($msg);
        $this->builder->setEc2Client($this->ec2_client);
        $this->builder->setRunner($this->runner);
        $results = $this->builder->execute();

        $this->assertTrue($results);
    }
}

