<?php

/**
 * エラーハンドラから呼び出されるExceptionクラス
 * エラー内容をメール送信して管理者に通知する
 *
 * @package    Qo
 * @subpackage Error
 **/
namespace Qo\Error\Exception;

class ErrorException extends \ErrorException
{

    /**
     * @param  string $msg
     * @param  int $severity  エラーの深刻度
     * @param  int $code  エラーコード
     * @param  string $file
     * @param  int $line
     * @return void
     **/
    public function __construct ($msg, $severity, $code, $file, $line)
    {
        parent::__construct($msg, $severity, $code, $file, $line);
    }
}

