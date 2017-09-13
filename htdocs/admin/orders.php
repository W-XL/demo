<?php
header("Content-Type: text/html; charset=utf-8");
require_once 'config.php';
COMMON("paramUtils");
//ini_set("display_errors","On");
//error_reporting(E_ALL);

BO('orders_admin');

$act = paramUtils::strByGET("act", false);
$app_id = paramUtils::intByGET("app_id");

$bo = new orders_admin();
switch ($act){
    case"list":
        $bo->order_list_view($app_id);
        break;
    case'export':
        $bo->export();
        break;
}