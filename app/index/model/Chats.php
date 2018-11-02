<?php

namespace app\index\model;

use think\Model;

class Chats extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'mall_chats';

    // 连接的数据库
    protected $connection = 'db_config1';

    /**
     * 获取聊天历史消息记录
     * @param $uid   int  用户ID
     * @param $to_uid int 接收用户ID
     * @return array
     */
    public function loadHistory($uid, $to_uid)
    {
        //$where = '(uid = ' . $uid . ' and touid = ' . $to_uid . ') or (uid=' . $to_uid . ' and touid=' . $uid . ')';
        $list = $this
            ->where(['uid' => $uid, 'touid' => $to_uid])
            ->whereOr(function ($query) use ($uid, $to_uid) {
                $query->where(['uid' => $to_uid, 'touid' => $uid]);
            })
            ->limit(50)
            ->order('createTime', 'desc')
            ->select();
        $return = [];
        if ($list) {
            foreach ($list as $key => $value) {
                $return[$key] = $value->toArray();
            }
            unset($list);
            /**
             * 根据指定字段重新排序数组
             * @param array $arr 要排序的数组
             * @param $field string $field 需要排序的数组的下标
             * @param int $sort SORT_ASC - 默认，按升序排列 | SORT_DESC - 按降序排列
             * @param int $type SORT_REGULAR-默认。将每一项按常规顺序排列 | SORT_NUMERIC-将每一项按数字顺序排列 | SORT_STRING-将每一项按字母顺序排列
             * @return array
             */
            $return = _getTwoDimensionalArrSort($return, 'createTime');
        }
        return $return;
    }

    /**
     * 新增聊天记录
     * @param $uid int 发起方uid
     * @param $to_uid int 接收方uid
     * @param string $content 内容
     * @return bool
     */
    public function addChatMsg($uid, $to_uid, $content)
    {
        return $this->data(['uid' => $uid, 'touid' => $to_uid, 'content' => $content, 'createTime' => time()])->save();
    }

}
