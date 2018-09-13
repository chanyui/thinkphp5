<?php
/**
 * Created by PhpStorm.
 * User: Lance Li
 * Date: 2018/3/22
 * Time: 上午11:14
 */

namespace app\common\service\redis;

use app\common\Service\RedisInterfaceService;
use app\common\service\RedisService;

/**
 * 访客数
 */
class VisitsPageService implements RedisInterfaceService
{
    private static $type = 'storeVisits';

    /**
     * 实现新增接口
     * @param int $store_id
     * return int
     */
    public static function addRedis($store_id = 0)
    {
        if (!$store_id) return 0;
        $time = time();
        //没有超过24小时不更新
        if ($oldTime = self::getScoreRedis($store_id)) {
            if (((int)$oldTime + 86400) > $time) return 0;
        }
        $key = self::setKeyRedis(self::$type, $store_id);
        return RedisService::getInstance()->zAdd($key, $time, request()->ip());
    }

    /**
     * 实现获取接口
     * @param int $store_id
     * @param int $startTime
     * @param int $endTime
     * return array
     */
    public static function getRedis($store_id = 0, $startTime = 0, $endTime = 0)
    {
        if (!$store_id) return 0;
        $key = self::setKeyRedis(self::$type, $store_id);
        return RedisService::getInstance()->zRangeByScore($key, $startTime, $endTime);
    }

    /**
     * 实现统计接口
     * @param int $store_id
     * @param int $startTime
     * @param int $endTime
     * return int
     */
    public static function countRedis($store_id = 0, $startTime = 0, $endTime = 0)
    {
        if (!$store_id) return false;
        $key = self::setKeyRedis(self::$type, $store_id);
        return RedisService::getInstance()->zCount($key, $startTime, $endTime);
    }

    /**
     * 实现删除接口
     * @param int $store_id
     * @param int $startTime
     * @param int $endTime
     * return int
     */
    public static function delRedis($store_id = 0, $startTime = 0, $endTime = 0)
    {
        if (!$store_id) return 0;
        $key = self::setKeyRedis(self::$type, $store_id);
        return RedisService::getInstance()->zRemRangeByScore($key, $startTime, $endTime);
    }

    /**
     * 实现key接口
     * @param string $type
     * @param int $store_id
     * return string
     */
    public static function setKeyRedis($type = '', $store_id = 0)
    {
        return md5($type) . ':' . $store_id;
    }

    /**
     * 获取指定member的score
     * @param int $store_id
     * return bool|float
     */
    public static function getScoreRedis($store_id)
    {
        if (!$store_id) return false;
        $key = self::setKeyRedis(self::$type, $store_id);
        return RedisService::getInstance()->zScore($key, request()->ip());
    }

}