<?php
/**
 * 环信SDK好友操作测试
 * User: chocoboxxf
 * Date: 16/1/19
 * Time: 下午4:38
 */
namespace chocoboxxf\Easemob\Tests;

use Yii;

class FriendTest extends \PHPUnit_Framework_TestCase
{
    public function testAddFriend()
    {
        // 请在phpunit.xml.dist中设置环信账号
        $easemob = Yii::createObject([
            'class' => 'chocoboxxf\Easemob\Easemob',
            'orgName' => isset($_ENV['ORG_NAME']) ? $_ENV['ORG_NAME'] : '',
            'appName' => isset($_ENV['APP_NAME']) ? $_ENV['APP_NAME'] : '',
            'clientId' => isset($_ENV['CLIENT_ID']) ? $_ENV['CLIENT_ID'] : '',
            'clientSecret' => isset($_ENV['CLIENT_SECRET']) ? $_ENV['CLIENT_SECRET'] : '',
        ]);
        $ret = $easemob->addFriend('username1', 'username2');
        $this->assertArrayHasKey('uuid', $ret);
        $this->assertArrayHasKey('type', $ret);
        $this->assertArrayHasKey('created', $ret);
        $this->assertArrayHasKey('modified', $ret);
        $this->assertArrayHasKey('username', $ret);
        $this->assertArrayHasKey('activated', $ret);
    }

}
