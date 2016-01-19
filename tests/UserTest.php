<?php
/**
 * 环信SDK用户操作测试
 * User: chocoboxxf
 * Date: 15/12/5
 * Time: 下午10:55
 */
namespace chocoboxxf\Easemob\Tests;

use Yii;

class UserTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateUser()
    {
        // 请在phpunit.xml.dist中设置环信账号
        $easemob = Yii::createObject([
            'class' => 'chocoboxxf\Easemob\Easemob',
            'orgName' => isset($_ENV['ORG_NAME']) ? $_ENV['ORG_NAME'] : '',
            'appName' => isset($_ENV['APP_NAME']) ? $_ENV['APP_NAME'] : '',
            'clientId' => isset($_ENV['CLIENT_ID']) ? $_ENV['CLIENT_ID'] : '',
            'clientSecret' => isset($_ENV['CLIENT_SECRET']) ? $_ENV['CLIENT_SECRET'] : '',
        ]);
        $ret = $easemob->createUser('username1', 'password1', 'nickname1');
        var_dump($ret);
        $this->assertArrayHasKey('uuid', $ret);
        $this->assertArrayHasKey('type', $ret);
        $this->assertArrayHasKey('created', $ret);
        $this->assertArrayHasKey('modified', $ret);
        $this->assertArrayHasKey('username', $ret);
        $this->assertArrayHasKey('activated', $ret);
    }

    public function testGetUser()
    {
        // 请在phpunit.xml.dist中设置环信账号
        $easemob = Yii::createObject([
            'class' => 'chocoboxxf\Easemob\Easemob',
            'orgName' => isset($_ENV['ORG_NAME']) ? $_ENV['ORG_NAME'] : '',
            'appName' => isset($_ENV['APP_NAME']) ? $_ENV['APP_NAME'] : '',
            'clientId' => isset($_ENV['CLIENT_ID']) ? $_ENV['CLIENT_ID'] : '',
            'clientSecret' => isset($_ENV['CLIENT_SECRET']) ? $_ENV['CLIENT_SECRET'] : '',
        ]);
        $ret = $easemob->getUser('username1');
        $this->assertArrayHasKey('uuid', $ret);
        $this->assertArrayHasKey('type', $ret);
        $this->assertArrayHasKey('created', $ret);
        $this->assertArrayHasKey('modified', $ret);
        $this->assertArrayHasKey('username', $ret);
        $this->assertArrayHasKey('activated', $ret);
    }

    public function testGetUserToken()
    {
        // 请在phpunit.xml.dist中设置环信账号
        $easemob = Yii::createObject([
            'class' => 'chocoboxxf\Easemob\Easemob',
            'orgName' => isset($_ENV['ORG_NAME']) ? $_ENV['ORG_NAME'] : '',
            'appName' => isset($_ENV['APP_NAME']) ? $_ENV['APP_NAME'] : '',
            'clientId' => isset($_ENV['CLIENT_ID']) ? $_ENV['CLIENT_ID'] : '',
            'clientSecret' => isset($_ENV['CLIENT_SECRET']) ? $_ENV['CLIENT_SECRET'] : '',
        ]);
        $ret = $easemob->getUserToken('username1', 'password1');
        $this->assertArrayHasKey('access_token', $ret);
        $this->assertArrayHasKey('expires_in', $ret);
        $this->assertArrayHasKey('user', $ret);
    }

}
