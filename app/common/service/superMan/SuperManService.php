<?php
/**
 * Created by PhpStorm.
 * User: yc
 * Date: 2018/5/8
 * Time: 17:07
 */

namespace app\common\service\superMan;


class SuperManService
{
    private static $module;

    /**
     * 当我们初始化 “超人” 类的时候，
     * 提供的模组实例必须是一个 SuperModuleInterface 接口的实现。否则就会提示错误
     * SuperManService constructor.
     * @param SuperModuleInterface $module
     */
    public function __construct(SuperModuleInterface $module)
    {
        self::$module = $module;
    }

    /**
     * IoC容器注册后返回调用的方法
     */
    public function action($parameters)
    {
        return self::$module->activate($parameters);
    }
}