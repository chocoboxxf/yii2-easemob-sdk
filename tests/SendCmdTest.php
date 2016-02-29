<?php
/**
 * 发送透传消息测试
 * User: chocoboxxf
 * Date: 16/2/29
 * Time: 上午11:17
 */
namespace chocoboxxf\Easemob\Tests;

use Yii;
use chocoboxxf\Easemob\Easemob;

class SendCmdTest extends \PHPUnit_Framework_TestCase
{
    public function testSend()
    {
        // 请在phpunit.xml.dist中设置环信账号
        $easemob = Yii::createObject([
            'class' => 'chocoboxxf\Easemob\Easemob',
            'orgName' => isset($_ENV['ORG_NAME']) ? $_ENV['ORG_NAME'] : '',
            'appName' => isset($_ENV['APP_NAME']) ? $_ENV['APP_NAME'] : '',
            'clientId' => isset($_ENV['CLIENT_ID']) ? $_ENV['CLIENT_ID'] : '',
            'clientSecret' => isset($_ENV['CLIENT_SECRET']) ? $_ENV['CLIENT_SECRET'] : '',
        ]);
        $targetType = Easemob::TARGET_TYPE_USERS;
        $target = [
            'username',
        ];
        $action = 'action1';
        $ext = [
            'key1' => 'value1',
            'key2' => 'value2',
        ];
        $ret = $easemob->sendCmd($targetType, $target, $action, $ext);
        var_dump($ret);
    }

}
