<?php
/**
 * 环信SDK
 * User: chocoboxxf
 * Date: 16/1/5
 * Time: 下午4:11
 */
namespace chocoboxxf\Easemob;

use GuzzleHttp\Client;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\web\HttpException;

class Easemob extends Component
{
    /**
     * 通过client secret授权
     */
    const GRANT_TYPE_CREDENTIALS = 'client_credentials';
    /**
     * 通过密码授权
     */
    const GRANT_TYPE_PASSWORD = 'password';
    /**
     * 默认环信API域名
     */
    const EASEMOB_URL = 'https://a1.easemob.com';
    /**
     * 各接口路径
     */
    const PATH_REQUEST_TOKEN = '/token'; // 获取access token
    const PATH_CREATE_USER = '/users'; // 创建用户
    const PATH_GET_USER = '/users/[USER_NAME]'; // 获取单个用户
    const PATH_ADD_FRIEND = '/users/[USER_NAME]/contacts/users/[FRIEND_NAME]'; // 添加好友
    const PATH_EXPORT_CHAT_MESSAGES = '/chatmessages'; // 导出聊天记录
    /**
     * 企业的唯一标识,开发者在环信开发者管理后台注册账号时填写的企业ID
     * @var string
     */
    public $orgName;
    /**
     * 同一”企业”下”app”唯一标识,开发者在环信开发者管理后台创建应用时填写的”应用名称”
     * @var string
     */
    public $appName;
    /**
     * Client Id
     * @var string
     */
    public $clientId;
    /**
     * Client Secret
     * @var string
     */
    public $clientSecret;
    /**
     * 是否每次请求强制获取新的access token
     * @var boolean
     */
    public $forceRefreshToken = false;
    /**
     * API域名
     * @var string
     */
    public $apiDomain;

    /**
     * 数据缓存前缀
     * @var string
     */
    public $cachePrefix = 'cache_yii2_easemob_sdk';
    /**
     * 缓存对象
     * @var \yii\caching\Cache
     */
    protected $cache;
    /**
     * API路径前缀
     * @var string
     */
    protected $apiBaseUrl;
    /**
     * HTTP Client
     * @var \GuzzleHttp\Client
     */
    protected $apiClient;
    /**
     * access token信息
     * @var array
     */
    protected $token; // token

    public function init()
    {
        parent::init();
        if (!isset($this->orgName)) {
            throw new InvalidConfigException('请先配置企业的唯一标识');
        }
        if (!isset($this->appName)) {
            throw new InvalidConfigException('请先配置应用名称');
        }
        if (!isset($this->clientId)) {
            throw new InvalidConfigException('请先配置Client Id');
        }
        if (!isset($this->clientSecret)) {
            throw new InvalidConfigException('请先配置Client Secret');
        }
        $this->apiDomain = isset($this->apiDomain) ? $this->apiDomain : self::EASEMOB_URL;
        $this->apiBaseUrl = '/' . $this->orgName . '/' . $this->appName;
        $this->apiClient = new Client([
            'base_url' => [
                $this->apiDomain,
                [],
            ],
            'defaults' => [
            ]
        ]);
        if (Yii::$app->cache === null) {
            $this->cache = Yii::createObject([
                'class' => 'yii\caching\FileCache',
            ]);
        } else {
            $this->cache = Yii::$app->cache;
        }
    }

