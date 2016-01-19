<?php
/**
 * 环信SDK授权测试
 * User: chocoboxxf
 * Date: 15/12/5
 * Time: 下午10:55
 */
namespace chocoboxxf\Easemob\Tests;

use Yii;

class AuthTest extends \PHPUnit_Framework_TestCase
{
    public function testGetToken()
    {
        // 请在phpunit.xml.dist中设置环信账号
        $easemob = Yii::createObject([
            'class' => 'chocoboxxf\Easemob\Easemob',
            'orgName' => isset($_ENV['ORG_NAME']) ? $_ENV['ORG_NAME'] : '',
            'appName' => isset($_ENV['APP_NAME']) ? $_ENV['APP_NAME'] : '',
            'clientId' => isset($_ENV['CLIENT_ID']) ? $_ENV['CLIENT_ID'] : '',
            'clientSecret' => isset($_ENV['CLIENT_SECRET']) ? $_ENV['CLIENT_SECRET'] : '',
        ]);
        $ret = $easemob->getToken();
        $this->assertNotEmpty($ret);
    }

}
