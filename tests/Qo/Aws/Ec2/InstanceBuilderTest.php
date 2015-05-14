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
     * @var Runner
     **/
    private $runner;

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
        $this->runner     = $this->getRunnerMock();
        $this->ec2_client = $this->getEc2Mock();
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
     * @expectedExceptionMessage   Runnerクラスが指定されていません
     * @group builder-not-set-runner
     * @group builder
     **/
    public function Runnerクラスを指定していない場合 ()
    {
        $msg = new \stdClass;

        $this->builder->setMessage($msg);
        $this->builder->execute();
    }


    /**
     * @test
     * @expectedException          Qo\Error\Exception\QoException
     * @expectedExceptionMessage   Ec2クライアントクラスが指定されていません
     * @group builder-not-set-ec2-client
     * @group builder
     **/
    public function Ec2クライアントクラスを指定していない場合 ()
    {
        $msg = new \stdClass;

        $this->builder->setMessage($msg);
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
        $msg->message_id = 'fb676f597607583fb402789d0b91d3ad17f58cb6';
        $msg->action = 'fo';
        $msg->publish_type = 'fopdf_only';
        $msg->book_name = 'Gemini-Sample';
        $msg->client = 'default';
        $msg->timestamp = '20150512171808';
        $msg->user = 'info@iii-planning.com';

        $this->runner->expects($this->any())
            ->method('execute')
            ->will($this->returnValue($this->_getRunInstanceResponse()));

        $this->builder->setMessage($msg);
        $this->builder->setEc2Client($this->ec2_client);
        $this->builder->setRunner($this->runner);
        $results = $this->builder->execute();

        $this->assertTrue($results);
    }


    /**
     * @return string
     **/
    private function _getRunInstanceResponse ()
    {
        return 'execute: aws ec2 run-instances --cli-input-json '.
            'file://gemini-app/aws/production.json --image-id ami-f0ec2bf0 '.
            '--user-data file://gemini-app/user-data/production.sh
{
    "OwnerId": "637549107398",
    "ReservationId": "r-e54fc116",
    "Groups": [
        {
            "GroupName": "WebService",
            "GroupId": "sg-b81a81b9"
        }
    ],
    "Instances": [
        {
            "Monitoring": {
                "State": "pending"
            },
            "PublicDnsName": "",
            "KernelId": "aki-176bf516",
            "State": {
                "Code": 0,
                "Name": "pending"
            },
            "EbsOptimized": false,
            "LaunchTime": "2015-05-13T05:13:00.000Z",
            "ProductCodes": [],
            "StateTransitionReason": "",
            "InstanceId": "i-76c4f985",
            "ImageId": "ami-f0ec2bf0",
            "PrivateDnsName": "",
            "KeyName": "a_1",
            "SecurityGroups": [
                {
                    "GroupName": "WebService",
                    "GroupId": "sg-b81a81b9"
                }
            ],
            "ClientToken": "",
            "InstanceType": "t1.micro",
            "NetworkInterfaces": [],
            "Placement": {
                "Tenancy": "default",
                "GroupName": "",
                "AvailabilityZone": "ap-northeast-1a"
            },
            "Hypervisor": "xen",
            "BlockDeviceMappings": [],
            "Architecture": "x86_64",
            "StateReason": {
                "Message": "pending",
                "Code": "pending"
            },
            "RootDeviceName": "/dev/sda",
            "VirtualizationType": "paravirtual",
            "RootDeviceType": "ebs",
            "AmiLaunchIndex": 0
        }
    ]
}';
    }
}

