<?php
/*接口版本：v3*/
header('Content-Type: application/json; charset=utf-8');
require_once 'config.php';
COMMON('paramUtils');
BO('android_pay');

$params = array();
$api = new android_pay();
if(isset($_SERVER['HTTP_USER_AGENT1'])){
    $header = base64_decode(substr($_SERVER['HTTP_USER_AGENT1'],1));
    $header = explode("&",$header);
    foreach($header as $k=>$param){
        $param = explode("=",$param);
        //应用id
        if($param[0] == 'app_id'){
            $params['app_id'] = $param[1];
        }
        //用户id
        if($param[0] == 'user_id'){
            $params['user_id'] = $param[1];
        }
    }
}

if(!$params['user_id'] || !$params['app_id'] ){
    $api->V->assign("msg", "你还没登入账号");
    $api->V->display("error.html");
    exit;
}
try{
    $api->paypal_result($params['app_id']);
}catch (Exception $e){
    $api->err_log(var_export($_POST,1),"paypal_result");
    $api->err_log(var_export($params,1),"paypal_result");
    $api->err_log(var_export($e,1),"paypal_result");
}