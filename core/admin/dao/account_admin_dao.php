<?php
COMMON('niuniuDao');
class account_admin_dao extends niuniuDao {

    public function __construct() {
        parent::__construct();
        $this->mmc = new Memcache();
        $this->mmc->connect(MMCHOST, MMCPORT);
        $this->TB_NAME = "admins";
    }

    public function get_account_list($page,$params){

        $this->limit_sql = "select a.*,b.ch_name from admins as a inner join admin_groups as b on a.group_id=b.id where a.is_del='0'";
        if ($params['user_id']){
            $this->limit_sql = $this->limit_sql . " and a.id =".$params['user_id'];
        }
        $this->limit_sql=$this->limit_sql." order by a.last_login desc";
        $this->doLimitResultList($page);
        return $this->result;
    }

    public function get_list($params){
        $this->sql = "select * from admins where is_del = 0";
        if($params['type']== 1){
            $this->sql = $this->sql . " and p1 ='0' and p2 ='0' and group_id ='10'";
        }elseif($params['type']== 2){
            $this->sql = $this->sql . " and p1 !='0' and p2 ='0' and group_id ='10'";
        }elseif($params['type']== 3){
            $this->sql = $this->sql . " and p1 !='0' and p2 !='0' and group_id ='10'";
        }elseif($params['type']== 4){
            $this->sql = $this->sql . " and group_id !='10'";
        }
        if($params['group_id']){
          $this->sql = $this->sql . " and group_id = ".$params['group_id'];
        }
        $this->doResultList();
        return $this->result;
    }

    public function get_account_by_group($usr_id,$page,$params){
        $this->limit_sql = "select a.*,b.ch_name from admins as a inner join admin_groups as b on a.group_id=b.id where a.is_del='0'";
        if($params['user_id']){
            $this->limit_sql = $this->limit_sql . " and a.id =".$params['user_id'];
        }
        $this->limit_sql = $this->limit_sql. " order by a.last_login desc";
        $this->doLimitResultList($page);
        return $this->result;
    }

    public function get_user_info($user_id){
        $data = $this->mmc->get("user_info_".$user_id);
        if(!$data) {
            $this->sql="select * from admins where id =?";
            $this->params = array($user_id);
            $this->doResult();
            $data = $this->result;
            $this->mmc->set("user_info_".$user_id,$data,MEMCACHE_COMPRESSED,300);
        }
        return $data;
    }

    public function update_account($account, $id){
        $this->sql = "update admins set account=?, real_name=?, qq=?, user_code=?, group_id=? where id=?";
        $this->params = array($account['account'], $account['real_name'], $account['qq'], $account['user_code'], $account['group_id'], $id);
        $this->doExecute();
    }

    public function insert_account($account){
        $this->sql = "insert into admins(account, real_name, qq, user_code, group_id, last_login, usr_pwd)values(?,?,?,?,?,?,?)";
        $this->params = array($account['account'],$account['real_name'],$account['qq'],$account['user_code'],$account['group_id'], strtotime("now"), $account['usr_pwd']);
        $this->doInsert();
        $user_id = $this->LAST_INSERT_ID;

        $group = $this->get_group($account['group_id']);
        $this->sql = "insert into admin_permissions(usr_id,module,permissions)values(?,?,?)";
        $this->params = array($user_id, $group['module'], $group['module']);
        $this->doExecute();
        return $user_id;
    }

    public function get_groups(){
        $this->sql = "select * from admin_groups order by id desc";
        $this->doResultList();
        return $this->result;
    }

    public function get_group($id){
        $this->sql = "select * from admin_groups where id=?";
        $this->params = array($id);
        $this->doResult();
        return $this->result;
    }

    public function check_account($account){
        $this->sql = "select * from admins where account=? or user_code=?";
        $this->params = array($account['account'], $account['user_code']);
        $this->doResult();
        return $this->result;
    }

    public function update_account_pwd($account, $id){
        $this->sql = "update admins set usr_pwd=? where id=?";
        $this->params = array(md5($account['password']), $id);
        $this->doExecute();
    }

    public function get_user_modules($user_id){
        $this->sql = "select * from admin_permissions where usr_id=?";
        $this->params = array($user_id);
        $this->doResult();
        return $this->result;
    }

    public function get_group_list($id){
        $this->sql = "select id from admins where group_id = ?";
        $this->params = array($id);
        $this->doResultList();
        return $this->result;
    }

    public function insert_user_permision($user_id, $menus){
        $this->sql = "insert into admin_permissions(usr_id, module, permissions)values(?,?,?)";
        $this->params = array($user_id, $menus, $menus);
        $this->doExecute();
        $this->update_user_token($user_id);
    }

    public function update_user_permision($user_id, $menus){
        $this->sql = "update admin_permissions set module=?,permissions=? where usr_id=?";
        $this->params = array($menus, $menus, $user_id);
        $this->doExecute();
        $this->update_user_token($user_id);
    }

    public function update_group_permision($user_id, $menus ,$group_id){
        $this->sql = "update admin_groups set module=?,permissions=? where id=?";
        $this->params = array($menus, $menus, $user_id);
        $this->doExecute();

        $this->sql = "update admin_permissions set module = ?,permissions = ? where usr_id in (".$group_id.")";
        $this->params = array($menus,$menus);
        $this->doExecute();
    }

    public function update_user_token($user_id){
        $token = $this->create_guid();
        $this->sql = "update admins set token=? where id=?";
        $this->params = array($token, $user_id);
        $this->doExecute();
    }

    protected function create_guid() {
        $charid = strtolower(md5(uniqid(mt_rand(), true)));
        $hyphen = chr(45);
        $uuid = substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12);
        return $uuid;
    }

    public function get_menu($id){
        $this->sql = "select * from admin_menus where id=?";
        $this->params = array($id);
        $this->doResult();
        return $this->result;
    }
    public function get_user_apps($user_id){
        $this->sql = "select * from admins where id=? ";
        $this->params = array($user_id);
        $this->doResult();
        return $this->result;
    }

    public function update_user_apps($user_id, $apps){
        $token = $this->create_guid();
        $this->sql = "update admins set apps=?,token=? where id=?";
        $this->params = array($apps, $token, $user_id);
        $this->doExecute();
    }

    public function del_user($user_id){
        $this->sql = "update admins set is_del='1' where id=?";
        $this->params = array($user_id);
        $this->doExecute();
    }


}