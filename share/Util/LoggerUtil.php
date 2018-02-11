<?php
/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2018/02/09
 * Time: 18:47
 */

namespace Util;

class LoggerUtil
{
    /**
     * エラーメッセージの出力
     * @param \Exception $e
     */
    public function setErrorMessage(\Exception $e)
    {
        global $log;
        $log->addError($e->getMessage());
    }

    /**
     * データベースに関するログ
     */
    public function setDatabaseLog()
    {
        global $log;
        $log->addWarning('データベースエラー');
    }

    /**
     * ツイッターOAuthに関するログ
     */
    public function setTwitterLog()
    {
        global $log;
        $log->addWarning('ツイッターOAuthエラー');
    }

    /**
     * Imagickに関するログ
     */
    public function setImagickLog()
    {
        global $log;
        $log->addWarning('Imagickエラー');
    }
}