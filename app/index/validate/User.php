<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 2017/1/17
 * Time: 15:17
 */
namespace app\index\validate;

use think\Validate;

class User extends Validate
{
    protected $rule = [
        'username' => 'require|max:20',
        'password' => 'require'
    ];

    protected $message = [
        'username.require' => '用户名必填',
        'username.max'     => '用户名最多不能超过20个字符',
        'password'         => '密码必填'
    ];
}