<?php
COMMON('niuniuDao');
class app_admin_dao extends niuniuDao {

    public function __construct() {
        parent::__construct();
        $this->mmc = new Memcache();
        $this->mmc->connect(MMCHOST, MMCPORT);
        $this->TB_NAME = "apps";
    }

    public function get_app_list($page,$params){
        $this->limit_sql="select * from apps where 1=1";
        if($params['app_id']){
            $this->limit_sql = $this->limit_sql." and app_id = ".$params['app_id'];
        }
        if($params['access_type'] && is_numeric($params['access_type']) || $params['access_type'] === "0"){
            $this->limit_sql = $this->limit_sql." and access_type = ".$params['access_type'];
        }
        $this->limit_sql = $this->limit_sql." order by id desc";
        $this->doLimitResultList($page);
        return $this->result;
    }

    public function get_app_name(){
        $this->sql="select * from apps where app_id <>'5001'";
        $this->doResultList();
        return $this->result;
    }

     public function get_online_game_list(){
        $this->sql="select * from apps where access_type > 2 ";
        $this->doResultList();
        return $this->result;
    }

    public function get_ch_apps($ch_id){
        $this->sql="select * from apps where ch_id=? order by id desc";
        $this->params=array($ch_id);
        $this->doResultList();
        return $this->result;
    }

    public function get_user_info($user_id){
        $this->sql="select * from admins where id=?  ";
        $this->params = array($user_id);
        $this->doResult();
        return $this->result;
    }

    public function get_user_apps(){
        $data = $this->mmc->get("admin_apps".$_SESSION['usr_id']);
        if(!$data){
            $this->sql="select * from apps where 1=1 ";
            if($_SESSION['group_id']<>1){
                $this->sql .= " and (";
                $apps = explode(",", $_SESSION['apps']);
                foreach ($apps as $k=>$v){
                    if($k==0){
                        $this->sql .= " app_id=".$v;
                    }else{
                        $this->sql .= " or app_id=".$v;
                    }
                }
                $this->sql .=")";
            }
            $this->sql.=" order by id desc";
            $this->doResultList();
            $data = $this->result;
            $this->mmc->set("admin_apps".$_SESSION['usr_id'], $data,MEMCACHE_COMPRESSED, 300);
        }
        return $data;
    }

    public function get_all_app(){
        $data = $this->mmc->get("admin_all_apps");
        if(!$data){
            $this->sql = "select * from apps order by id desc";
            $this->doResultList();
            $data = $this->result;
            $this->mmc->set("admin_all_apps", $data, MEMCACHE_COMPRESSED, 7200);
        }
        return $data;
    }

    public function update_app($app, $id){
        $this->sql = "update apps set app_name=?,app_type=?,channel=?,status=?,lastupdate=?,app_icon=?,sdk_order_url=?,sdk_charge_url=?,
                        web_serv_url=?,web_user_url=?,web_order_url=?,web_charge_url=?,version=?,version_time=?,
                        version_url=?,access_type=?,role_type=?,web_url=? where id=?";
        $this->params = array($app['app_name'], $app['app_type'],$app['channel'],$app['status'], strtotime("now"), $app['app_icon'], $app['sdk_order_url'],
            $app['sdk_charge_url'], $app['web_serv_url'], $app['web_user_url'], $app['web_order_url'], $app['web_charge_url'],
             $app['version'], strtotime($app['version_time']), $app['version_url'], $app['access_type'],$app['role_type'],$app['web_url'], $id);
        $this->doExecute();
        $this->mmc->delete("app_info".$app['app_id']);
    }

    public function update_app_notice($app, $id){
        $this->sql = "update apps set notice=?,notice_status=? where id=?";
        $this->params = array($app['notice'], $app['notice_status'], $id);
        $this->doExecute();
        $this->mmc->delete("app_info".$app['app_id']);
    }

    public function version_update($app, $id){
        $this->sql = "update apps set version=?,up_title=?,up_desc=?,up_status=? where id=?";
        $this->params = array($app['version'],$app['up_title'],$app['up_desc'],$app['up_status'], $id);
        $this->doExecute();
        $this->mmc->delete("app_info".$app['app_id']);
    }

    public function insert_app($app, $app_key){
        $this->sql = "insert into apps(app_id, app_key, app_name, app_type, status, add_time, sdk_order_url, sdk_charge_url)values(?,?,?,?,?,?,?,?)";
        $this->params = array($app['app_id'], $app_key, $app['app_name'], $app['app_type'], $app['status'], strtotime("now"), $app['sdk_order_url'], $app['sdk_charge_url']);
        $this->doInsert();
        $this->mmc->delete("all_apps");
        return $this->LAST_INSERT_ID;
    }

    public function update_app_icon($img, $id, $app_key){
        $this->sql = "update apps set app_icon=?,app_key=? where id=?";
        $this->params = array($img, $app_key, $id);
        $this->doExecute();
    }

    public function check_app_id($app_id){
        $this->sql = "select * from apps where app_id=?";
        $this->params = array($app_id);
        $this->doResultList();
        return $this->result;
    }

}