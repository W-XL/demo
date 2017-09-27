<?php
COMMON('Dao');
class scenery_dao extends Dao{
    public function __construct(){
        parent::__construct();
        $this->mmc = new Memcache();
        $this->mmc->connect(MMCHOST, MMCPORT);
    }

    public function get_list(){

    }
}