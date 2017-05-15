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
        if (request()->isPost()) {
            dump(session('numArr'));
            $data = input('post.');
            dump($data);
            return $this->fetch();
        } else {
            return $this->fetch();
        }
    }

    public function getNum()
    {
        $tmp = array();
        while (count($tmp) < 4) {
            $tmp[] = mt_rand(0, 9);
            $tmp = array_unique($tmp);
        }
        $randNum = $tmp;
        session('numArr',$randNum);
        $this->redirect('game/index');
    }




}