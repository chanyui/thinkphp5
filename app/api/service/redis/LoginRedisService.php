<?php

namespace app\api\service\redis;

use app\api\service\RedisInterfaceService;
use app\api\service\RedisService;

class LoginRedisService implements RedisInterfaceService
{
    private static $hash = 'LoginSave';

    /**
     * 实现新增接口
     * @param string $key
     * @param string $data
     * @param string $type
     * @param int $expire
     * return bool
     */
    public static function addRedis($key = '', $data = '', $type = '', $expire = 86400)
    {
        if (!$key) return false;
        $key = self::setKeyRedis($type, $key);
        return RedisService::getInstance()->setex($key, $expire, $data);
    }

    /**
     * 实现获取接口
     * @param string $key
     * @param string $type
     * return string
     */
    public static function getRedis($key = '', $type = '')
    {
        if (!$key) return 0;
        $key = self::setKeyRedis($type, $key);
        return RedisService::getInstance()->get($key);
    }

    /**
     * 实现统计接口
     */
    public static function countRedis()
    {
    }

    /**
     * 实现删除接口
     * @param string $key
     * @param string $type
     * return bool
     */
    public static function delRedis($key = '', $type = '')
    {
        $key = self::setKeyRedis($type, $key);
        return RedisService::getInstance()->del($key);
    }

    /**
     * 实现key接口
     * @param $type
     * @param $key
     * @param bool $ipCheck ,是否检查ip
     * @return string
     */
    public static function setKeyRedis($type = '', $key = '')
    {
        $key = $type . ':' . self::$hash . ':' . $key;
        return $key;
    }

    /**
     * 验证是否存在对应的key
     * @param $key
     * @return int
     */
    public static function checkPaymentIsExist($key, $type = '')
    {
        $key = self::setKeyRedis($type, $key);
        return RedisService::getInstance()->exists($key);
    }

}