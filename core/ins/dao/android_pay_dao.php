<?php
COMMON('dao','niuniuDao');
class android_pay_dao extends niuniuDao {

	public function __construct() {
		parent::__construct();
        $this->mmc = new Memcache();
        $this->mmc->connect(MMCHOST, MMCPORT);
	}

    public function get_app_info($app_id){
        $data = $this->mmc->get("app_info".$app_id);
        if (!$data) {
            $this->sql = "select * from apps where app_id=?";
            $this->params = array($app_id);
            $this->doResult();
            $data = $this->result;
            $this->mmc->set("app_info".$app_id, $data, MEMCACHE_COMPRESSED,3600);
        }
        return $data;
    }

    public function get_order_info($params){
        $this->sql = "select * from orders where app_order_id=? and order_id=?";
        $this->params = array($params['cp_order_id'],$params['niu_order_id']);
        $this->doResult();
        $data = $this->result;
        return $data;
    }

    public function get_payed_orders(){
        $this->sql = "SELECT b.sdk_charge_url,b.app_key,a.*,c.good_code FROM `orders` as a inner join apps as b on a.app_id=b.app_id 
                      inner join app_goods as c on a.product_id=c.id where a.status=2 order by a.charge_time asc";
        $this->doResultList();
        return $this->result;
    }

    public function get_order_by_id($order_id){
        $this->sql = "select * from orders where order_id=?";
        $this->params = array($order_id);
        $this->doResult();
        $data = $this->result;
        return $data;
    }

    public function up_order_info($id,$params,$status){
        $this->sql = "update orders set pay_order_id=?,`status`=?,pay_time=? where id=?";
        $this->params = array($params['paypal_id'],$status,$params['timestamp'], $id);
        $this->doExecute();
    }

    public function update_order($id,$payment_id){
        $this->sql = "update orders set `status`=?,charge_time=? where id=? and pay_order_id=? ";
        $this->params = array(2,time(),$id,$payment_id);
        $this->doExecute();
    }

    public function update_payssion_order($pm_id,$transaction_id,$payer_email,$order_id){
        $this->sql = "update orders set `status`=?,`pay_time`=?,pm_id=?,pay_order_id=?,`payer`=? where order_id=? ";
        $this->params = array('2',time(),$pm_id,$transaction_id,$payer_email,$order_id);
        $this->doExecute();
    }

    public function get_money_info($app_id,$money_id){
        $this->sql = "select b.app_name,a.* from app_goods as a inner join apps as b on a.app_id=b.app_id 
                      where a.app_id=? and a.good_code=? and a.status=1 order by good_price";
        $this->params = array($app_id,$money_id);
        $this->doResult();
        return $this->result;
    }

    public function get_userapp_channel($user_id, $app_id){
        $this->sql = "select * from user_apptb where userid=? and app_id=? order by id asc limit 0,1";
        $this->params = array($user_id, $app_id);
        $this->doResult();
        return $this->result;
    }

    public function get_paypal_orders(){
        $this->sql = "select * from orders where `status`= 1 and pay_channel= 1 order by charge_time asc";
        $this->doResultList();
        return $this->result;
    }

    public function create_order($order){
        $this->sql = "insert into orders(app_id, order_id, app_order_id, pay_channel, buyer_id, role_id, product_id, unit_price, title, role_name, amount, 
                                        pay_money,pay_price,currency,status, buy_time, ip, serv_id, channel,payExpandData)values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $this->params = array_values($order);
        $this->doInsert();
        return $this->LAST_INSERT_ID;
    }

    public function update_order_charge($id){
        $this->sql = "update orders set charge_time=? where id=?";
        $this->params = array(strtotime("now"), $id);
        $this->doExecute();
    }

    public function update_order_status($id, $status){
        $this->sql = "update orders set status=? where id=?";
        $this->params = array($status, $id);
        $this->doExecute();
    }

    public function set_usr_session($key, $data){
        $session_data = $this->mmc->get("mpay-session-".session_id());
        $session_data[$key] = $data;
        $this->mmc->set("mpay-session-".session_id(), $session_data, MEMCACHE_COMPRESSED, 300);
    }

    public function get_usr_session($key){
        $session_data = $this->mmc->get("mpay-session-".session_id());
        if($key){
            return isset($session_data[$key])?$session_data[$key]:'';
        }else{
            return $session_data;
        }
    }
}
