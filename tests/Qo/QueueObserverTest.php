<?php

use Qo\QueueObserver;

class QueueObserverTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * @test
     * @expectedException          \Qo\Exception\QoException
     * @expectedExceptionMessage   Undefined variable: v
     * @group qo-init-error-handler
     * @group qo
     **/
    public function 基本的なエラーハンドラが正常に設定されたかを確認 ()
    {
        QueueObserver::init();

        // 例外処理
        strtolower($v);
    }
}
