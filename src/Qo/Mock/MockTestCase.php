<?php

/**
 * モック生成に特化したユニットテスト用クラス
 *
 * @package Qo
 * @subpackage Mock
 **/
namespace Qo\Mock;

abstract class MockTestCase extends \PHPUnit_Framework_TestCase
{

    /**
     * インターフェイス
     *
     * @var array
     **/
    private $methods = array(
        'getAccessKeyId',
        'getSecretKey',
        'getSecurityToken',
        'getExpiration',
        'setAccessKeyId',
        'setSecretKey',
        'setSecurityToken',
        'setExpiration',
        'isExpired'
    );


    /**
     * コンストラクタ引数のモックを取得する
     *
     * @return array
     **/
    private function _getConstructArguments ()
    {
        $arguments = array(
            $this->_getCredentialsInterfaceMock(),
            $this->_getSignatureInterfaceMock(),
            $this->_getCollectionMock()
        );

        return $arguments;
    }


    /**
     * @return Collection
     **/
    private function _getCollectionMock ()
    {
        return $this->getMock('Guzzle\Common\Collection');
    }


    /**
     * @return CredentialsInterface
     **/
    private function _getCredentialsInterfaceMock ()
    {
        return $this->getMock('Aws\Common\Credentials\CredentialsInterface');
    }


    /**
     * @return SignatureInterface
     **/
    private function _getSignatureInterfaceMock ()
    {
        return $this->getMock('Aws\Common\Signature\SignatureInterface');
    }


    /**
     * @return Ec2Client_Mock
     **/
    public function getEc2Mock ()
    {
        $arguments = $this->_getConstructArguments();
        $methods   = array_merge($this->methods, [
            'createTags'
        ]);

        return $this->getMock('Aws\Ec2\Ec2Client', $methods, $arguments);
    }


    /**
     * @return InstanceBuilder
     **/
    public function getInstanceBuilderMock ()
    {
        return $this->getMock('Qo\Aws\Ec2\InstanceBuilder', [
            'execute', 'setEc2Client', 'setRunner'
        ]);
    }


    /**
     * @return Guzzle\Service\Resource\Model
     **/
    public function getGuzzleModelMock ()
    {
        return $this->getMock('Guzzle\Service\Resource\Model', [
            'get', 'getPath'
        ]);
    }


    /**
     * @return Receiver_Mock
     **/
    public function getReceiverMock ()
    {
        return $this->getMock('Qo\Aws\Sqs\Receiver', [
            'execute', 'setSqsClient', 'setQueueUrl'
        ]);
    }


    /**
     * @return Runner_Mock
     **/
    public function getRunnerMock ()
    {
        return $this->getMock('Qo\Command\Runner', [
            'execute'
        ]);
    }


    /**
     * @return SqsClient_Mock
     **/
    public function getSqsMock ()
    {
        $arguments = $this->_getConstructArguments();
        $methods   = array_merge($this->methods, [
            'deleteMessage', 'receiveMessage'
        ]);

        return $this->getMock('Aws\Sqs\SqsClient', $methods, $arguments);
    }


    /**
     * @return Sweeper_Mock
     **/
    public function getSweeperMock ()
    {
        return $this->getMock('Qo\Aws\Sqs\Sweeper', [
            'execute'
        ]);
    }
}
