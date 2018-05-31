<?php
/**
 * Created by PhpStorm.
 * User: Lance Li
 * Date: 2018/4/18
 * Time: 下午4:16
 */

namespace app\common\service\Redis;

use app\common\Service\RedisInterfaceService;
use app\common\service\RedisService;

class GoodsTimingService implements RedisInterfaceService
{
    private static $hash = 'GoodsTiming';

    /**
     * 实现新增接口
     * @param string $key
     * @param string $data
     * @param string $type
     * return bool
     */
    public static function addRedis($data = '', $time = 0, $key = '', $type = '')
    {
        $key = self::setKeyRedis($type, $key);
        return RedisService::getInstance()->zAdd($key, $time, $data);
    }

    /**
     * 实现获取接口
     * @param string $key
     * @param string $type
     * return string
     */
    public static function getRedis($startTime = 0, $endTime = 0, $key = '', $type = '')
    {
        $key = self::setKeyRedis($type, $key);
        return RedisService::getInstance()->zrangebyscore($key, $startTime, $endTime);
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
    public static function delRedis($data = '', $key = '', $type = '')
    {
        $key = self::setKeyRedis($type, $key);
        return RedisService::getInstance()->zDelete($key, $data);
    }

    /**
     * 实现批量删除接口
     */
    public static function delRedisAll($startTime = 0, $endTime = 0, $key = '', $type = '')
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
        $key = $type . ':' . self::$hash . ':' . $key;
        return $key;
    }
}