<?php
COMMON('sdkCore','uploadHelper');
DAO('android_pay_dao');

class android_pay_web extends sdkCore{

    public $DAO;
    public $qa_user_id;
    public $lang;
    public $gameVer;

    public function __construct(){
        parent::__construct();
        $this->DAO = new android_pay_dao();
        $this->qa_user_id = array();
        $this->lang = 'en';
        if(isset($_SERVER['HTTP_USER_AGENT1'])){
            $header = base64_decode(substr($_SERVER['HTTP_USER_AGENT1'],1));
            $header = explode("&",$header);
            foreach($header as $k=>$param){
                $param = explode("=",$param);

                if($param[0] == 'sdkver'){
                    $this->sdkver = $param[1];
                }elseif($param[0] == 'channel'){
                    $this->guild_code = $param[1];
                }elseif($param[0] == 'ver'){
                    $this->gameVer = $param[1];
                }
                if($param[0] == 'lang' && $param[1]){
                    $this->lang = strtolower($param[1]);
                }
            }
        }
    }

    public function set_usr_session($key, $data){
        $this->DAO->set_usr_session($key, $data);
    }

    public function get_usr_session($key=''){
        return $this->DAO->get_usr_session($key);
    }

    public function game_notice(){
        $app_id = $this->usr_params['app_id'];
        if(!$app_id){
            die("参数错误");
        }
        $app_info = $this->DAO->get_app_info($app_id);
        if($app_info['notice_status']==1){
            $this->game_notice_title($app_info);
            $this->assign("info", $app_info);
            $this->display("game_notice.html");
        }
    }

    public function game_notice_title($app_info){
        $title = $app_info['app_name'].' notice';
        if($this->lang == 'en'){
            $title = $app_info['app_name'].' notice';
        }elseif($this->lang == 'zh-cn'){
            $title = $app_info['app_name'].'公告';
        }
        $this->assign("title", $title);
    }

    public function game_version(){
        $app_id = $this->usr_params['app_id'];
        if(!$app_id){
            die("参数错误");
        }
        $app_info = $this->DAO->get_app_info($app_id);
        $type = 1;
        $url = "";
        $ip_array = array('117.25.82.219', '117.25.82.208', '110.83.60.75', '117.27.76.225', '117.25.83.163', '117.25.83.168');
        if($app_info['up_status']=='1'){
            if($app_info['version'] > $this->gameVer && $app_info['version_url']){
                $type = 2;
                $url = $app_info['version_url'];
            }
        }elseif($app_info['up_status'] == '2' && in_array($this->client_ip(),$ip_array)){
            if($app_info['version'] > $this->gameVer && $app_info['version_url']){
                $type = 2;
                $url = $app_info['version_url'];
            }
        }
        $result = array("result"=>1,"desc"=>"","type"=>$type, "data"=>array("appver"=>$app_info['version'],"forceupdate"=>"2",
                    "title"=>$app_info['up_title'],"content"=>$app_info['up_desc'],"url"=>$url),"is_notice"=>$app_info['notice_status'],
            "pay_status"=>'0');//pay_status 0 google_pay  1 其他支付
//            "pay_status"=>$app_info['pay_status']);
        die("0".base64_encode(json_encode($result)));
    }

    public function paypal_callback(){
        $paypal_orders = $this->DAO->get_paypal_orders();
        if(!$paypal_orders){
            die("执行完毕");
        }
        foreach($paypal_orders as $k=>$order){
            $this->paypal_verify($order['pay_order_id'],$order);
        }
        die("操作成功");
    }

    public function payssion_callback(){
        $pm_id = $_POST['pm_id'];
        $transaction_id = $_POST['transaction_id'];
        $amount = $_POST['amount'];
        $currency = $_POST['currency'];
        $order_id = $_POST['order_id'];
        $state = $_POST['state'];
        $payer_email = $_POST['payer_email'];
        $notify_sig = $_POST['notify_sig'];
        $msg = implode('|', array(payssion_api_key, $pm_id, $amount, $currency,$order_id, $state, payssion_secret_key));
        $api_sig = md5($msg);
        if($api_sig == $notify_sig){
            $order_info = $this->DAO->get_order_by_id($order_id);
            if(!$order_info){
                $msg = '未查询到订单。sign='.$api_sig;
                $this->err_log(var_export($msg,1),'payssion_webhook');
                http_response_code(501);
            }elseif($order_info['status']=='0'){
                $this->DAO->update_payssion_order($pm_id,$transaction_id,$payer_email,$order_id);
                http_response_code(200);
            }else{
                http_response_code(413);
            }
        }else{
            $msg = '签名错误。sign='.$api_sig;
            $this->err_log(var_export($msg,1),'payssion_webhook');
            http_response_code(402);
        }
        return;
    }


