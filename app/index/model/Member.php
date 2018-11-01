<?php

namespace app\index\model;

use think\Model;

class Member extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'mall_member';

    // 连接的数据库
    protected $connection = 'db_config1';

    /**
     * 根据uid获取用户绑定的fd
     * @param $uid
     */
    public function getFdByUid($uid)
    {
        $info = $this->field('member_fd')->where(['userid' => $uid])->find();
        return $info ? $info->toArray()['member_fd'] : 0;
    }

    /**
     * 更新绑定的fd
     * @param $uid  int 用户ID
     * @param $fd   int 客户端fd
     * @return bool
     */
    public function updateBind($uid, $fd)
    {
        if ($this->where(['userid' => $uid])->update(['member_fd' => $fd])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 根据uid查找会员信息
     * @param $uid int
     * @return
     */
    public function findMemberArr($uid)
    {
        $info = $this->field('userid,member_name,member_truename,member_avatar')->where(['userid' => $uid])->find();
        return $info ? $info->toArray() : [];
    }
}
