<?php
header("Content-Type: text/html; charset=utf-8");
require_once 'config.php';
COMMON('paramUtils');
BO("paypal_web");

$bo = new paypal_web();
$bo->err_log(var_export($_POST,1),'paypal_webhook');
$bo->webhook();
