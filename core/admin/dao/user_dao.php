<?php
COMMON('niuniuDao');
class user_dao extends niuniuDao{

    public function __construct(){
        parent::__construct();
        $this->mmc = new Memcache();
        $this->mmc->connect(MMCHOST, MMCPORT);
    }

    public function get_user_info_list($params,$page){
        $this->limit_sql = "select * from user_info where 1=1";
        if($params['login_type'] && is_numeric($params['login_type']) || $params['login_type'] === '0'){
            $this->limit_sql .=  " and login_type = ".$params['login_type'];
        }
        if($params['fb_bind'] && is_numeric($params['fb_bind']) || $params['fb_bind'] ==='0'){
            $this->limit_sql .= " and fb_bind = ".$params['fb_bind'];
        }
        if($params['reg_from']){
            $this->limit_sql .= " and reg_from = ".$params['reg_from'];
        }
        if($params['user_id']){
            $this->limit_sql .= " and user_id = ".$params['user_id'];
        }
        if($params['nick_name']){
            $this->limit_sql .= " and nick_name = '".$params['nick_name']."'";
        }
        if($params['user_name']){
            $this->limit_sql .= " and user_name = '".$params['user_name']."'";
        }
        if($params['start_time']){
            $this->limit_sql .= " and reg_time >= ".strtotime($params['start_time']);
        }
        if($params['end_time']){
            $this->limit_sql .= " and reg_time <= ".strtotime($params['end_time']);
        }
        $this->limit_sql .= " order by user_id desc";
        $this->doLimitResultList($page);
        return $this->result;
    }
}