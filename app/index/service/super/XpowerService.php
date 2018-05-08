<?php
/**
 * Created by PhpStorm.
 * User: yc
 * Date: 2018/5/6
 * Time: 13:08
 */

namespace app\index\service\super;

class XpowerService implements SuperInterface
{
    /**
     * 实现超人方法
     */
    public function activate(array $target)
    {
        return '我是超人的不死属性！';
    }
}