<?php
header("Content-Type: text/html; charset=utf-8");
require_once 'config.php';
COMMON("paramUtils");
ini_set("display_errors","off");


BO('account_admin');

$act = paramUtils::strByGET("act", false);

$bo = new account_admin();
switch ($act){
    case"list":
        $bo->account_list_view();
        break;
    case"add":
        $pid = paramUtils::intByGET("id");
        $bo->account_add_view();
        break;
    case"edit":
        $id = paramUtils::intByGET("id", false);
        $bo->account_edit_view($id);
        break;
    case"password":
        $id = paramUtils::intByGET("id", false);
        $bo->account_pwd_view($id);
        break;
    case "do_edit":
        $id = paramUtils::intByGET("id", false);
        $bo->do_account_edit($id);
        break;
    case "do_add":
        $bo->do_account_add();
        break;
    case "do_password":
        $id = paramUtils::intByGET("id", false);
        $bo->do_account_password($id);
        break;
    case"menu":
        $id = paramUtils::intByGET("id", false);
        $bo->account_menu_view($id);
        break;
    case"group_menu":
        $id = paramUtils::intByGET("id", false);
        $bo->account_group_menu_view($id);
        break;
    case "do_menu":
        $id = paramUtils::intByGET("id", false);
        $bo->do_account_menu($id);
        break;
    case "do_group_menu":
        $id = paramUtils::intByGET("id", false);
        $bo->do_account_group_menu($id);
        break;
    case "groups":
        $bo->account_group_view();
        break;
    case"app":
        $id = paramUtils::intByGET("id", false);
        $bo->account_app_view($id);
        break;
    case"do_app":
        $id = paramUtils::intByGET("id", false);
        $bo->do_account_app($id);
        break;
    case"del":
        $id = paramUtils::intByGET("id", false);
        $bo->del_account($id);
        break;
    case"do_del":
        $bo->do_del();
        break;
    default:
        $bo->account_list_view();
        break;
}