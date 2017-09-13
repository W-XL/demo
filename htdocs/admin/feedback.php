<?php
header("Content-Type: text/html; charset=utf-8");
require_once 'config.php';
COMMON("paramUtils");
ini_set("display_errors","on");

BO('feedback_admin');

$act = paramUtils::strByGET("act", false);
$id = paramUtils::intByGET("id");

$bo = new feedback_admin();
switch ($act){
    case "view":
        $bo->list_view();
        break;
    case "edit":
        $bo->edit_view($id);
        break;
    case "do_edit":
        $bo->do_edit($id);
        break;
    case "account_retrieve":
        $bo->account_retrieve();
        break;
    case "account_edit":
        $bo->account_edit($id);
        break;
    case "do_account_edit":
        $bo->do_account_edit($id);
        break;
    default:
        $bo->list_view();
        break;
}