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
        $methods   = array_merge($this->methods, []);

        return $this->getMock('Aws\Ec2\Ec2Client', $methods, $arguments);
    }


    /**
     * @return SqsClient_Mock
     **/
    public function getSqsMock ()
    {
        $arguments = $this->_getConstructArguments();
        $methods   = array_merge($this->methods, [
            'receiveMessage'
        ]);

        return $this->getMock('Aws\Sqs\SqsClient', $methods, $arguments);
    }
}