    /**
     * 创建单个用户
     * @param string $username 用户名
     * @param string $password 密码
     * @param string $nickname 昵称，可不填
     * @param bool $checkDuplicate 是否检查有重复的用户，重复报错，不检查则直接返回重复的用户信息
     * @return bool|mixed
     */
    public function createUser($username, $password, $nickname = '', $checkDuplicate = true)
    {
        // 先尝试获取用户
        if (!$checkDuplicate) {
            $result = $this->getUser($username);
            if ($result !== false) {
                return $result;
            }
        }
        try {
            $data = [
                'username' => $username,
                'password' => $password,
            ];
            if (trim($nickname) !== '') {
                $data['nickname'] = $nickname;
            }
            $response = $this->apiClient->post(
                $this->apiBaseUrl . self::PATH_CREATE_USER,
                [
                    'headers' => ['Authorization' => $this->getTokenHeader()],
                    'json' => $data
                ]
            );
            $result = $response->json();
            return isset($result['entities']) ? reset($result['entities']) : false;
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * 获取单个用户
     * @param string $username 用户名
     * @return bool|mixed
     */
    public function getUser($username)
    {
        try {
            $path = str_replace('[USER_NAME]', $username, self::PATH_GET_USER);
            $response = $this->apiClient->post(
                $this->apiBaseUrl . $path,
                [
                    'headers' => ['Authorization' => $this->getTokenHeader()],
                ]
            );
            $result = $response->json();
            return isset($result['entities']) ? reset($result['entities']) : false;
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * 获取用户的access token
     * @param string $username 用户名
     * @param string $password 密码
     * @return bool|mixed
     */
    public function getUserToken($username, $password)
    {
        try {
            $response = $this->apiClient->post(
                $this->apiBaseUrl . self::PATH_REQUEST_TOKEN,
                [
                    'json' => [
                        'grant_type' => self::GRANT_TYPE_PASSWORD,
                        'username' => $username,
                        'password' => $password,
                    ]
                ]
            );
            $result = $response->json();
            return isset($result['access_token']) ? $result : false;
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * 添加好友
     * @param string $username 用户名
     * @param string $friendName 好友的用户名
     * @return bool|mixed
     */
    public function addFriend($username, $friendName)
    {
        try {
            $path = str_replace('[USER_NAME]', $username, self::PATH_ADD_FRIEND);
            $path = str_replace('[FRIEND_NAME]', $friendName, $path);
            $response = $this->apiClient->post(
                $this->apiBaseUrl . $path,
                [
                    'headers' => ['Authorization' => $this->getTokenHeader()],
                ]
            );
            $result = $response->json();
            return isset($result['entities']) ? reset($result['entities']) : false;
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * 导出聊天记录
     * @param string $lastTimestamp 上次导出的最后一条记录时间戳，不传则从头开始
     * @param int $limit 当前导出分页大小，单次最多1000条记录
     * @param string $cursor 当前导出分页游标（上次导出时返回的，返回空则表示无下一页）
     * @return bool|mixed
     */
    public function exportChatMessages($lastTimestamp = '', $limit = 1000, $cursor = '')
    {
        try {
            $path = self::PATH_EXPORT_CHAT_MESSAGES;
            $data = [];
            if (is_numeric($lastTimestamp)) {
                $data['ql'] = 'select * where timestamp>' . $lastTimestamp;
            }
            if ($limit > 0) {
                $data['limit'] = $limit;
            }
            if ($cursor !== '') {
                $data['cursor'] = $cursor;
            }
            $response = $this->apiClient->get(
                $this->apiBaseUrl . $path,
                [
                    'headers' => ['Authorization' => $this->getTokenHeader()],
                    'query' => $data,
                ]
            );
            $result = $response->json();
            return isset($result['entities']) ? $result : false;
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * 获取access token
     * @return mixed
     * @throws HttpException
     */
    public function getToken()
    {
        $time = time();
        if ($this->token === null || $this->token['expires_at'] < $time || $this->forceRefreshToken) {
            $result = $this->token === null && !$this->forceRefreshToken ? $this->getCache('token') : false;
            if ($result === false) {
                if (!($result = $this->requestToken())) {
                    throw new HttpException(500, 'Fail to get access_token from easemob server.');
                }
                $result['expires_at'] = $time + $result['expires_in'];
                $this->setCache('token', $result, $result['expires_in']);
            }
            $this->setToken($result);
        }
        return $this->token['access_token'];
    }

    /**
     * 手动设置access token
     * @param array $token
     */
    public function setToken(array $token)
    {
        if (!isset($token['access_token'])) {
            throw new InvalidParamException('The easemob access_token must be set.');
        } elseif(!isset($token['expires_at'])) {
            throw new InvalidParamException('easemob access_token expire time must be set.');
        }
        $this->token = $token;
    }

    /**
     * 获取请求header中的授权信息（IM用户）
     * @param string $token Access Token
     * @return string
     */
    protected function getUserTokenHeader($token)
    {
        return 'Bearer '.$token;
    }

    /**
     * 获取请求header中的授权信息
     * @return string
     * @throws HttpException
     */
    protected function getTokenHeader()
    {
        return 'Bearer '.$this->getToken();
    }

    /**
     * 请求服务器token
     * @param string $grantType 授权类型
     * @return array|bool
     */
    protected function requestToken($grantType = self::GRANT_TYPE_CREDENTIALS)
    {
        try {
            $response = $this->apiClient->post(
                $this->apiBaseUrl . self::PATH_REQUEST_TOKEN,
                [
                    'json' => [
                        'grant_type' => $grantType,
                        'client_id' => $this->clientId,
                        'client_secret' => $this->clientSecret,
                    ]
                ]
            );
            $result = $response->json();
            return isset($result['access_token']) ? $result : false;
        } catch (\Exception $ex) {
            return false;
        }
    }


    /**
     * 获取缓存键值
     * @param string $name 缓存Key
     * @return string
     */
    protected function getCacheKey($name)
    {
        return sprintf('%s_%s_%s_%s',
            $this->cachePrefix,
            $this->orgName,
            $this->appName,
            $name
        );
    }
    /**
     * 缓存数据
     * @param string $name 缓存Key
     * @param mixed $value 缓存Value
     * @param int $duration 缓存有效时间
     * @return bool
     */
    protected function setCache($name, $value, $duration)
    {
        return $this->cache->set($this->getCacheKey($name), $value, $duration);
    }

    /**
     * 获取缓存数据
     * @param $name 缓存Key
     * @return mixed
     */
    protected function getCache($name)
    {
        return $this->cache->get($this->getCacheKey($name));
    }

}