<?php
header("Content-Type: text/html; charset=utf-8");
require_once 'config.php';
COMMON('paramUtils');
BO('feedback');
ini_set("display_errors","On");

$bo = new feedback();
$act = paramUtils::strByGET('act');

$appid = 1000;
$user_id = 0;
$language = 'en';

if(isset($_SERVER['HTTP_USER_AGENT1'])){
    $header = base64_decode(substr($_SERVER['HTTP_USER_AGENT1'],1));
    $header = explode("&",$header);
    foreach($header as $k=>$param){
        $param = explode("=",$param);

        //应用id
        if($param[0] == 'app_id'){
            $appid = $param[1];
        }
        //用户id
        if($param[0] == 'user_id'){
            $user_id = $param[1];
        }
        //系统固件版本
        if($param[0] == 'osver'){
            $osver = $param[1];
        }
        //游戏版本
        if($param[0] == 'ver'){
            $gamever = $param[1];
        }

        //游戏网络
        if($param[0] == 'net'){
            $net = $param[1];
        }
        //所用设备
        if($param[0] == 'mtype'){
            $mtype = $param[1];
        }
        //系统名
        if($param[0] == 'dt'){
            $osname = $param[1];
        }
        //登录类型
        if($param[0] == 'mode'){
            $logintype = $param[1];
        }

        if($param[0] == 'sdktype'){
            $sdktype = $param[1];
        }
        //设备标识
        if($param[0] == 'sid'){
            $uuid = $param[1];
        }
        //sdk版本
        $sdkver = '';                   //有的版本没有sdk头选项，防止出错
        if($param[0] == 'sdkver'){
            $sdkver = $param[1];
        }
        if($param[0] == 'lang' && $param[1]){
            $lang = strtolower($param[1]);
        }
    }
}
if($lang){
    $language = $lang;
}
$bo->set_language($language);
switch ($act){
    case 'problem_feedback':
        $bo->problem_feedback($osname,$language);
        break;
    case 'account_retrieve':
        $bo->account_retrieve($osname, $uuid, $appid);
        break;
    case 'account_submit':
        $bo->account_submit();
        break;
    case 'problem_submit':
        $bo->problem_submit();
        break;
    case 'load_more_record':
        $bo->load_more_record(paramUtils::intByPOST('appid'), paramUtils::intByPOST('user_id'),paramUtils::intByPOST('page'), paramUtils::intByPOST('num') ? paramUtils::intByPOST('num') : 5);
        break;
    case 'question_detail':
        $bo->question_detail(paramUtils::intByGET('id'), paramUtils::intByGET('read_status'),$language);
        break;
    default :
        if(!$user_id){
            $data = $bo->get_usr_session('user');
            if(!$data){
                $bo->show_error("无法获取用户信息,请重新登录");
            }else{
                $bo->view($data['appid'], $data['usr_id'], $data['osver'],$data['gamever'],$data['net'],$data['mtype'],$data['osname'],$data['logintype'],$data['sdkver']);
            }
        }else{
            $bo->view($appid, $user_id, $osver, $gamever, $net,$mtype, $osname, $logintype,$sdkver);
        }
        break;
}