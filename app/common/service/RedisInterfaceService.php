<?php
/**
 * Created by PhpStorm.
 * User: Lance Li
 * Date: 2018/3/22
 * Time: 上午11:07
 */

namespace app\common\Service;

interface RedisInterfaceService
{
    /**
     * 定义新增接口
     */
    public static function addRedis();

    /**
     * 定义获取接口
     */
    public static function getRedis();

    /**
     * 定义统计接口
     */
    public static function countRedis();

    /**
     * 定义删除接口
     */
    public static function delRedis();

    /**
     * 定义key接口
     */
    public static function setKeyRedis();

}