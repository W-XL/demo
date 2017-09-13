<?php
header("Content-Type: text/html; charset=utf-8");
require_once 'config.php';
COMMON('paramUtils');
BO("paypal_web");

$bo = new paypal_web();
$success = paramUtils::strByGET('success',false);
$paymentId = paramUtils::strByGET('paymentId',false);
$PayerID = paramUtils::strByGET('PayerID',false);
if($success=='true'){
    $bo->return_url($paymentId,$PayerID);
}elseif($success=='false'){
    die("error");
}
