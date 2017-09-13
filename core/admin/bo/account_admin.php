<?php
COMMON('adminBaseCore','pageCore','uploadHelper');
DAO('account_admin_dao','menu_admin_dao','app_admin_dao');

class account_admin extends adminBaseCore{
    public $DAO;

    public function __construct() {
        parent::__construct();
        $this->DAO = new account_admin_dao();
    }

    public function account_list_view(){
        if(!$_SESSION['usr_id']){
            header("location:login.php?act=login");
        }
        //权限判断
        $this->user_permissions($_SESSION['usr_id']);
        if($_SESSION['group_id'] == '1'){
            $list = $this->DAO->get_account_list($this->page,$_GET);
        }else{
            $list = $this->DAO->get_account_by_group($_SESSION['usr_id'],$this->page,$_GET);
        }
        $page = $this->pageshow($this->page, "account.php?act=list&type=".$_GET['type']."&");
        $this->assign("params", $_GET);
        $this->assign("datalist", $list);
        $this->assign("page_bar", $page->show());
        $this->assign("user_id", $_GET["user_id"]);
        $this->display("account_list.html");
    }

    private function user_permissions($user_id){
        $user_info = $this->DAO->get_user_info($user_id);
        if($user_info['group_id']=='1'){
            $this->assign("status", '1');
        }else{
            $this->assign("status",'2');
        }
    }

    public function account_add_view(){
        $groups = $this->DAO->get_groups();
        $this->assign("groups", $groups);
        $this->display("account_add.html");
    }

    public function account_edit_view($id){
        $groups = $this->DAO->get_groups();
        $info = $this->DAO->get($id);
        $this->assign("info", $info);
        $this->assign("groups", $groups);
        $this->display("account_edit.html");
    }

    public function account_pwd_view($id){
        $info = $this->DAO->get($id);
        $this->assign("info", $info);
        $this->display("account_pwd_edit.html");
    }

    public function account_group_view(){
        $info = $this->DAO->get_groups();
        $this->assign("dataList", $info);
        $this->display("account_group_list.html");
    }

    public function account_app_view($id){
        $app_dao = new app_admin_dao();
        $info = $this->DAO->get($id);
        $apps = $app_dao->get_all_app();
        $info['apps'] = explode(",", $info['apps']);
        $this->assign("apps", $apps);
        $this->assign("info", $info);
        $this->display("account_app_edit.html");
    }


    public function account_group_menu_view($id){
        $info = $this->DAO->get_group($id);

        $menu_dao = new menu_admin_dao();
        $menu_list = $menu_dao->get_cate_menu(0);
        foreach ($menu_list as $k=>$v){
            $sub_list = $menu_dao->get_cate_menu($v['id']);
            $menu_list[$k]['sub_list'] = $sub_list;
            foreach ($sub_list as $kk=>$vv){
                $child_list = $menu_dao->get_cate_menu($vv['id']);
                $menu_list[$k]['sub_list'][$kk]['child_list'] = $child_list;
            }
        }
        $modules = explode(",", $info['module']);
        $this->assign("info", $info);
        $this->assign("menu_list", $menu_list);
        $this->assign("modules", $modules);
        $this->display("account_group_menu_edit.html");
    }

    public function account_menu_view($id){
        $info = $this->DAO->get($id);

        $menu_dao = new menu_admin_dao();
        $menu_list = $menu_dao->get_cate_menu(0);
        foreach ($menu_list as $k=>$v){
            $sub_list = $menu_dao->get_cate_menu($v['id']);
            $menu_list[$k]['sub_list'] = $sub_list;
            foreach ($sub_list as $kk=>$vv){
                $child_list = $menu_dao->get_cate_menu($vv['id']);
                $menu_list[$k]['sub_list'][$kk]['child_list'] = $child_list;
            }
        }
        $user_modules = $this->DAO->get_user_modules($id);
        $modules = explode(",", $user_modules['module']);
        $this->assign("info", $info);
        $this->assign("menu_list", $menu_list);
        $this->assign("modules", $modules);
        $this->display("account_menu_edit.html");
    }

    public function do_account_edit($id){
        if(!$_POST['id'] || !$_POST['account'] || !$_POST['real_name']){
            $this->error_msg("缺少必填项");
        }
        $this->DAO->update_account($_POST, $id);
        $this->succeed_msg();
    }

    public function do_account_add(){
        if(!$_POST['user_code'] || !$_POST['account'] || !$_POST['real_name'] || !$_POST['usr_pwd']){
            $this->error_msg("缺少必填项");
        }
        if(preg_match("/[\x7f-\xff]/", $_POST['user_code'])){
            $this->error_msg("代码不能含中文.");
        }
        if($this->DAO->check_account($_POST)){
            $this->error_msg("账号或代码已被使用");
        }
        $_POST['usr_pwd'] = md5($_POST['usr_pwd']);
        $this->DAO->insert_account($_POST);
        $this->succeed_msg();
    }


    public function do_account_password($id){
        if(!$_POST['password'] || !$_POST['re_pwd']){
            $this->error_msg("缺少必填项");
        }

        $this->DAO->update_account_pwd($_POST, $id);
        $this->succeed_msg();
    }

    public function do_account_menu($id){
        if(!$_POST['id']){
            $this->error_msg("缺少必填项");
        }

        $menus = implode(",", $_POST['menu']);
        foreach ($_POST['menu'] as $k=>$v){
            $menu = $this->DAO->get_menu($v);
            if($menu['pid']!=0 && !in_array($menu['pid'], $_POST['menu'])){
                $menus.= ",".$menu['pid'];
            }
        }

        $user_modules = $this->DAO->get_user_modules($id);
        if(!$user_modules){
            $this->DAO->insert_user_permision($id, $menus);
        }else{
            $this->DAO->update_user_permision($id, $menus);
        }
        $this->succeed_msg();
    }

    public function do_account_group_menu($id){
        if(!$_POST['id']){
            $this->error_msg("缺少必填项");
        }
        $menus = implode(",", $_POST['menu']);
        foreach($_POST['menu'] as $k=>$v){
            $menu = $this->DAO->get_menu($v);
            if($menu['pid']!=0 && !in_array($menu['pid'], $_POST['menu'])){
                $menus.= ",".$menu['pid'];
            }
        }
        $group = $this->DAO->get_group_list($id);
        $group_id = "";
        foreach($group as $key=>$data){
            $group_id .= $data['id'].",";
        }
        $this->DAO->update_group_permision($id, $menus,rtrim($group_id,","));
        $this->succeed_msg();
    }

    public function do_account_app($id){
        if(!$_POST['id']){
            $this->error_msg("缺少必填项");
        }
        $apps = implode(",", $_POST['app']);
        $user_info = $this->DAO->get_user_apps($id);
        $this->DAO->update_user_apps($id, $apps);
        $this->succeed_msg();
    }

    public function del_account($id){
        $user_info = $this->DAO->get_user_info($id);
        if(empty($user_info)){
            die("未查询到该账号信息,请联系管理员");
        }
        $this->assign("user_info", $user_info);
        $this->display("account_del_view.html");
    }

    public function do_del(){
        if(!$_POST['id']){
            $this->error_msg("缺少必填项");
        }
        $this->DAO->del_($_POST['id']);
        $this->succeed_msg();
    }


}