<?php
COMMON('sdkCore','class.phpmailer', 'class.smtp','uploadHelper');
DAO('feedback_dao');

class feedback extends sdkCore{
    public $DAO;
    private $language = 'en';
    protected $l10n_msg;

    public function __construct(){
        parent::__construct();
        $this->DAO = new feedback_dao();
    }

    public function set_language($language){
        $this->language = $language;
        $this->l10n_msg = $this->i18n[$language];
        $this->V->assign("language", $language);
        $this->V->assign("l10n_msg", $this->l10n_msg);
    }

    public function view($appid, $user_id, $osver, $gamever, $net, $mtype, $osname, $logintype, $sdkver){
        //把用户id和游戏id写到memcached下
        $this->set_usr_session('user', array('appid' => $appid, 'usr_id' =>$user_id, 'gamever'=>$gamever, 'net'=>$net,
            'mtype'=>$mtype, 'osname'=>$osname, 'osver'=>$osver, 'logintype'=>$logintype,
            'sdkver'=>$sdkver, 'language' => 'cn'));

        $qa = $this->DAO->get_fag($appid);

        $info = $this->get_home_info();
        $this->V->assign('appid', $appid);
        $this->V->assign('user_id', $user_id);
        $this->V->assign('info', $info);
        $notice = $this->DAO->get_notice_by_appid($this->usr_params['pid']);
        $this->V->assign('qa', $qa);
        $this->V->assign('notice', $notice);
        $this->V->assign('user_id',$this->usr_params['usr_id']);
        $this->V->assign('appid',$this->usr_params['pid']);
        $this->V->display('help/feedback.html');

    }

    public function problem_feedback($osname,$language) {
        if(empty($this->usr_params['usr_id']) || empty($this->usr_params['pid'])){
            $this->show_error("无法获取用户信息,请重新登录");
            exit();
        }
        $problem_type=array(
            1=>$this->i18n[$language]['feedback']['Other'],//其他问题
            2=>$this->i18n[$language]['feedback']['AccountProblems'],//账号问题
            3=>$this->i18n[$language]['feedback']['RechargingProblems'],//充值问题
            4=>$this->i18n[$language]['feedback']['GamingIssues'],//游戏问题
        );
        $user_game_info = $this->DAO->get_my_games($this->usr_params['usr_id'], $this->usr_params['pid']);
        $this->V->assign('system',$osname);
        $this->V->assign('services',$user_game_info);
        $this->V->assign('type', $problem_type);
        $this->V->display('help/problem_feedback.html');
    }

    public function account_retrieve($osname,$uuid,$appid) {
        $info = $this->DAO->get_uuid_info($uuid);
        $this->V->assign('info',$info);
        $this->V->assign('uuid',$uuid);
        $this->V->assign('system',$osname);
        $this->V->assign('appid',$appid);
        $this->V->display('help/account_retrieve.html');
    }

    public function account_submit(){
        $data = $_POST;
        $data['creation_time']= strtotime($data['creation_time']);
        $data['last_time']= strtotime($data['last_time']);
        if(empty($data['other'])){
            $data['other']="";
        }
        $data['img_path'] = "";
        //图片处理
        $img_path = $this->upload_img();
        if (!empty($img_path) && $img_path) {
            $imgs = implode("|", $img_path);
            $data['img_path'] = $imgs;
        }
        $params = $this->usr_params;
        $data['appid'] = $params['pid'];

        $id = $this->DAO->insert_account_back($data);
        if (!$id) {
            die(json_encode(array("result" => 0,"desc" => 'Network request error,Please resubmit！')));
        }
        die(json_encode(array("result" => 1,"desc" => 'success')));
    }

