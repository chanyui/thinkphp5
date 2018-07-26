<?php

/**
 * 获取用户的信息
 * @param int $user_id 用户id
 */
if (!function_exists('get_user')) {
    function get_user($user_id)
    {
        if (!$user_id) {
            return false;
        }
        if (\app\api\service\redis\LoginRedisService::checkPaymentIsExist($user_id)) {
            return \app\api\service\redis\LoginRedisService::getRedis($user_id);
        } else {
            return false;
        }
    }
}