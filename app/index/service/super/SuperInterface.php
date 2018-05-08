<?php
/**
 * Created by PhpStorm.
 * User: yc
 * Date: 2018/5/6
 * Time: 13:09
 */

namespace app\index\service\super;

interface SuperInterface
{
    /**
     * 定义超人激活方法的接口
     * 超能力激活方法
     * 任何一个超能力都得有该方法，并拥有一个参数
     * @param array $target 针对目标，可以是一个或多个，自己或他人
     */
    public function activate(array $target);
}