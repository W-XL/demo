<?php
header("Content-Type: text/html; charset=utf-8");
require_once 'config.php';
COMMON('paramUtils');
BO("android_pay_web");

$bo = new android_pay_web();
$bo->err_log(var_export($_POST,1),'payssion_webhook');
$bo->payssion_callback();