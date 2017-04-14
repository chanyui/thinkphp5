<?php
namespace app\index\controller;

use think\Controller;

class Home extends Controller
{
    public function index()
    {
        return $this->display('欢迎登录');
    }
    
}