<?php
COMMON('niuniuDao');
class qa_dao extends niuniuDao{

    public function __construct()
    {
        parent::__construct();
        $this->mmc = new Memcache();
        $this->mmc->connect(MMCHOST, MMCPORT);
    }

    public function get_qa_device($params,$page){
        $this->limit_sql = "select * from stats_device where 1=1";
        if($params['type']){
            $this->limit_sql .= " and type = ".$params['type'];
        }
        if($params['app_id']){
            $this->limit_sql .= " and AppID = ".$params['app_id'];
        }
        if($params['channel']){
            $this->limit_sql .= " and Channel = '".$params['channel']."'";
        }
        if($params['start_time']){
            $this->limit_sql .= " and ActTime >= '".strtotime($params['start_time'])."'";
        }
        if($params['end_time']){
            $this->limit_sql .= " and ActTime <= '".strtotime($params['end_time'])."'";
        }
        $this->limit_sql .= " order by id desc";
        $this->doLimitResultList($page);
        return $this->result;
    }

    public function get_apps_list(){
        $this->sql = "select * from apps where status = 1 order by id desc";
        $this->doResultList();
        return $this->result;
    }

    public function get_qa_role($params,$page){
        $this->limit_sql = "select * from stats_user_app where 1=1";
        if($params['type']){
            $this->limit_sql .= " and type = ".$params['type'];
        }
        if($params['app_id']){
            $this->limit_sql .= " and AppID = ".$params['app_id'];
        }
        if($params['user_id']){
            $this->limit_sql .= " and UserID = ".$params['user_id'];
        }
        if($params['channel']){
            $this->limit_sql .= " and Channel = '".$params['channel']."'";
        }
        $this->limit_sql .= " order by ID desc";
        $this->doLimitResultList($page);
        return $this->result;
    }

    public function get_qa_login($params,$page){
        $this->limit_sql = "select * from stats_user_op_log where 1=1";
        if($params['type']){
            $this->limit_sql .= " and type = ".$params['type'];
        }
        if($params['app_id']){
            $this->limit_sql .= " and appid = ".$params['app_id'];
        }
        if($params['user_id']){
            $this->limit_sql .= " and userid = ".$params['user_id'];
        }
        if($params['channel']){
            $this->limit_sql .= " and channel = '".$params['channel']."'";
        }
        $this->limit_sql .= " order by ID desc";
        $this->doLimitResultList($page);
        return $this->result;
    }


}