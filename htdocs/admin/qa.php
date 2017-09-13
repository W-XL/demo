<?php
header("Content-Type: text/html; charset=utf-8");
require_once 'config.php';
COMMON("paramUtils");
ini_set("display_errors","On");

BO('qa_admin');

$act = paramUtils::strByGET("act", false);

$bo = new qa_admin();
switch ($act) {
    case"pay":
        $bo->qa_pay_view();
        break;
    case"device":
        $bo->qa_device_view();
        break;
    case"role":
        $bo->qa_role_view();
        break;
    case"login":
        $bo->qa_login_view();
        break;
}