<?php
/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2018/02/16
 * Time: 23:33
 */

use Database\Repository\UserRepository;
use Exception\DatabaseFalseException;
use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
use Util\ImageUtil;

class UserRepositoryTest extends TestCase
{
    use TestCaseTrait;

    public function getConnection()
    {
        global $sqlData;
        $pdo = new PDO("mysql:dbname={$sqlData['testing']['name']};host={$sqlData['testing']['host']};charset=utf8",
            $sqlData['testing']['user'], $sqlData['testing']['pass']);
        return $this->createDefaultDBConnection($pdo);
    }

    public function getDataSet()
    {
        return $this->createXMLDataSet(dirname(__FILE__) . '/../_files/UserTableData.xml');
    }

    public function testCreateUserData()
    {
        /**
         * @var ImageUtil | PHPUnit_Framework_MockObject_MockObject $imageUtilMock
         * @var stdClass $userInfo
         */
        $accessToken = [
            'oauth_token' => 'oauth_token',
            'oauth_token_secret' => 'oauth_token_secret'
        ];
        $userInfo = new UserForUserRepository;
        $imageUtilMock = $this->createMock(ImageUtil::class);
        $imageUtilMock->method('saveTwitterImage')->willReturn('http://localhost/user/3e190b8cf.jpg');
        $userRepository = new UserRepositoryForTest($imageUtilMock);
        $result = $userRepository->createUserData($accessToken, $userInfo);
        $this->assertSame('3', $result);
    }

    public function testGetUserInfo()
    {
        /**
         * 正常にUserのデータが取得できた場合のテスト
         */
        $userRepository = new UserRepository;
        $userInfo = $userRepository->getUserInfo('1');
        $this->assertSame('1', $userInfo->id);
        $this->assertSame('435096859685938', $userInfo->twitter_id);
        $this->assertSame('access_token', $userInfo->access_token);
        $this->assertSame('access_token_secret', $userInfo->access_token_secret);
        $this->assertSame('syo', $userInfo->username);
        $this->assertSame('Syo_pr', $userInfo->screen_name);
        $this->assertSame('http://localhost/user/3e188cc86b6e789b8778d8b7c690b8cf.jpg', $userInfo->user_image);
        $this->assertSame('0', $userInfo->notification_flog);
        $this->assertSame('0', $userInfo->delete_flog);
        $this->assertSame('2018-02-13 14:19:34', $userInfo->created);
    }

    public function testGetUserInfoThrowException()
    {
        /**
         * 存在しないUserIdを指定した場合のテスト
         */
        $loggerUtilMock = $this->createMock(\Util\LoggerUtil::class);
        $loggerUtilMock->method('setDatabaseLog');
        $userRepository = new UserRepositoryForTest(null, $loggerUtilMock);
        $this->expectException(DatabaseFalseException::class);
        $userRepository->getUserInfo('3');
    }

    public function testGetUserInfoNotIdThrowException()
    {
        /**
         * 数値ではない値をUserIdに指定した場合のテスト
         */
        $loggerUtilMock = $this->createMock(\Util\LoggerUtil::class);
        $loggerUtilMock->method('setDatabaseLog');
        $userRepository = new UserRepositoryForTest(null, $loggerUtilMock);
        $this->expectException(DatabaseFalseException::class);
        $userRepository->getUserInfo('a');
    }

    public function testGetUserData()
    {
        /**
         * 正常にUserのデータが取得できた場合のテスト
         */
        $loggerUtilMock = $this->createMock(\Util\LoggerUtil::class);
        $loggerUtilMock->method('setDatabaseLog');
        $userRepository = new UserRepositoryForTest(null, $loggerUtilMock);
        $userInfo = $userRepository->getUserData('Syo_pr');
        $this->assertSame('1', $userInfo->id);
        $this->assertSame('435096859685938', $userInfo->twitter_id);
        $this->assertSame('access_token', $userInfo->access_token);
        $this->assertSame('access_token_secret', $userInfo->access_token_secret);
        $this->assertSame('syo', $userInfo->username);
        $this->assertSame('Syo_pr', $userInfo->screen_name);
        $this->assertSame('http://localhost/user/3e188cc86b6e789b8778d8b7c690b8cf.jpg', $userInfo->user_image);
        $this->assertSame('0', $userInfo->notification_flog);
        $this->assertSame('0', $userInfo->delete_flog);
        $this->assertSame('2018-02-13 14:19:34', $userInfo->created);
    }

    public function testGetUserDataThrowException()
    {
        /**
         * DBに存在しないscreenNameを指定した場合のテスト
         */
        $loggerUtilMock = $this->createMock(\Util\LoggerUtil::class);
        $loggerUtilMock->method('setDatabaseLog');
        $userRepository = new UserRepositoryForTest(null, $loggerUtilMock);
        $this->expectException(DatabaseFalseException::class);
        $userRepository->getUserData('Syo');
    }
}

class UserRepositoryForTest extends UserRepository
{
    private $imageUtilMock;
    private $loggerUtilMock;

    public function __construct(ImageUtil $imageUtilMock = null, \Util\LoggerUtil $loggerUtilMock = null)
    {
        $this->imageUtilMock = $imageUtilMock;
        $this->loggerUtilMock = $loggerUtilMock;
    }

    public function getImageUtil()
    {
        return $this->imageUtilMock;
    }

    public function getLoggerUtil()
    {
        return $this->loggerUtilMock;
    }
}

class UserForUserRepository
{
    public $id = 43509685675646435;
    public $name = 'User1';
    public $screen_name = 'User_Name';
    public $profile_image_url_https = 'http://localhost/user/3e188cc86b6e789b8778d8b7c690b8cf.jpg';
}