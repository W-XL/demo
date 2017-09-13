<?php
COMMON('adminBaseCore','pageCore','uploadHelper');
DAO('feedback_admin_dao');

class feedback_admin extends adminBaseCore{
    public $DAO;

    public function __construct(){
        parent::__construct();
        $this->DAO = new feedback_admin_dao();
    }

    public function list_view(){
        if($_POST){
            $_SESSION['params'] = $params = $_POST;
        }elseif(!$_GET['page']){
            unset($_SESSION['params']);
        }else{
            $params = $_SESSION['params'];
        }
        $feedbacks_name = $this->DAO->get_feedback_name();
        $feedbacks = $this->DAO->get_list($this->page,$params);
        $page = $this->pageshow($this->page, "feedback.php?act=list&");
        $this->assign("params",$params);
        $this->assign("feedbacks",$feedbacks_name);
        $this->assign("datalist", $feedbacks);
        $this->assign("page_bar", $page->show());
        $this->display("feedback_list.html");
    }
    public function account_retrieve(){
        $params = $_GET;
        $accout_list = $this->DAO->get_account_retrieve($this->page,$params);
        $page = $this->pageshow($this->page, "feedback.php?act=account_retrieve&email=".$params['email']."&");
        $this->assign("params",$params);
        $this->V->assign("page_bar", $page->show());
        $this->V->assign('datalist', $accout_list);
        $this->V->display("account_retrieve_list.html");
    }
    public function account_edit($id) {
        $info = $this->DAO->get_account_info($id);
        if($info['img_path']) {
            $info['img_path'] = explode("|",$info['img_path']);
        }
        $this->V->assign("info",$info);
        $this->V->display("account_retrieve_edit.html");
    }

    public function do_account_edit($id) {
        $feedback = $_POST;
        if($feedback['status'] == 2 && !$feedback['reply']){
            $this->error_msg("请填写回复内容");
        } else {
            $feedback['operator_id'] = $_SESSION['usr_id'];
            $feedback['operator_time'] = time();
            $this->DAO->update_account($feedback,$id);
            $this->succeed_msg("信息提交成功");
        }
    }

    public function edit_view($id) {
        $info = $this->DAO->get_feedback($id);
        if($info['img_path']) {
            $info['img_path'] = explode("|",$info['img_path']);
        }
        $this->V->assign("info", $info);
        $this->V->display("feedback_edit.html");
    }

    public function do_edit($id){
        if (!$_POST['feedback']) {
            $this->error_msg("请填写回复信息");
        } else {
                $data=array(
                    'feedback'=>$_POST['feedback'],
                    'feedback_usr'=>$_SESSION['usr_id'],
                    'feedback_time'=>date('Y-m-d H:i:s',time())
                );
            $this->DAO->update_feedback($data,$id);
            $this->succeed_msg();
        }
    }


}
