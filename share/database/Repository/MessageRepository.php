<?php
/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2018/02/09
 * Time: 8:19
 */

namespace Database\Repository;

use Database\Models\Message;
use DateTime;
use Di\UtilContainer;
use PDOException;

class MessageRepository
{
    use UtilContainer;

    /**
     * メッセージの情報をデータベースに登録
     * @param string $userId
     * @param string $path
     * @param string $message
     * @return string
     */
    public function createMessage(string $userId, string $path, string $message): string
    {
        date_default_timezone_set('Asia/Tokyo');
        $time = new DateTime();
        $hash = md5(uniqid(rand(), 1));
        try {
            Message::create()
                ->set('user_id', $userId)
                ->set('image_path', $path)
                ->set('hash', $hash)
                ->set('message_text', $message)
                ->set('send_flog', 0)
                ->set('created', $time->format('Y-m-d H:i:s'))
                ->set('updated', $time->format('Y-m-d H:i:s'))
                ->save();
            return $hash;
        } catch (PDOException $e) {
            $this->getLoggerUtil()->setDatabaseLog();
            throw $e;
        }
    }

    /**
     * メッセージをハッシュから取得する
     * @param string $hash
     * @return Message
     */
    public function getMessage(string $hash): Message
    {
        try {
            $userInfo = Message::table_alias('c1')
                ->select_many('c1.*')
                ->select_many('c2.screen_name', 'c2.delete_flog')
                ->join('users', 'c1.user_id=c2.id', 'c2')
                ->where('hash', $hash)
                ->findOne();
            return $userInfo;
        } catch (PDOException $e) {
            $this->getLoggerUtil()->setDatabaseLog();
            throw $e;
        }
    }

    /**
     * ユーザに送られてきた質問の一覧
     * @param string $userId
     * @param int $flog
     * @return array
     */
    public function getMessageList(string $userId, int $flog): array
    {
        try {
            $messageList = Message::where('user_id', $userId)
                ->where('send_flog', $flog)
                ->orderByDesc('id')->findMany();
            return $messageList;
        } catch (PDOException $e) {
            $this->getLoggerUtil()->setDatabaseLog();
            throw $e;
        }
    }

    /**
     * ユーザが解答した結果をDBに保存
     * @param string $hash
     * @param string $messageText
     * @return Message
     */
    public function updateSendMessage(string $hash, string $messageText): Message
    {
        try {
            $message = Message::where('hash', $hash)->findOne();
            if($this->getUserSessionUtil()->loginUserExist($message->user_id) && ($message !== false) && ($message->send_flog == 0)) {
                $message->answer_text = $messageText;
                $message->send_flog = 1;
                $message->save();
                return $message;
            } else {

            }
        } catch (PDOException $e) {
            $this->getLoggerUtil()->setDatabaseLog();
            throw $e;
        }
    }
}