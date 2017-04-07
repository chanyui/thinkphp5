<?php
namespace app\index\controller;

use think\Controller;

class Ajax extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function receive()
    {
        $data['name'] = input('name');
        $data['age'] = input('age');
        var_dump($data);
        return json_encode($data);
    }

    public function ajax()
    {
        $data = input('post.');
        var_dump($data);
        return $data;
    }



}