<?php
COMMON('adminBaseCore','pageCore','uploadHelper');

class user_admin extends adminBaseCore{
    public $DAO;

    public function __construct(){
        parent::__construct();
        $this->DAO = new user_dao();
    }

    public function list_view(){
        if($_POST){
            $_SESSION['user_info'] = $params = $_POST;
        }elseif(!$_GET['page']){
            unset($_SESSION['user_info']);
        }else{
            $params = $_SESSION['user_info'];
        }
        $user_info = $this->DAO->get_user_info_list($params,$this->page);
        $page = $this->pageshow($this->page,'user.php?act=list&');
        $this->assign('page_bar',$page->show());
        $this->assign('params',$params);
        $this->assign('user_info',$user_info);
        $this->display('user_list.html');
    }

}