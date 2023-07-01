<?php

/**
 * Created by PhpStorm.
 * User: @van
 * Date: 2015/4/23
 * Time: 15:05
 */
class FollowRecord
{
    const TYPE_RECORD  = 1;//跟进纪录
    const TYPE_PLAN    = 2;//跟进计划
    const TYPE_SERVICE = 3;//跟进服务
    const TYPE_REBACK  = 4;//回访

    public function searchNotice() {
        $������            = new CSearch();
        $������->condition = 'uid = ? and pre_time > 0 and pre_time < ? and pre_ok = 0';
        $������->join      = 'left join t_client  as client on client.id = t.cli_id';
        $������->params    = array(User::getCuruid(), time());
        $������->select    = 't.*,client.name cli_name';
        return $this->queryAllSearch($������);
    }

    public function searchReback($��Π�) {
        $������    = new CSearch();
        $���� = 'cli_id = ? and is_reback = 1';
        $��ޒ��    = array($��Π�['cli_id']);
        if ($��Π�['start_time']) {
            $���� .= ' and t.time >= ? ';
            $��ޒ��[] = strtotime($��Π�['start_time']);
        }
        if ($��Π�['end_time']) {
            $���� .= ' and t.time <= ? ';
            $��ޒ��[] = strtotime($��Π�['end_time']) + 86400;
        }
        if ($��Π�['reback_type_id']) {
            $���� .= ' and t.fid = ? ';
            $��ޒ��[] = $��Π�['reback_type_id'];
        }
        if ($��Π�['uid']) {
            $���� .= ' and t.uid = ? ';
            $��ޒ��[] = $��Π�['uid'];
        }
        $������            = Common::getSortStr($��ޒ��, array(
            'rebacktime' => 't.reback_time'
        ));
        $������->order     = $������;
        $������->condition = $����;
        $������->params    = $��ޒ��;
        return $this->queryAllSearch($������);
    }

    public static function hasNotice() {
        return self::model()->count('uid = ? and pre_time > 0 and pre_time < ? and pre_ok = 0', array(User::getCuruid(), time()));
    }

    public function getOneById($����) {
        return $this->query('id=?', $����);
    }

    public function add($����ҝ) {
        $����ҝ['com_id'] = User::getCurcomid();
        $����ҝ['c_time'] = time();
        $����ҝ['e_time'] = time();
        return $this->insert($����ҝ);
    }

    public function edit($����ҝ, $����) {
        $����ҝ['com_id'] = User::getCurcomid();
        $����ҝ['e_time'] = time();
        return $this->update($����ҝ, 'id = ?', $����);
    }

    /**
     * 通过ID查询记录
     *
     * @param int $com_id 公司ID
     * @param int $id     主键ID
     *
     * @return array
     */
    public static function findById($膋��, $����) {
        $��� = null;
        if ($���� > 0) {
            $��� = self::model()->query('com_id = ? and id = ?', array($膋��, $����));
        }
        return $���;
    }

    public function getTodyFollow() {
        return $this->query('com_id = ?  and c_time >' . mktime(0, 0, 0, date('m'), date('d'), date('Y')) . ' and c_time <' . (mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1), array(User::getCurcomid()), 'count(*) as number');

    }


}

?>