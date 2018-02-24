<?php
/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2018/02/03
 * Time: 10:07
 */

namespace Util;

use Di\UtilContainer;
use Exception;
use Imagick;
use ImagickDraw;
use ImagickPixel;

class ImageUtil
{
    use UtilContainer;

    /**
     * Twitterアイコンをローカルに保存
     * @param string $imagePath
     * @return string
     */
    public function saveTwitterImage(string $imagePath): string
    {
        $image = file_get_contents($imagePath);
        $filename = md5(uniqid(rand(), 1)) . '.jpg';
        file_put_contents(__DIR__ . '/../public/user/' . $filename, $image);
        $path = getenv('APP_URL') . '/user/' . $filename;
        return $path;
    }

    /**
     * 送られてきたメッセージを画像に変換
     * @param string $message
     * @return string
     * @throws Exception
     */
    public function makeMessageImage(string $message): string
    {
        try {
            $header = new Imagick(__DIR__ . '/../resources/img/q-head.png');
            $footer = new Imagick(__DIR__ . '/../resources/img/q-footer.png');
            $messageImage = new Imagick();
            $draw = new ImagickDraw();
            $filename = md5(uniqid(rand(), 1)) . '.png';
            $message = $this->getTextUtil()->checkMessageText($message);
            $stringHeight = $this->getMessageImageHeight(substr_count($message, "\n"));

            $messageImage->newImage(550, $stringHeight, new ImagickPixel('white'));
            $messageImage->setImageFormat('png');
            $messageImage->borderImage('#FFB38A', 25, 0);

            $draw->setFont(__DIR__ . "/../resources/font/JKFonts/JKG-L_3.ttf");
            $draw->setFontSize(24);
            $draw->setGravity(Imagick::GRAVITY_CENTER);
            $metrics = $messageImage->queryFontMetrics($draw, $message);
            $draw->annotation(0, $metrics['ascender'], $message);
            $messageImage->drawImage($draw);

            $header->addImage($messageImage);
            $header->setIteratorIndex(0);
            $headMessage = $header->appendImages(true);

            $headMessage->addImage($footer);
            $headMessage->setIteratorIndex(0);
            $mixImage = $headMessage->appendImages(true);
            $mixImage->writeImage(__DIR__ . "/../public/message/$filename");

            $header->destroy();
            $footer->destroy();
            $messageImage->destroy();
            $draw->destroy();
            $headMessage->destroy();
            $mixImage->destroy();

            return $filename;
        } catch (Exception $e) {
            $this->getLoggerUtil()->setImagickLog();
            throw $e;
        }
    }

    /**
     * 改行の行数からWidthを決める
     * @param int $stringNumber
     * @return int
     */
    public function getMessageImageHeight(int $stringNumber): int
    {
        $defaultNumber = 200;
        if ($stringNumber < 6) {
            return $defaultNumber;
        } else {
            return $defaultNumber + (25 * ($stringNumber - 5));
        }
    }
}