<?php

use Qo\Error\ErrorHandler;

class ErrorHandlerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     * @expectedException          \Qo\Error\Exception\QoException
     * @expectedExceptionMessage   Undefined variable: v
     * @group error-handler-init
     * @group error-handler
     **/
    public function 基本的なエラーハンドラが正常に設定されたかを確認 ()
    {
        ErrorHandler::init();

        // 例外処理
        strtolower($v);
    }
}
