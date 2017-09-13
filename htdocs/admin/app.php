<?php
header("Content-Type: text/html; charset=utf-8");
require_once 'config.php';
COMMON("paramUtils");

BO('app_admin');
$act = paramUtils::strByGET("act", false);

$bo = new app_admin();
switch ($act){
    case"list":
        $bo->app_list_view();
        break;
    case"add":
        $bo->app_add_view();
        break;
    case"edit":
        $id = paramUtils::intByGET("id", false);
        $bo->app_edit_view($id);
        break;
    case"edit_notice":
        $id = paramUtils::intByGET("id", false);
        $bo->app_notice_edit_view($id);
        break;
    case"version_edit":
        $id = paramUtils::intByGET("id", false);
        $bo->version_edit($id);
        break;
    case "version_update":
        $id = paramUtils::intByGET("id", false);
        $bo->version_update($id);
        break;
    case "do_edit":
        $id = paramUtils::intByGET("id", false);
        $bo->do_app_edit($id);
        break;
    case "do_edit_notice":
        $id = paramUtils::intByGET("id", false);
        $bo->do_app_notice_edit($id);
        break;
    case "do_add":
        $bo->do_app_add();
        break;
    default:
        $bo->app_list_view();
        break;
}