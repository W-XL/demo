<?php
COMMON('sdkCore','RNCryptor/RNEncryptor');
DAO('android_pay_dao');

class android_pay extends sdkCore{

    public $DAO;
    public $qa_user_id;

    public function __construct(){
        parent::__construct();
        $this->DAO = new android_pay_dao();
        $this->qa_user_id = array('248','245');
    }

    public function android_pay_order(){
        $timestamp = $_GET['timestamp'];
        $USR_HEADER = $this->get_usr_session('USR_HEADER');
        $app_id = $USR_HEADER['app_id'];
        $app_info = $this->DAO->get_app_info($app_id);
        if($USR_HEADER['md5'] !== md5($app_id.$USR_HEADER['player_id'].$USR_HEADER['serv_id'].$USR_HEADER['user_id'].$USR_HEADER['usertoken'].$app_info['app_key'])){
            $result = array("errcode"=>1,"message"=>"参数不正确");
        }else{
            $time = strtotime("now");
            $money = $this->DAO->get_money_info($app_id,$USR_HEADER['money_id']);
            $money['unit_price'] = $money['good_price'];
            $SYS_order_id = $this->orderid($USR_HEADER['app_id']);
            if($USR_HEADER['cp_order_id']){
                $app_order_id = $USR_HEADER['cp_order_id'];
            }else{
                $result = array("errcode"=>1,"message"=>"游戏订单号创建失败。");
                die("0".base64_encode(json_encode($result)));
            }
            if(!empty($USR_HEADER['goodmultiple'])){
                $money['pay_price'] = $money['good_price'] * $USR_HEADER['goodmultiple'];
            }else{
                $USR_HEADER['goodmultiple'] = 1;
                $money['pay_price'] = $money['good_price'];
            }
            $pay_chanel = 0;
            if($USR_HEADER['pay_type'] == 'paypal'){
                $pay_chanel = 1;
            }elseif($USR_HEADER['pay_type'] == 'payssion'){
                $pay_chanel = 2;
            }
            if(empty($pay_chanel)){
                $result = array("errcode"=>1,"message"=>"支付渠道错误。");
                die("0".base64_encode(json_encode($result)));
            }
            if(in_array($USR_HEADER['user_id'], $this->qa_user_id)){
                $money['pay_price'] = 0.01;
            }
            $this->create_order($money, $USR_HEADER, $SYS_order_id, $app_order_id, $time,$pay_chanel);

            $sign = $app_id.$SYS_order_id.$timestamp.$money['pay_price'].$app_info['app_key'];
            $result = array(
                "errcode" => 0,
                "message" => "订单生成成功",
                "orderid" => $SYS_order_id,
                'cp_order_id' => $app_order_id,
                "goodsname" => $money['good_amount']*$USR_HEADER['goodmultiple'].$money['good_unit'],
                "goodsdesc" => $money['good_intro'],
                "goodsfee" => $money['pay_price'],
                "currency_type" => $USR_HEADER['currency_type'],
                "timestamp" => $timestamp,
                "paytype" => $USR_HEADER['pay_type'],
                "sign" => md5($sign)
            );
        }
        die("0".base64_encode(json_encode($result)));
    }

    public function paypal_result($app_id){
        $result = array('result'=>1,'desc'=>'网络请求异常！');
        $app_info = $this->DAO->get_app_info($app_id);
        if(!$app_info){
            $result['desc']='游戏信息异常';
            die("0".base64_encode(json_encode($result)));
        }
        $verify_result = $this->param_verify($app_info);
        if(!empty($verify_result)){
            $result['desc']=$verify_result;
            die("0".base64_encode(json_encode($result)));
        }
        //查询订单
        $this->err_log(var_export($_POST,1),"paypal_result");
        $order_info = $this->DAO->get_order_info($_POST);
        if(!$order_info){
            $result['desc'] = '查询失败';
            die("0".base64_encode(json_encode($result)));
        }elseif($order_info['status']=='0'){
            $this->DAO->up_order_info($order_info['id'],$_POST,1);// 客户端支付
            $result = array('result'=>2,'desc'=>'请求成功！');
        }else{
            $result = array('result'=>2,'desc'=>'请求成功！');
        }
        die("0".base64_encode(json_encode($result)));
    }

