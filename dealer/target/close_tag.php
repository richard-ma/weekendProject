<?php

/**
 * Created by PhpStorm.
 * User: @van
 * Date: 2015/4/23
 * Time: 15:05
 */
class FollowRecord
{
    const TYPE_RECORD  = 1;//è·Ÿè¿›çºªå½•
    const TYPE_PLAN    = 2;//è·Ÿè¿›è®¡åˆ’
    const TYPE_SERVICE = 3;//è·Ÿè¿›æœåŠ¡
    const TYPE_REBACK  = 4;//å›è®¿

    public function searchNotice() {
        $üÓÏà’¬            = new CSearch();
        $üÓÏà’¬->condition = 'uid = ? and pre_time > 0 and pre_time < ? and pre_ok = 0';
        $üÓÏà’¬->join      = 'left join t_client  as client on client.id = t.cli_id';
        $üÓÏà’¬->params    = array(User::getCuruid(), time());
        $üÓÏà’¬->select    = 't.*,client.name cli_name';
        return $this->queryAllSearch($üÓÏà’¬);
    }

    public function searchReback($›ó‚Î º) {
        $üÓÏà’¬    = new CSearch();
        $šúí«‘È = 'cli_id = ? and is_reback = 1';
        $…óŞ’œÅ    = array($›ó‚Î º['cli_id']);
        if ($›ó‚Î º['start_time']) {
            $šúí«‘È .= ' and t.time >= ? ';
            $…óŞ’œÅ[] = strtotime($›ó‚Î º['start_time']);
        }
        if ($›ó‚Î º['end_time']) {
            $šúí«‘È .= ' and t.time <= ? ';
            $…óŞ’œÅ[] = strtotime($›ó‚Î º['end_time']) + 86400;
        }
        if ($›ó‚Î º['reback_type_id']) {
            $šúí«‘È .= ' and t.fid = ? ';
            $…óŞ’œÅ[] = $›ó‚Î º['reback_type_id'];
        }
        if ($›ó‚Î º['uid']) {
            $šúí«‘È .= ' and t.uid = ? ';
            $…óŞ’œÅ[] = $›ó‚Î º['uid'];
        }
        $»´•¾½ª            = Common::getSortStr($…óŞ’œÅ, array(
            'rebacktime' => 't.reback_time'
        ));
        $üÓÏà’¬->order     = $»´•¾½ª;
        $üÓÏà’¬->condition = $šúí«‘È;
        $üÓÏà’¬->params    = $…óŞ’œÅ;
        return $this->queryAllSearch($üÓÏà’¬);
    }

    public static function hasNotice() {
        return self::model()->count('uid = ? and pre_time > 0 and pre_time < ? and pre_ok = 0', array(User::getCuruid(), time()));
    }

    public function getOneById($öäá») {
        return $this->query('id=?', $öäá»);
    }

    public function add($½òÖÛÒ) {
        $½òÖÛÒ['com_id'] = User::getCurcomid();
        $½òÖÛÒ['c_time'] = time();
        $½òÖÛÒ['e_time'] = time();
        return $this->insert($½òÖÛÒ);
    }

    public function edit($½òÖÛÒ, $öäá») {
        $½òÖÛÒ['com_id'] = User::getCurcomid();
        $½òÖÛÒ['e_time'] = time();
        return $this->update($½òÖÛÒ, 'id = ?', $öäá»);
    }

    /**
     * é€šè¿‡IDæŸ¥è¯¢è®°å½•
     *
     * @param int $com_id å…¬å¸ID
     * @param int $id     ä¸»é”®ID
     *
     * @return array
     */
    public static function findById($è†‹ó€, $öäá») {
        $ñÅôƒ… = null;
        if ($öäá» > 0) {
            $ñÅôƒ… = self::model()->query('com_id = ? and id = ?', array($è†‹ó€, $öäá»));
        }
        return $ñÅôƒ…;
    }

    public function getTodyFollow() {
        return $this->query('com_id = ?  and c_time >' . mktime(0, 0, 0, date('m'), date('d'), date('Y')) . ' and c_time <' . (mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1), array(User::getCurcomid()), 'count(*) as number');

    }


}

?>