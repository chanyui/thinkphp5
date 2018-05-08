<?php
/**
 * Created by PhpStorm.
 * User: yc
 * Date: 2018/5/6
 * Time: 11:18
 * Desc: 这种更为高级的工厂，就是工厂模式的升华 —— IoC 容器。
 */

namespace app\index\service\container;

class IocContainerService
{
    private static $binds;

    private static $instances;

    /**
     * 0工厂注册函数
     */
    public function bind($abstract, $concrete)
    {
        if ($concrete instanceof \Closure) {
            self::$binds[$abstract] = $concrete;
        } else {
            self::$instances[$abstract] = $concrete;
        }
    }

    /**
     * 工厂执行函数
     */
    public function make($abstract, $parameters = [])
    {
        if (isset(self::$instances[$abstract])) {
            return self::$instances[$abstract];
        }
        array_unshift($parameters, $this);
        return call_user_func_array(self::$binds[$abstract], $parameters);
    }
}