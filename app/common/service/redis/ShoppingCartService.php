<?php
/**
 * Created by PhpStorm.
 * User: Lance Li
 * Date: 2018/5/8
 * Time: 下午7:30
 */

namespace app\common\service\redis;

use app\common\Service\RedisInterfaceService;
use app\common\service\RedisService;

class ShoppingCartService implements RedisInterfaceService
{
    private static $hash = 'ShoppingCart';

    /**
     * 实现新增接口
     * @param string $key
     * @param string $data
     * @param string $type
     * return bool
     */
    public static function addRedis($data = '', $score = 0, $key = '', $type = '')
    {
        $key = self::setKeyRedis($type, $key);
        return RedisService::getInstance()->zAdd($key, $score, $data);
    }

    /**
     * 实现获取接口
     * @param string $key
     * @param string $type
     * return string
     */
    public static function getRedis($startScore = 0, $endScore = 0, $key = '', $type = '')
    {
        $key = self::setKeyRedis($type, $key);
        return RedisService::getInstance()->zrangebyscore($key, $startScore, $endScore);
    }

    /**
     * 实现获取接口
     * @param string $key
     * @param string $type
     * return string
     */
    public static function getRedisList($startScore = 0, $endScore = 0, $key = '', $type = '')
    {
        $key = self::setKeyRedis($type, $key);
        return RedisService::getInstance()->zrange($key, $startScore, $endScore);
    }

    /**
     * 实现统计接口
     */
    public static function countRedis($key = '', $type = '')
    {
        $key = self::setKeyRedis($type, $key);
        return RedisService::getInstance()->zCard($key);
    }

    /**
     * 实现删除接口
     */
    public static function delRedis($key = '', $data = '', $type = '')
    {
        $key = self::setKeyRedis($type, $key);
        return RedisService::getInstance()->zRem($key, $data);
    }


    /**
     * 实现批量删除接口
     */
    public static function delRedisAll($key = '', $startTime = 0, $endTime = 0, $type = '')
    {
        $key = self::setKeyRedis($type, $key);
        return RedisService::getInstance()->zRemRangeByScore($key, $startTime, $endTime);
    }

    /**
     * 实现key接口
     * @param $type
     * @param $key
     * @return string
     */
    public static function setKeyRedis($type = '', $key = '')
    {
        $key = $type . ':' . config('REDIS_PREFIX') . self::$hash . ':' . $key;
        return $key;
    }
}