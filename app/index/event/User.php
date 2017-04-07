<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 2017/1/18
 * Time: 17:12
 */
namespace app\index\event;

class User
{
    public function userExist($uname)
    {
        $dbres = db('user')->where('username',$uname)->find();
        if ($dbres) {
            return true;
        } else {
            return false;
        }
    }
}