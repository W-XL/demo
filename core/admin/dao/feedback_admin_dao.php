<?php
COMMON('niuniuDao');

class feedback_admin_dao extends niuniuDao {

    public function __construct() {
        parent::__construct();
        $this->TB_NAME = "sys_feedbacktb";
        $this->mmc = new Memcache();
        $this->mmc->connect(MMCHOST, MMCPORT);
    }

    public function get_list($page,$params){
        $this->limit_sql = "select a.*,b.app_name from sys_feedbacktb as a inner join niuniu.apps as b on a.appid=b.app_id where 1=1 ";
        if($params['app_id']){
            $this->limit_sql = $this->limit_sql." and a.appid = ".$params['app_id'];
        }
        if($params['user_id']){
            $this->limit_sql = $this->limit_sql." and a.user_id = ".$params['user_id'];
        }
        $this->limit_sql = $this->limit_sql." order by a.id desc";
        $this->doLimitResultList($page);
        return $this->result;
    }

    public function get_feedback_name(){
        $this->sql = "select * from  apps  order by id desc ";
        $this->doResultList();
        return $this->result;
    }

    public function get_account_retrieve($page,$params){
        $this->limit_sql = 'select * from sys_account_back where 1=1 ';
        if($params['email']){
            $this->limit_sql = $this->limit_sql." and email = '".$params['email']."'";
        }
        $this->limit_sql = $this->limit_sql." order by add_time desc";
        $this->doLimitResultList($page);
        return $this->result;
    }

    public function get_account_info($id) {
        $this->sql='select * from sys_account_back where id='.$id;
        $this->doResult();
        return $this->result;
    }

    public function update_account($feedback,$id) {
        $this->sql = "update sys_account_back set status=?, operator_id=?, reply=?, remarks=?, operator_time=? where id=?";
        $this->params = array($feedback['status'],$feedback['operator_id'],$feedback['reply'],$feedback['remarks'],$feedback['operator_time'],$id);
        $this->doExecute();
    }

    public function get_feedback($id){
        $this->sql = 'select a.*,b.app_name from sys_feedbacktb as a inner join apps as b on a.appid=b.app_id where a.id=?';
        $this->params = array($id);
        $this->doResult();
        return $this->result;
    }

    public function update_feedback($params, $id){
        $this->sql = "update sys_feedbacktb set feedback=?,feedback_usr=?,feedback_time=? where id=?";
        $this->params = array($params['feedback'], $params['feedback_usr'], $params['feedback_time'], $id);
        $this->doExecute();
    }

    public function get_user_info($user_id){
        $this->sql = "SELECT user_id,nick_name,login_name FROM user_info WHERE user_id=? ";
        $this->params = array($user_id);
        $this->doResult();
        return $this->result;
    }

}
?>