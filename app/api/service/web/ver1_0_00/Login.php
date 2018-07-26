<?php
/**
 * Created by PhpStorm.
 * User: yc
 * Date: 2018/7/25
 * Time: 16:56
 */

namespace app\api\service\web\ver1_0_00;

use app\api\logic\LoginLogic;
use app\api\service\redis\LoginRedisService;

class Login
{
    static private $param; //参数

    public function __construct($param)
    {
        self::$param = $param;
    }

    /**
     * 登录操作
     */
    public function login()
    {
        $username = self::$param['username'];
        $password = self::$param['password'];
        list($code, $msg) = LoginLogic::loginV($username, $password);
        if ($code != 200) return [$code, $msg, null];
        //数据库验证
        $uid = 123123123;
        if ($uid) {
            $user = [
                'uid' => $uid,
                'name' => '测试！'
            ];
            $key = user_encode($uid);
            if (!LoginRedisService::addRedis($key, json_encode($user))) {
                return [404, '登录失败！'];
            } else {
                return [200, '登录成功！', ['token' => $key]];
            }
        } else {
            return [403, '登录失败！'];
        }
    }
}