# yii2-easemob-sdk
基于Yii2实现的环信API SDK（目前开发中）

环境条件
--------
- >= PHP 5.4
- >= Yii 2.0
- >= GuzzleHttp 5.0

安装
----

添加下列代码在``composer.json``文件中并执行``composer update --no-dev``操作

```json
{
    "require": {
       "chocoboxxf/yii2-easemob-sdk": "dev-master"
    }
}
```

设置方法
--------

```php
// 全局使用
// 在config/main.php配置文件中定义component配置信息
'components' => [
  .....
  'easemob' => [
    'class' => 'chocoboxxf\Easemob\Easemob',
    'orgName' => '企业ID',
    'appName' => '应用名称',
    'clientId' => 'Client Id',
    'clientSecret' => 'Client Secret',
  ]
  ....
]
// 代码中调用
$result = Yii::$app->easemob->getUser('username');
....
```

```php
// 局部调用
$easemob = Yii::createObject([
    'class' => 'chocoboxxf\Easemob\Easemob',
    'orgName' => '企业ID',
    'appName' => '应用名称',
    'clientId' => 'Client Id',
    'clientSecret' => 'Client Secret',
]);
$result = $easemob->getUser('username');
....
```

使用示例
--------

创建单个用户

```php
$userInfo = Yii::$app->easemob->createUser('username', 'password', 'nickname');
```

获取单个用户

```php
$userInfo = Yii::$app->easemob->getUser('username');
```

获取用户Access Token

```php
$tokenInfo = Yii::$app->easemob->getUserToken('username', 'password');
```

添加好友

```php
$friendInfo = Yii::$app->easemob->addFriend('username1', 'username2');
```