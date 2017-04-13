<?php
namespace app\index\controller;

use think\Controller;

class Index extends Controller
{
    protected $db;

    public function _initialize()
    {
        $this->db = model('User');
    }

    public function index()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $data['username'] = trim($data['username']);
            $data['password'] = trim($data['password']);
            $remember = input('post.remember') ? intval(input('post.remember')) : 0;
            $result = $this->validate($data, 'User');
            if ($result !== true) {
                $this->error($result);
            } else {
                $dbres = $this->db->where('username', $data['username'])->find()->toArray();
                if (check_password($data['password'], $dbres['salt'], $dbres['password'])) {
                    if ($remember == 1) {
                        $userData = $data['username'] . '\n' . $data['password'];
                        $encode = authcode($userData, 'ENCODE');
                        setcookie('user', $encode, time() + 24 * 3600);
                    }
                    unset($dbres['salt']);
                    unset($dbres['password']);
                    session('uid', $dbres);

                    $this->success('登录成功', 'index/index/home');
                } else {
                    $this->error('登录失败');
                }
            }
        } else {
            $userCookie = cookie('user');
            $deUserCookie = authcode($userCookie, 'DECODE');
            list($username, $password) = explode('\n', $deUserCookie);
            $this->assign('username', $username);
            $this->assign('password', $password);
            return $this->fetch();
        }
    }

    public function register()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $data['username'] = trim($data['username']);
            $data['password'] = trim($data['password']);
            $result = $this->validate($data, 'User');
            if ($result !== true) {
                $this->error($result);
            } else {
                $user = controller('User', 'event')->userExist($data['username']);
                if ($user) {
                    $this->error('用户已存在，请重新注册');
                } else {
                    $data['salt'] = random(6);
                    $data['password'] = get_password($data['password'], $data['salt']);
                    $dbres = $this->db->save($data);
                    if ($dbres) {
                        $this->success('注册成功', 'index/index');
                    } else {
                        $this->error('注册失败');
                    }
                }
            }
        } else {
            return $this->fetch();
        }
    }

    public function home()
    {
        return $this->display('欢迎登陆');
    }
}