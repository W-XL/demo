<?php
COMMON('adminBaseCore','pageCore','uploadHelper');
DAO('scenery_dao');
class scenery_admin extends adminBaseCore{
    public $DAO;

    public function __construct(){
        parent::__construct();
        $this->DAO = new scenery_dao();
    }

    public function scenery_list(){
        $this->display("scenery_list.html");
    }
}