<?php
COMMON('adminBaseCore','pageCore','uploadHelper');
DAO('app_admin_dao');

class app_admin extends adminBaseCore{
    public $DAO;

    public function __construct() {
        parent::__construct();
        $this->DAO = new app_admin_dao();
    }

    public function app_list_view(){
        if($_POST){
            $_SESSION['app_list'] = $params = $_POST;
        }elseif(!$_GET['page']){
            unset($_SESSION['app_list']);
        }else{
            $params = $_SESSION['app_list'];
        }
        $list = $this->DAO->get_app_list($this->page,$params);
        $applist = $this->DAO->get_app_name();
        $page = $this->pageshow($this->page, "app.php?act=list&");
        $this->assign("app_list",$applist);
        $this->assign("params",$params);
        $this->assign("datalist", $list);
        $this->assign("page_bar", $page->show());
        $this->display("app_list.html");
    }

    public function app_add_view(){
        $this->display("app_add.html");
    }

    public function app_edit_view($id){
        $info = $this->DAO->get($id);
        $this->assign("info", $info);
        $this->display("app_edit.html");
    }

    public function app_notice_edit_view($id){
        $info = $this->DAO->get($id);
        $this->assign("info", $info);
        $this->display("app_notice.html");
    }

    public function version_edit($id){
        $info = $this->DAO->get($id);
        $this->assign("info", $info);
        $this->display("app_update_view.html");
    }

    public function do_app_edit($id){
        if(!$_POST['app_id'] || !$_POST['app_key'] || !$_POST['app_name']){
            $this->error_msg("缺少必填项");
        }
        if(!$_FILES['app_icon']['tmp_name']){
            $_POST['app_icon'] = $_POST['old_img'];
        }else{
            $_POST['app_icon']=$this->up_img('app_icon',"images/66game",array(),1,1,$id,0);
        }
        if(strtotime($_POST['version_time'])>0 && !$_POST['version_url']){
            $this->error_msg("请填写下载URL");
        }
        $this->DAO->update_app($_POST, $id);
        $this->succeed_msg();
    }

    public function version_update($id){
        if(!$_POST['version']){
            $this->error_msg("请填写版本号。");
        }
        if(!$_POST['up_title']){
            $this->error_msg("请填写更新标题。");
        }
        if(!$_POST['up_desc']){
            $this->error_msg("请填写更新内容。");
        }
        $this->DAO->version_update($_POST, $id);
        $this->succeed_msg();
    }

    public function do_app_notice_edit($id){
        if($_POST['notice_status']==1 && !$_POST['notice']){
            $this->error_msg("请填写公告");
        }
        $this->DAO->update_app_notice($_POST, $id);
        $this->succeed_msg();
    }

    public function do_app_add(){
        if(!$_POST['app_id'] || !$_POST['app_name']){
            $this->error_msg("缺少必填项");
        }
        if($this->DAO->check_app_id($_POST['app_id'])){
            $this->error_msg("APP ID已经被使用");
        }
        $app_key = md5(strtotime("now").rand(1,100000));

        $app_id = $this->DAO->insert_app($_POST,$app_key);

        if($_FILES['app_icon']['tmp_name']){
            $img = $this->up_img('app_icon',GAME_ICON,array(),1,1,$app_id,0);
            $this->DAO->update_app_icon($img, $app_id, $app_key);
        }
        $this->succeed_msg();
    }
}