    public function param_verify($app_info){
        $msg = '';
        $data = $_POST;
        if(!$data){
            $msg = '参数丢失';
            return $msg;
        }
        if(!$data['paypal_id'] || !$data['cp_order_id'] || !$data['niu_order_id'] || !$data['timestamp'] || !$data['sign']){
            $msg = '参数异常！';
            return $msg;
        }
        $sign = $app_info['app_id'].$data['paypal_id'].$data['cp_order_id'].$data['niu_order_id'].$data['timestamp'].$app_info['app_key'];
        if(md5($sign)!= $data['sign']){
            $msg = '加密出错！';
            return $msg;
        }
        return $msg;
    }

    public function array_to_xml($arr=array()){
        $xml = '<xml>';
        foreach ($arr as $key => $val){
            if(is_array($val)){
                $xml .= "<".$key.">".$this->array_to_xml($val)."</".$key.">";
            }else{
                $xml .= "<".$key.">".$val."</".$key.">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }


    protected function make_pay_sign($order,$money,$USR_HEADER,$app_info){
        if(!$order || !$money || !$USR_HEADER || !$app_info) {
            return '';
        }
        $sign = md5($order['app_id'].$order['order_id'].$money['good_price'].$order['buyer_id'].$USR_HEADER['usertoken'].$app_info['app_key']);
        return $sign;
    }

    public function create_guids() {
        $charid = strtolower(md5(uniqid(mt_rand(), true)));
        $uuid = substr($charid, 0, 32);
        return $uuid;
    }


    protected function create_order($money, $header, $YD_order_id, $app_order_id, $time,$pay_channel=1){
        $order['app_id']        = $header['app_id'];
        $order['order_id']       = $YD_order_id;
        $order['app_order_id']  = $app_order_id;
        $order['pay_channel']   = $pay_channel;
        $order['buyer_id']    = $header['user_id'];
        $order['role_id']     = $header['player_id'];
        $order['product_id']  = $money['id'];
        $order['unit_price']  = $money['unit_price'];
        $order['title']       = $money['good_name'];
        $order['role_name']   = $header['player_name'];
        $order['amount']      = $money['good_amount'];
        $order['pay_money']   = $money['good_price'];
        $order['pay_price']   = isset($money['pay_price'])?$money['pay_price']:$money['good_price'];
        $order['currency']   = $header['currency_type'];
        $order['status']      = 0;
        $order['buy_time']    = $time;
        $order['ip']     = $this->client_ip();
        $order['serv_id']   = $header['serv_id'];
        $order['channel']   = $header['channel'];
        $order['payExpandData'] = isset($header['payexpanddata'])?$header['payexpanddata']:'';
        $user_info = $this->DAO->get_userapp_channel($header['user_id'],$header['app_id']);
        if($user_info['channel']){
            $order['channel']   = $user_info['channel'];
        }
        if(!$this->DAO->create_order($order)){
            $result = array("errcode"=>1,"message"=>"订单创建失败!");
            die("0".base64_encode(json_encode($result)));
        }
        return $order;
    }

    public function set_usr_session($key, $data){
        $this->DAO->set_usr_session($key, $data);
    }

    public function get_usr_session($key=''){
        return $this->DAO->get_usr_session($key);
    }

    public function check_user_agent($user_agent, $app_id){
        $app_info = $this->DAO->get_app_info($app_id);
        if(!$app_info){
            die("参数错误");
        }

        $ua = rawurldecode($user_agent);
        $ua = base64_decode($ua);

        $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $app_info['app_key'], $ua, MCRYPT_MODE_CBC, AES_IV);

        $header = explode("&", $decrypted);
        return $header;
    }
}