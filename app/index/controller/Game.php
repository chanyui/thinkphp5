<?php
namespace app\index\controller;

use think\Controller;

class Game extends Controller
{
    protected $db;

    public function _initialize()
    {

    }

    public function index()
    {
        return $this->fetch();
    }

}