<?php
COMMON('niuniuDao');
class orders_admin_dao extends niuniuDao {

    public function __construct() {
        parent::__construct();
        $this->mmc = new Memcache();
        $this->mmc->connect(MMCHOST, MMCPORT);
        $this->TB_NAME = "orders";
    }

    public function get_order_list($page, $params){
        $this->limit_sql = "select a.*,b.app_name,c.channel as ch from orders as a inner join apps as b on a.app_id=b.app_id
                          left join user_apptb as c on a.app_id=c.app_id and a.buyer_id=c.userid ";
        $this->limit_sql .= "where 1=1 ";
        if($params['app_id']){
            $this->limit_sql .= " and a.app_id=".$params['app_id'];
        }
        if($params['status'] === '0'){
            $this->limit_sql .= " and a.status='".$params['status']."'";
        }elseif($params['status']== '1'){
            $this->limit_sql .= " and a.status in(1,2) ";
        }elseif($params['status']){
            $this->limit_sql .= " and a.status='".$params['status']."'";
        }
        if($params['ch'] && empty($params['ch_status'])){
            $this->limit_sql .= " and c.channel='".$params['ch']."'";
        }
        if($params['ch_id'] && $_SESSION['group_id'] == '1'){
            $this->limit_sql .= " and b.ch_id=".$params['ch_id'];
        }
        if($params['buyer_id']){
            $this->limit_sql .= " and a.buyer_id=".$params['buyer_id'];
        }
        if($params['start_time']){
            $this->limit_sql .= " and a.pay_time >=".strtotime($params['start_time']);
        }
        if($params['end_time']){
            $this->limit_sql .= " and a.pay_time <".strtotime($params['end_time']);
        }
        if($params['qa'] == "1"){
            $this->limit_sql .= " and a.buyer_id not in('15443','71','5632','13478','13474')";
        }elseif($params['qa'] == "2"){
            $this->limit_sql .= " and a.buyer_id in('15443','71','5632','13478','13474')";
        }
        if(!empty($params['guild_code'])){
            $this->limit_sql .= " and c.channel in (".$params['guild_code'].")";
        }
        if($_SESSION['group_id']<>1){
            if($_SESSION['guild_code'] && empty($params['guild_code'])){
                $this->limit_sql .= " and c.channel in (".$_SESSION['guild_code'].")";
            }
            if(empty($_SESSION['apps'])){
                $this->limit_sql .= " and a.app_id=''";
            }else{
                $this->limit_sql .= " and a.app_id in (".$_SESSION['apps'].")";
            }
        }
        if($params['pay_channel']){
            $this->limit_sql .= " and a.pay_channel = ".$params['pay_channel'];
        }
        if($params['channel'] == '1'){
            $this->limit_sql .= " and ch_type = 1 ";
        }elseif($params['channel'] == '2'){
            $this->limit_sql .= " and ch_type > 1 ";
        }
        $this->limit_sql = $this->limit_sql." order by a.id desc";
        $this->doLimitResultList($page);
        return $this->result;
    }

    public function get_order_list_nolimit($params){
        $this->sql = "select a.*,b.app_name,b.payee_ch,c.channel as ch from orders as a inner join apps as b on a.app_id=b.app_id
                          left join user_apptb as c on a.app_id=c.app_id and a.buyer_id=c.userid ";
        $this->sql .= "where 1=1 ";

        if($params['app_id']){
            $this->sql .= " and a.app_id='".$params['app_id']."'";
        }
        if($params['status'] === '0'){
            $this->sql .= " and a.status='".$params['status']."'";
        }elseif($params['status']== '1'){
            $this->sql .= " and a.status in(1,2) ";
        }elseif($params['status']){
            $this->sql .= " and a.status='".$params['status']."'";
        }
        if($params['buyer_id']){
            $this->sql .= " and a.buyer_id=".$params['buyer_id'];
        }
        if($params['start_time']){
            $this->sql .= " and a.pay_time >=".strtotime($params['start_time']);
        }
        if($params['end_time']){
            $this->sql .= " and a.pay_time <".strtotime($params['end_time']);
        }
        if($params['qa'] == "1"){
            $this->sql .= " and a.buyer_id not in('15443','71','5632','13478','13474')";
        }elseif($params['qa'] == "2"){
            $this->sql .= " and a.buyer_id in('15443','71','5632','13478','13474')";
        }
        if($_SESSION['group_id']<>1){
            if(empty($_SESSION['apps'])){
                $this->sql .= " and a.app_id=''";
            }else{
                $this->sql .= " and a.app_id in (".$_SESSION['apps'].")";
            }
        }
        if($params['pay_channel']){
            $this->sql .= " and a.pay_channel = ".$params['pay_channel'];
        }
        $this->sql = $this->sql." order by a.id desc";
        $this->doResultList();
        return $this->result;
    }

    public function get_sum_money($params){
        $this->sql = "select sum(a.pay_money) as money from orders as a inner join apps as b on a.app_id=b.app_id
                          left join user_apptb as c on a.app_id=c.app_id and a.buyer_id=c.userid ";
        $this->sql .= "where 1=1 ";
        if(empty($params['status'])){
            $this->sql .= " and a.status= 2 ";
        }
        if($params['app_id']){
            $this->sql .= " and a.app_id=".$params['app_id'];
        }
        if($params['status']){
            $this->sql .= " and a.status='".$params['status']."'";
        }
        if($params['status'] === '0'){
            $this->sql .= " and a.status='".$params['status']."'";
        }
        if($params['buyer_id']){
            $this->sql .= " and a.buyer_id=".$params['buyer_id'];
        }
        if($params['start_time']){
            $this->sql .= " and a.pay_time >=".strtotime($params['start_time']);
        }
        if($params['end_time']){
            $this->sql .= " and a.pay_time <".strtotime($params['end_time']);
        }
        if($params['qa'] == "1"){
            $this->sql .= " and a.buyer_id not in('15443','71','5632','13478','13474')";
        }elseif($params['qa'] == "2"){
            $this->sql .= " and a.buyer_id in('15443','71','5632','13478','13474')";
        }
        if($_SESSION['group_id']<>1){
            if(empty($_SESSION['apps'])){
                $this->sql .= " and a.app_id=''";
            }else{
                $this->sql .= " and a.app_id in (".$_SESSION['apps'].")";
            }
        }
        if($params['pay_channel']){
            $this->sql .= " and a.pay_channel = ".$params['pay_channel'];
        }
        $this->sql = $this->sql." order by a.id desc";
        $this->doResultList();
        return $this->result;
    }

    public function update_app($app, $id){
        $this->sql = "update apps set app_name=?,status=?,lastupdate=?,app_icon=?,sdk_order_url=?,sdk_charge_url=? where id=?";
        $this->params = array($app['app_name'], $app['status'], strtotime("now"), $app['app_icon'], $app['sdk_order_url'], $app['sdk_charge_url'], $id);
        $this->doExecute();
    }
    
    public function insert_app($app, $app_key){
        $this->sql = "insert into apps(app_id, app_key, app_name, status, add_time, sdk_order_url, sdk_charge_url)values(?,?,?,?,?,?,?)";
        $this->params = array($app['app_id'], $app_key,$app['app_name'], $app['status'], strtotime("now"), $app['sdk_order_url'], $app['sdk_charge_url']);
        $this->doInsert();
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

    public function get_code_info($p){
        $this->sql = "select user_code from admins where id = ?";
        $this->params = array($p);
        $this->doResult();
        return $this->result;
    }



}