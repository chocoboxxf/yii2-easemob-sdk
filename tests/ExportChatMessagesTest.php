<?php
/**
 * PHP File
 * User: chocoboxxf
 * Date: 16/2/17
 * Time: 下午5:12
 */
namespace chocoboxxf\Easemob\Tests;

use Yii;

class ExportChatMessagesTest extends \PHPUnit_Framework_TestCase
{
    public function testExportAll()
    {
        // 请在phpunit.xml.dist中设置环信账号
        $easemob = Yii::createObject([
            'class' => 'chocoboxxf\Easemob\Easemob',
            'orgName' => isset($_ENV['ORG_NAME']) ? $_ENV['ORG_NAME'] : '',
            'appName' => isset($_ENV['APP_NAME']) ? $_ENV['APP_NAME'] : '',
            'clientId' => isset($_ENV['CLIENT_ID']) ? $_ENV['CLIENT_ID'] : '',
            'clientSecret' => isset($_ENV['CLIENT_SECRET']) ? $_ENV['CLIENT_SECRET'] : '',
        ]);
        $ret = $easemob->exportChatMessages();
        var_dump($ret);
    }

}