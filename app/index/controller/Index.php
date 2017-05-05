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

    /**
     * 登录
     * +------------------------------------------------------------------
     * @functionName : index
     * +------------------------------------------------------------------
     * @author yucheng
     * +------------------------------------------------------------------
     * @return mixed
     */
    public function index()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $data['username'] = trim($data['username']);
            $data['password'] = trim($data['password']);
            $remember = input('post.remember') ? intval(input('post.remember')) : 0;

            //是否要解密cookie密码
            if (input('post.issecret')) {
                $data['password'] = authcode($data['password'], 'DECODE');
            }

            $result = $this->validate($data, 'User');
            if ($result !== true) {
                $this->error($result);
            } else {
                $dbres = $this->db->where('username', $data['username'])->find()->toArray();
                if (check_password($data['password'], $dbres['salt'], $dbres['password'])) {
                    if ($remember == 1) {
                        $userData = $data['username'] . '\n' . $data['password'];
                        $encode = authcode($userData, 'ENCODE');
                        cookie('user', $encode, array('expire' => time() + 24 * 3600));
                    } else {
                        cookie('user', null, array('expire' => time() - 24 * 3600));
                    }
                    unset($dbres['salt']);
                    unset($dbres['password']);
                    session('uid', $dbres);

                    $this->success('登录成功', 'index/home/index');
                } else {
                    $this->error('登录密码错误');
                }
            }
        } else {
            $userCookie = cookie('user');
            $deUserCookie = authcode($userCookie, 'DECODE');
            if ($deUserCookie) {
                list($username, $password) = explode('\n', $deUserCookie);
                $leng = strlen($password);
                $password && $issecret = true;
                $password = $issecret ? authcode($password, 'ENCODE') : $password;
                $viewPwd = substr($password, 0, $leng);
            } else {
                $username = $password = $viewPwd = '';
                $issecret = 0;
            }
            $this->assign('username', $username);
            $this->assign('password', $password);
            $this->assign('viewPwd', $viewPwd);
            $this->assign('issecret', $issecret);
            return $this->fetch();
        }
    }

    /**
     * 注册
     * +------------------------------------------------------------------
     * @functionName : register
     * +------------------------------------------------------------------
     * @author yucheng
     * +------------------------------------------------------------------
     * @return mixed
     */
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

}