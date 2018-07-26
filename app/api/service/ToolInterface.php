<?php

namespace app\api\service;

interface ToolInterface
{
    /**
     * 定义检查规则
     */
    public static function rule($type, $val);
}