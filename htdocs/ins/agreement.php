<?php
header("Content-Type: text/html; charset=utf-8");
require_once 'config.php';
COMMON('paramUtils');
BO('feedback');
$bo = new feedback();
$lang = 'en';
if(isset($_SERVER['HTTP_USER_AGENT1'])){

    $header = base64_decode(substr($_SERVER['HTTP_USER_AGENT1'],1));
    $header_data = explode("&",$header);
    foreach($header_data as $k=>$param){
        $param = explode("=",$param);
        if($param[0] == 'lang'){
            $lang = strtolower($param[1]);
        }
    }
    $bo->V->display("agreement_".$lang.".html");
}else{
    $bo->V->display("agreement_en.html");
}