    public function problem_submit(){
        $user = $this->get_usr_session('user');

        if(!empty($user)){
            $params = array_merge($this->usr_params,$user);
            $data = array_merge($_POST,$params);
        }else{
            $params=$this->usr_params;
            $data['appid'] = $params['pid'];
            $data['gamever'] =$params['ver'];
            $data['mtype'] = $params['devicetype'];
            $data['osname'] = "";
            $data = array_merge($_POST,$this->usr_params,$data);
        }
        $data['img_path']="";
        $img_path = $this->upload_img();
        if(!empty($img_path) && $img_path){
            $imgs = implode("|", $img_path);
            $data['img_path'] = $imgs;
        }
        $msg="";
        if ($data['type'] == '' || empty($data['type'])) {
            $msg= "请选择问题类型!!";
        }
        if ($data['server'] == '' || empty($data['server'])) {
            $msg= "请选择服务器!!";
        }
        if ($data['content'] == ''|| empty($data['content'])) {
            $msg= "请填写描述内容!!";
        }
        if(!empty($msg)){
            die(json_encode(array("result" => 0,"desc" => $msg)));
        }
        $id = $this->DAO->insert_feedback($data);
        if(!$id) {
            die(json_encode(array("result" => 0,"desc" => 'Network request error,Please resubmit！')));
        }
        echo json_encode(array(
            "result" => 1,
            "desc" => 'success'
        ));
    }

    public function question_detail($id, $read_status,$language) {
        if($read_status == 0) {
            $this->DAO->update_question_status($id, $this->usr_params['usr_id'], $this->usr_params['pid']);
        }
        $info = $this->DAO->get_feedback_info($id);
        if(!$info['app_name']){
            $info['app_name'] = $this->DAO->get_app_name($info['appid']);
        }
        if($info['img_path']) {
            $info['imgs'] = explode('|', $info['img_path']);
        }
        unset($info['img_path']);
        $info['type_name'] = $this->translate_type($info['type'],$language);
        $this->V->assign('info',$info);
        $this->V->display('help/question_detail.html');
    }

    public function translate_type($type,$language){
        switch($type){
            case 1:
                return $this->i18n[$language]['feedback']['Other'];
            case 2:
                return $this->i18n[$language]['feedback']['AccountProblems'];
            case 3:
                return $this->i18n[$language]['feedback']['RechargingProblems'];
            case 4:
                return $this->i18n[$language]['feedback']['GamingIssues'];
            default:
                return $this->i18n[$language]['feedback']['Other'];
        }
    }

    private function upload_img(){
        $image_count = count($_FILES);
        if ($image_count > 5) {
            echo json_encode(array(
                "status" => 0,
                "msg" => 'Picture can not be more than 5'
            ));
            exit();
        }
        $img_path = array();
        for ($i = 0; $i < $image_count; $i++) {
            $img_name = "image" . $i;
            $type = trim($_FILES[$img_name]['type']);
            if (!in_array($type, array('image/jpeg', 'image/jpg', 'image/gif', 'image/png'))) {
                echo json_encode(array(
                    "result" => 0,
                    "desc" => "Image format error"
                ));
                exit();
            }
            if ($_FILES[$img_name]['tmp_name']) {
                $upload = new uploadHelper($img_name, 'images/service', 1, "image/gif|image/jpg|image/jpeg|image/png");
                $upload->upload();
            }

            if ($upload->has_err == 1) {
                echo json_encode(array(
                    "result" => 0,
                    "desc" => $upload->get_err_msgs()
                ));
                exit();
            }
            $img_path = array_merge($img_path, $upload->rel_files_path);
        }
        $ses_name = $this->usr_params['usr_id'] . "_up_imgs";
        $_SESSION[$ses_name] = $img_path;
        //存入成功
        return $img_path;
    }

    public function load_more_record($appid,$user_id,$page, $num=5) {
        $info = $this->more_home_info($appid,$user_id,$page, $num);
        echo json_encode($info);
    }

    private function more_home_info($appid, $user_id,$page = 0, $num=5) {
        $items = $this->DAO->get_my_problem($appid , $user_id, $page, $num);
        $has_more = 1;
        if(count($items) < $num) {
            $has_more = 0;
        }
        return array(
            'has_more' => $has_more,
            'items' => $items
        );
    }


    private function get_home_info($page = 0, $num=5) {
        $items = $this->DAO->get_my_problem($this->usr_params['pid'], $this->usr_params['usr_id'], $page, $num);
        $has_more = 1;
        if(count($items) < $num) {
            $has_more = 0;
        }
        return array(
            'has_more' => $has_more,
            'items' => $items
        );
    }

    public function show_error($msg){
        $this->V->assign("msg", $msg);
        $this->V->display("error.html");
    }

    public function set_usr_session($key, $data){
        $this->DAO->set_usr_session($key, $data);
    }

    public function get_usr_session($key=''){
        return $this->DAO->get_usr_session($key);
    }
}