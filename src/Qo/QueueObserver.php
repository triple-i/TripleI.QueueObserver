<?php

/**
 * 初期化を担うクラス
 * 主にエラーハンドリングを行う
 *
 * @package Qo
 **/
namespace Qo;

use Qo\Error\ErrorHandler;

class QueueObserver
{

    /**
     * @return void
     **/
    private function __construct ()
    {
    }


    /**
     * @return void
     **/
    private function __clone ()
    {
    }


    /**
     * 初期化処理
     *
     * @return void
     **/
    public static function init ()
    {
        // エラーハンドラを初期化する
        ErrorHandler::init();
    }
}
