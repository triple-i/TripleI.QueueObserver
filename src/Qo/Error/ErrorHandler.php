<?php

/**
 * エラーハンドリングに関わるクラス
 *
 * @package    Qo
 * @subpackage Error
 **/
namespace Qo\Error;

use Qo\Error\Exception\ErrorException;
use Qo\Error\Exception\QoException;

class ErrorHandler
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
     * エラーハンドラを初期化する
     *
     * @return void
     **/
    public static function init ()
    {
        // NoticeやDeprecated含め全てのエラーを捕捉する
        error_reporting(E_ALL);

        // 基本的なエラーハンドラを初期化する
        self::initErrorHandler();

        // 致命的なエラーを捕捉する
        self::initShutDownErrorHandler();
    }


    /**
     * 基本的なエラーを捕捉するハンドラを初期化
     *
     * @return void
     **/
    private static function initErrorHandler ()
    {
        set_error_handler(function ($severity, $msg, $file, $line) {
            $e = new ErrorException($msg, $severity, 0, $file, $line);
            self::salvageError($e);
        });
    }


    /**
     * 致命的なエラーを捕捉するハンドラを初期化
     *
     * @return void
     **/
    private static function initShutDownErrorHandler ()
    {
        register_shutdown_function(function () {
            $e = error_get_last();
            $error_to_catch = [
                E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR
            ];

            // 致命的なエラーに対する処理
            if (in_array($e['type'], $error_to_catch)) {
                $e = new ErrorException($e['message'], $e['type'], 0, $e['file'], $e['line']);
                self::salvageError($e);
            }
        });
    }


    /**
     * todo
     * エラー内容を解析して適切に処理する
     *
     * @param  ErrorException $e
     * @return void
     **/
    private static function salvageError (ErrorException $e)
    {
        // ユニットテスト時はQoExceptionを投げてPHPUnitに通知する
        if (TEST === true) throw new QoException($e->getMessage());
    }
}

