<?php
/**
 * Created by PhpStorm.
 * User: yc
 * Date: 2018/5/8
 * Time: 16:44
 */

namespace app\common\service\iocContainer;


class IocContainerService
{
    private static $binds;

    private static $instances;

    /**
     * 来自 laravel 的IoC容器解释(工厂模式的升华)
     * http://laravelacademy.org/post/769.html
     * 工厂注册函数
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