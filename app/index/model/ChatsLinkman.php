<?php

namespace app\index\model;

use think\Model;

class ChatsLinkman extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'mall_chats_linkman';

    // 连接的数据库
    protected $connection = 'db_config1';

    /**
     * 更新联系人(新增)
     * @param $uid int 发起方uid
     * @param $to_uid  int 接收方uid
     * @return bool
     */
    public function addLinkman($uid, $to_uid)
    {
        return $this->save(['uid' => $uid, 'to_uid' => $to_uid, 'create_time' => time()]);
    }

    /**
     * 查找联系人
     * @param $uid int 发起方uid
     * @param $to_uid  int 接收方uid
     * @return
     */
    public function findLinkman($uid, $to_uid)
    {
        $where = '(uid = ' . $uid . ' and to_uid = ' . $to_uid . ') or (uid=' . $to_uid . ' and to_uid=' . $uid . ')';
        $info = $this->field('uid,to_uid')->where($where)->find();
        return $info ? $info->toArray() : [];
    }

}
