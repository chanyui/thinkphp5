<?php
/**
 * Created by PhpStorm.
 * User: yc
 * Date: 2018/5/6
 * Time: 12:58
 */

namespace app\index\logic\container;

use app\index\service\super\SuperManService;
use app\index\service\super\XpowerService;

class IocContainerLogic
{
    /**
     * IoC容器注册实例
     * @param string $regName 要注册的名称
     * @param object $container 初始化容器的对象
     */
    public static function bindInstance($regName, $container)
    {
        /*$container->bind($regName, function ($container, $moduleName) {
            return new SuperManService($container->make($moduleName));
        });*/

        // 向该 超级工厂添加超人的生产脚本
        $container->bind('SuperManService', function ($container, $moduleName) {
            return new SuperManService($container->make($moduleName));
        });

        // 向该 超级工厂添加超能力模组的生产脚本
        $container->bind('XpowerService', function ($container) {
            return new XpowerService();
        });

        // ****************** 华丽丽的分割线 **********************
        // 开始启动生产
        /*$superman_1 = $container->make('superman', 'xpower');
        $superman_2 = $container->make('superman', 'ultrabomb');
        $superman_3 = $container->make('superman', 'xpower');*/
        // 随意添加
    }
}