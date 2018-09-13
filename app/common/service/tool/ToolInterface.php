<?php
/**
 * Created by PhpStorm.
 * User: yc
 * Date: 2018/9/3
 * Time: 14:53
 */

namespace app\common\service\tool;


interface ToolInterface
{
    /**
     * 定义验证工具类接口
     * @param $type
     * @param $value
     * @return mixed
     */
    public static function rule($type,$value);
}