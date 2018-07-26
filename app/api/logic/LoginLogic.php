<?php
/**
 * Created by PhpStorm.
 * User: yc
 * Date: 2018/7/25
 * Time: 17:49
 */

namespace app\api\logic;


use app\api\service\Tool;

class LoginLogic
{
    /**
     * 角色登陆验证
     * @param string $username
     * @param string $password
     * return array
     */
    public static function loginV($username, $password)
    {
        if (!Tool::rule('username', $username)) return [404, '请填写正确的用户名！'];
        if (!Tool::rule('password', $password)) return [404, '请填写正确的用户密码！'];
        return [200, '验证成功！'];
    }
}