<?php
COMMON('adminBaseCore','pageCore','uploadHelper');

class qa_admin extends adminBaseCore{
    public $DAO;

    public function __construct() {
        parent::__construct();
        $this->DAO = new qa_dao();
    }

    public function qa_device_view(){
        if($_POST){
            $_SESSION['qa_device'] = $params = $_POST;
        }elseif(!$_GET['page']){
            unset($_SESSION['qa_device']);
        }else{
            $params = $_SESSION['qa_device'];
        }
        $device = $this->DAO->get_qa_device($params,$this->page);
        $apps = $this->DAO->get_apps_list();
        $page = $this->pageshow($this->page,'qa.php?act=device&');
        $this->assign("page_bar", $page->show());
        $this->assign('apps',$apps);
        $this->assign('params',$params);
        $this->assign('datas',$device);
        $this->display("qa_device.html");
    }

    public function qa_role_view(){
        if($_POST){
            $_SESSION['qa_role'] = $params = $_POST;
        }elseif(!$_GET['page']){
            unset($_SESSION['qa_role']);
        }else{
            $params = $_SESSION['qa_role'];
        }
        $role = $this->DAO->get_qa_role($params,$this->page);
        $apps = $this->DAO->get_apps_list();
        $page = $this->pageshow($this->page,'qa.php?act=role&');
        $this->assign("page_bar", $page->show());
        $this->assign('params',$params);
        $this->assign('apps',$apps);
        $this->assign('datas',$role);
        $this->display("qa_role.html");
    }

    public function qa_login_view(){
        if($_POST){
            $_SESSION['qa_login'] = $params = $_POST;
        }elseif(!$_GET['page']){
            unset($_SESSION['qa_login']);
        }else{
            $params = $_SESSION['qa_login'];
        }
        $login = $this->DAO->get_qa_login($params,$this->page);
        $apps = $this->DAO->get_apps_list();
        $page = $this->pageshow($this->page,'qa.php?act=login&');
        $this->assign('page_bar',$page->show());
        $this->assign('apps',$apps);
        $this->assign('datas',$login);
        $this->assign('params',$params);
        $this->display('qa_login.html');
    }

}