    public function paypal_callback_test(){
        $payment_id ='PAY-4SY4739980432422VLG2MYDA' ;
        $ClientID='AaKFNfKPdSHJxVrv-0cgq0ArtzEsp0ceLM4aECf-Cg074HbUFGy64hVe7Q9NxF58WtjkAwGUOBYGmCA4';
        $ClientSecret='EHZDY5u_SBRVewAQMdkGvRvdESdKtW_esVt7rvGA0TXHvxABILDxEr8FfEqB7iMq3In2gCRPkA4kr61T';

        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential($ClientID, $ClientSecret)
        );
        $config = array(
            'mode'=>'LIVE'
        );
        $apiContext->setConfig($config);
        $payment = new \PayPal\Api\Payment();
        try {
            $paypal_info = $payment->get($payment_id,$apiContext);
//            $transactions = $paypal_info->getTransactions();
//            $amount = $transactions[0]->getAmount();
//            $state = $paypal_info->getState();
//            $total = $amount->getTotal();
//            $currency =$amount->getCurrency();
//            $order_status = $transactions[0]->getRelatedResources()[0]->getSale()->state;
            var_dump($paypal_info);
        }
        catch (\PayPal\Exception\PayPalConnectionException $ex) {
            $error = $ex->getData();
            var_dump($error);
        }
    }

    public function charge_order(){
        $this->open_debug();
        $orders = $this->DAO->get_payed_orders();
        if(!$orders){
            die("执行完毕");
        }
        foreach($orders as $k=>$order){
            $timestamp = strtotime("now");
            $url = $order['sdk_charge_url'];
            $id = $order['id'];
            $app_key = $order['app_key'];
            $order_id = $order['order_id'];
            $app_id = $order['app_id'];
            $serv_id = $order['serv_id'];
            $usr_id = $order['buyer_id'];
            $player_id = $order['role_id'];
            $app_order_id = $order['app_order_id'];
            $coin = $order['amount'];
            $money = $order['pay_money'];
            $add_time = $order['buy_time'];
            $good_code = $order['good_code'];
            $payExpandData = $order['payExpandData'];

            $this->DAO->update_order_charge($id);
            $sign_str = md5($app_id.$serv_id.$usr_id.$player_id.$app_order_id.$coin.$money.$add_time.$app_key);
            $post_ary = array("app_id"=>$app_id, "serv_id"=>$serv_id, "usr_id"=>$usr_id, "player_id"=>$player_id,
                "app_order_id"=>$app_order_id, "coin"=>$coin, "money"=>$money, "add_time"=>$add_time,
                "good_code"=>$good_code,"payExpandData"=>$payExpandData, "sign"=>$sign_str, "order_id"=>$order_id);
            $result = $this->request($url, $post_ary);

            $result = json_decode($result);
            if("1" == $result->success){
                $this->DAO->update_order_status($id, 3);
            }
        }
    }

    public function paypal_verify($payment_id,$order){
        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(ClientID, ClientSecret)
        );
        $config = array(
            'mode'=>'LIVE'
        );
        $apiContext->setConfig($config);
        $payment = new \PayPal\Api\Payment();
        $this->payment_id_verify($payment_id);
        try {
            $paypal_info = $payment->get($payment_id,$apiContext);
            $transactions = $paypal_info->getTransactions();
            $amount = $transactions[0]->getAmount();
            $state = $paypal_info->getState();
            $total = $amount->getTotal();
            $currency =$amount->getCurrency();
            $order_status = $transactions[0]->getRelatedResources()[0]->getSale()->state;
            if($state == 'approved' && $order_status == 'completed'){
                if($total == $order['pay_price'] && $currency == $order['currency']){
                    $this->DAO->update_order($order['id'],$payment_id);// 更改为支付成功的状态
                    return;
                }
            }
            $this->err_log(var_export($order,1),"paypal_callback");
            $this->err_log(var_export($paypal_info,1),"paypal_callback");
        }
        catch (\PayPal\Exception\PayPalConnectionException $ex) {
            $error = $ex->getData();
            $this->err_log(var_export($payment_id,1),"paypal_callback_error");
            $this->err_log(var_export($error,1),"paypal_callback_error");

        }
    }

    public function payment_id_verify($payment_id){
        if(empty($payment_id)){
            $this->err_log(var_export($payment_id,1),"paypal_callback");
            return;
        }
    }
}
