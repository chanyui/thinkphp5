<?php
/**
 * Created by PhpStorm.
 * User: Lance Li
 * Date: 2018/5/29
 * Time: 上午9:19
 */

namespace Common\Logic\Redis;

use app\common\Service\RedisInterfaceService;
use app\common\service\RedisService;

class StoreEvaAvgNumService implements RedisInterfaceService
{
    private static $hash = 'StoreEvaAvgNum';

    /**
     * 实现新增接口
     * @param string $key
     * @param string $data
     * @param string $type
     * @param int $expire
     * return int
     */
    public static function addRedis($key = '', $data = '', $type = '', $expire = 300)
    {
        if (!$key) return false;
        $key = self::setKeyRedis($type, $key);
        return RedisService::getInstance()->set($key, $data, $expire);
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
     */
    public static function delRedis()
    {
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
     * 验证
     * @param $key
     * @return int
     */
    public static function checkPaymentIsExist($key, $type = '')
    {
        $key = self::setKeyRedis($type, $key);
        return RedisService::getInstance()->exists($key);
    }
}