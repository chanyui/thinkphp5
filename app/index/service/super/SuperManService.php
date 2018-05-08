<?php
/**
 * Created by PhpStorm.
 * User: yc
 * Date: 2018/5/6
 * Time: 13:08
 */

namespace app\index\service\super;

class SuperManService
{
    private static $module;

    /**
     * 初始化该类的时候，提供的实例必须是一个 SuperInterface 的实现，否则报错
     * SuperManService constructor.
     * @param SuperInterface $module
     */
    public function __construct(SuperInterface $module)
    {
        self::$module = $module;
    }

    /**
     * 测试是否成功
     */
    public static function test()
    {
        return self::$module->activate([]);
    }
}