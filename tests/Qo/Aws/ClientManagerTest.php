<?php


use Qo\Aws\ClientManager;

class ClientManagerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     **/
    public function setUp ()
    {
        // 環境変数をクリアする
        putenv('AWS_ACCESS_KEY_ID=');
        putenv('AWS_SECRET_ACCESS_KEY=');
        putenv('AWS_DEFAULT_REGION=');
    }


    /**
     * @test
     * @expectedException           Qo\Error\Exception\QoException
     * @expectedExceptionMessage    環境変数AWS_ACCESS_KEY_IDが指定されていません
     * @group aws-manager-failed-access-key
     * @group aws-manager
     **/
    public function AWS_ACCESS_KEY_IDが設定されていなかった場合 ()
    {
        $config = ClientManager::getConfig();
    }


    /**
     * @test
     * @expectedException           Qo\Error\Exception\QoException
     * @expectedExceptionMessage    環境変数AWS_SECRET_ACCESS_KEYが指定されていません
     * @group aws-manager-failed-secret-key
     * @group aws-manager
     **/
    public function AWS_SECRET_ACCESS_KEYが設定されていなかった場合 ()
    {
        putenv('AWS_ACCESS_KEY_ID=access_key');
        $config = ClientManager::getConfig();
    }


    /**
     * @test
     * @expectedException           Qo\Error\Exception\QoException
     * @expectedExceptionMessage    環境変数AWS_DEFAULT_REGIONが指定されていません
     * @group aws-manager-failed-region
     * @group aws-manager
     **/
    public function AWS_DEFAULT_REGIONが設定されていなかった場合 ()
    {
        putenv('AWS_ACCESS_KEY_ID=access_key');
        putenv('AWS_SECRET_ACCESS_KEY=secret_key');
        $config = ClientManager::getConfig();
    }


    /**
     * @test
     * @group aws-manager-get-config
     * @group aws-manager
     **/
    public function デフォルトの設定を取得する場合 ()
    {
        $this->_initEnv();
        $config = ClientManager::getConfig();

        $this->assertArrayHasKey('key', $config);
        $this->assertEquals('access_key', $config['key']);

        $this->assertArrayHasKey('secret', $config);
        $this->assertEquals('secret_key', $config['secret']);

        $this->assertArrayHasKey('region', $config);
        $this->assertEquals('default_region', $config['region']);
    }


    /**
     * @test
     * @group aws-manager-override-config
     * @group aws-manager
     **/
    public function コンフィグを上書きする場合 ()
    {
        $this->_initEnv();

        $config = ClientManager::getConfig();

        $this->assertArrayHasKey('key', $config);
        $this->assertEquals('access_key', $config['key']);

        $config = ClientManager::getConfig(['key' => 'override_access_key']);
        $this->assertEquals('override_access_key', $config['key']);
    }


    /**
     * @test
     * @group aws-manager-get-sqs
     * @group aws-manager
     **/
    public function SQSクライアントの取得 ()
    {
        $this->_initEnv();
        $client = ClientManager::getSqsClient();

        $this->assertInstanceOf('Aws\Sqs\SqsClient', $client);
    }


    /**
     * 環境変数をセットする
     *
     * @return void
     **/
    private function _initEnv ()
    {
        putenv('AWS_ACCESS_KEY_ID=access_key');
        putenv('AWS_SECRET_ACCESS_KEY=secret_key');
        putenv('AWS_DEFAULT_REGION=default_region');
    }

}

