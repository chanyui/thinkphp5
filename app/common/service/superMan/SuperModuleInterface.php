<?php
/**
 * Created by PhpStorm.
 * User: yc
 * Date: 2018/5/8
 * Time: 16:54
 */

namespace app\common\service\superMan;

/**
 * 定义超能力模组的统一接口，所有超能力都必须实现这一接口
 * Interface SuperModuleInterfaceService
 * @package app\common\service\superMan
 */
interface SuperModuleInterface
{
    /**
     * 超能力激活方法
     * 任何一个超能力都得有该方法，并拥有一个参数
     * @param array $target 针对目标，可以是一个或多个，自己或他人
     */
    public function activate(array $target);
}