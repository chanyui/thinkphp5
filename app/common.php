<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * 生成随机码
 * +------------------------------------------------------------------
 * @functionName : random
 * +------------------------------------------------------------------
 * @param int $length 随机码的长度
 * @param int $numeric 0是字母和数字混合码，不为0是数字码
 * +------------------------------------------------------------------
 * @author yucheng
 * +------------------------------------------------------------------
 * @return string
 */
function random($length, $numeric = 0)
{
    $seed = base_convert(md5(print_r($_SERVER, 1) . microtime()), 16, $numeric ? 10 : 35);
    $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
    $res = '';
    $max = strlen($seed) - 1;
    for ($i = 0; $i < $length; $i++) {
        $res .= $seed[mt_rand(0, $max)];
    }
    return $res;
}

/**
 * 生成密码
 * +------------------------------------------------------------------
 * @functionName : get_password
 * +------------------------------------------------------------------
 * @param string $passowrd 原始密码
 * @param string $salt 密钥
 * +------------------------------------------------------------------
 * @author yucheng
 * +------------------------------------------------------------------
 * @return string
 */
function get_password($passowrd, $salt)
{
    $slt = $passowrd . '{' . $salt . '}';
    $h = 'sha256';
    $digest = hash($h, $slt, true);
    for ($i = 0; $i < 5000; $i++) {
        $digest = hash($h, $digest . $salt, true);
    }
    return base64_encode($digest);
}

/**
 * 验证密码是否正确
 * +------------------------------------------------------------------
 * @functionName : check_password
 * +------------------------------------------------------------------
 * @param string $password 原始密码
 * @param string $salt 密钥
 * @param string $pwd 加密后的密码
 * +------------------------------------------------------------------
 * @author yucheng
 * +------------------------------------------------------------------
 * @return bool
 */
function check_password($password, $salt, $pwd)
{
    $res = get_password($password, $salt);
    if ($res == $pwd) {
        return true;
    } else {
        return false;
    }
}