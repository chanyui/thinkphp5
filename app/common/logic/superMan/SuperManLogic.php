<?php
/**
 * Created by PhpStorm.
 * User: yc
 * Date: 2018/5/8
 * Time: 17:11
 */

namespace app\common\logic\superMan;

use app\common\service\superMan\SuperManService;
use app\common\service\superMan\UltraBombService;
use app\common\service\superMan\XpowerService;

class SuperManLogic
{
    public static function bindInstance($serverName, $container)
    {
        // 向该 超级工厂添加超人的生产脚本
        $container->bind('SuperManService', function ($container, $modulName) {
            return new SuperManService($container->make($modulName));
        });

        // 向该 超级工厂添加超能力模组的生产脚本
        $container->bind('XpowerService', function ($container) {
            return new XpowerService();
        });

        // 同上
        $container->bind('UltraBombService', function ($container) {
            return new UltraBombService();
        });

        // ****************** 华丽丽的分割线 **********************
        // 开始启动生产
        /*$superman_1 = $container->make('SuperManService', 'XpowerService');
        $superman_2 = $container->make('SuperManService', 'UltraBombService');*/
        // ...随意添加
    }
}