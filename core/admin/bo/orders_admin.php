<?php
COMMON('adminBaseCore','pageCore','uploadHelper');
DAO('orders_admin_dao');

class orders_admin extends adminBaseCore{
    public $DAO;

    public function __construct() {
        parent::__construct();
        $this->DAO = new orders_admin_dao();
    }

    public function order_list_view($app_id){
        $app_dao = new app_admin_dao();
        if($_POST){
            $_SESSION['order_list'] = $params = $_POST;
        }elseif(!$_GET['page']){
            unset($_SESSION['order_list']);
        }else{
            $params = $_SESSION['order_list'];
        }
        $money = $this->DAO->get_sum_money($params);
        $list = $this->DAO->get_order_list($this->page,$params);
        $url="orders.php?act=list&";
        $page = $this->pageshow($this->page,$url);
        $this->assign("datalist", $list);
        $this->assign("params", $params);
        $this->assign("money", $money[0]['money']);
        $this->assign("apps", $app_dao->get_user_apps());
        $this->assign("page_bar", $page->show());
        $this->assign("app_id", $app_id);
        $this->display("order_list.html");
    }

    public function app_edit_view($id){
        $info = $this->DAO->get($id);
        $this->assign("info", $info);
        $this->display("app_edit.html");
    }

    public function do_app_edit($id){
        if(!$_POST['app_id'] || !$_POST['app_key'] || !$_POST['app_name']){
            $this->error_msg("缺少必填项");
        }
        if(!$_FILES['app_icon']['tmp_name']){
            $_POST['app_icon'] = $_POST['old_img'];
        }else{
            $_POST['app_icon']=$this->up_img('app_icon',GAME_ICON,array(),1,1,$id,0);
        }
        $this->DAO->update_app($_POST, $id);
        $this->succeed_msg();
    }

    public function export(){
        set_time_limit(0);
        $params = $_GET;
        $dataList  = $this->DAO->get_order_list_nolimit($params);
        if($dataList){
            $this->master_excel_out($dataList);
        }else{
            echo "没有数据需要导出！";
        }
    }

    private function master_excel_out($data){
        set_time_limit(0);
        $str_now=date("Y-m-d H:i:s",strtotime("now"));
        $objExcel = new PHPExcel();
        $objExcel->setActiveSheetIndex(0);
        $objActSheet = $objExcel->getActiveSheet();
        $objActSheet->setTitle("充值数据");
        $objActSheet->setCellValue("A1", "游戏ID");
        $objActSheet->setCellValue("B1", "游戏名");
        $objActSheet->setCellValue("C1", "游戏区服ID");
        $objActSheet->setCellValue("D1", "充值ID");
        $objActSheet->setCellValue("E1", "账号");
        $objActSheet->setCellValue("F1", "66订单号");
        $objActSheet->setCellValue("G1", "cp订单号");
        $objActSheet->setCellValue("H1", "商品");
        $objActSheet->setCellValue("I1", "金额");
        $objActSheet->setCellValue("J1", "购买人ID");
        $objActSheet->setCellValue("K1", "渠道");
        $objActSheet->setCellValue("L1", "订单状态");
        $objActSheet->setCellValue("M1", "下单日期");
        $objActSheet->setCellValue("N1", "时间");
        $objActSheet->setCellValue("O1", "支付日期");
        $objActSheet->setCellValue("P1", "时间");
        $objActSheet->setCellValue("Q1", "方式");
        $n = 2;
        foreach($data as $info){
            $objActSheet->setCellValue("A".$n, $info['app_id']);
            $objActSheet->setCellValue("B".$n, $info['app_name']);
            $objActSheet->setCellValue("C".$n, $info['serv_id']);
            $objActSheet->setCellValue("D".$n, "'".$info['role_id']);
            $objActSheet->setCellValue("E".$n, "'".$info['payer']);
            $objActSheet->setCellValue("F".$n, "'".$info['order_id']);
            $objActSheet->setCellValue("G".$n, "'".$info['app_order_id']);
            $objActSheet->setCellValue("H".$n, $info['title']);
            $objActSheet->setCellValue("I".$n, $info['pay_money']);
            $objActSheet->setCellValue("J".$n, $info['buyer_id']);
            $objActSheet->setCellValue("K".$n, $info['pay_channel']);
            if($info['status'] == 0){
                $objActSheet->setCellValue("L".$n, "未付款");
            }elseif($info['status'] == 1){
                $objActSheet->setCellValue("L".$n, "已付款");
            }elseif($info['status'] == 2){
                $objActSheet->setCellValue("L".$n, "完成订单");
            }else{
                $objActSheet->setCellValue("L".$n, "取消订单");
            }
            $objActSheet->setCellValue("M".$n, date("Y-m-d",$info['buy_time']));
            $objActSheet->setCellValue("N".$n, date("H:i:s",$info['buy_time']));
            $objActSheet->setCellValue("O".$n, date("Y-m-d",$info['pay_time']));
            $objActSheet->setCellValue("P".$n, date("H:i:s",$info['pay_time']));
            $objActSheet->setCellValue("Q".$n, $info['payee_ch']);
            $n++;
        }
        $title = iconv("UTF-8", "GB2312//IGNORE","充值数据-".$str_now.'.xls');
        header("Content-type: text/html;charset=utf-8");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$title.'"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
        $objWriter->save('php://output');
    }


}