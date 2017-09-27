<?php
header("Content-Type: text/html; charset=utf-8");
require_once 'config.php';
COMMON("paramUtils");

BO('scenery_admin');
$act = paramUtils::strByGET("act", false);

$bo = new scenery_admin();
switch ($act){
    case"list":
        $bo->scenery_list();
        break;
}