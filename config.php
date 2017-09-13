<?php
//数据库地址
define("DBHOST", "127.0.0.1");
define("DBPORT", "3306");
define("READ_DBHOST", "127.0.0.1");
define("READ_DBPORT", "3306");
define("DBUSER", "root");
define("DBPWD", "123456");
define("DBNAME", "niuniu");
define("MMCHOST", "127.0.0.1");
define("MMCPORT", "11211");
define("NIUNIU_DBNAME", "niuniu");
//define('REDIS_HOST', '10.10.96.209');
//define('REDIS_PORT', '6379');
//define("NIUNIU_DBNAME", "niuniu");
//define("ES_HOST", "120.132.63.146");
//define("ES_PORT", "9200");
//define("ES_INDEX", "sdk_log");
//图片服务器URL前辍

//网站网址
define("SITEURL", $_SERVER['HTTP_HOST']);
//路径符号，win和linux会有区别
define("DS", DIRECTORY_SEPARATOR);
//网站物理路径
define('PREFIX', realpath(dirname(__FILE__)));
//常用类文件夹
define("COMMON", PREFIX.DS."common".DS);
//业务类文件夹
define("BO", PREFIX.DS."core".DS.APP.DS."bo".DS);
//数据操作类文件夹
define("DAO", PREFIX.DS."core".DS.APP.DS."dao".DS);
//模板文件夹
define("VIEW", PREFIX.DS."core".DS.APP.DS."view".DS);
//缓存文件夹
define("CACHE", PREFIX.DS."cache".DS);
//表单验证
define("VALIDATOR", PREFIX.DS."core".DS.APP.DS."validator".DS);
//bean
define("BEAN", PREFIX.DS."core".DS.APP.DS."bean".DS);
define("AES_IV", "1236547887654123");
//if(FALSE === strpos($_SERVER['HTTP_HOST'],'kuyoo.com')){
//    define("ALIPAY_EMAIL","crab17173@163.com");
//    define("ALI_partner", "2088911899697331");
//    define("ALI_key", "t1ek1l0mt2b7clnp383ugj5zt597ldtn");
//    define("ALI_notify_url", "http://charge.66173.cn/ali_notify.php");
//}else{
//    define("ALIPAY_EMAIL","hcl@kuyoo.com");
//    define("ALI_partner", "2088801294214641");
//    define("ALI_key", "ih6r1db02j0dhqd0akd0166ofitjeqaa");
//    define("ALI_notify_url", "http://charge.kuyoo.com/ali_notify.php");
//}
//define("ALI_sign_type", strtoupper('MD5'));
//define("ALI_input_charset", strtolower('utf-8'));
//define("ALI_cacert", PREFIX.DS."htdocs".'\\cacert.pem');
//define("ALI_transport", "http");
//
//define("YEEPAY_M_ID", "10012462534");
//define("YEEPAY_M_KEY", "fCq2y3rqsw2L7AX1n5387328TiWTqGE3hkv94kgS0DI6VF124T3FCsbMfzs4");
//define("QQ_APP_ID", "101227332");
//define("QQ_APP_KEY", "8b5a29be5cca2e9f87affc60999100e7");
//define("KY_QQ_APP_ID", "101359700");
//define("KY_QQ_APP_KEY", "0f46f174da48262f6740cca424f8a633");
//define("SMS_APP_ID", "aaf98f894d328b13014d659cf55723c5");
//define("SMS_APP_TOKEN", "7279f6081ac14997b7fcb5fb77b33a48");
//define("P7881_ID", "117199584");
//define("P7881_KEY", "cfa40a081ad45eaa1f8bb947fb192932");
//define("P7881_SEC", "8740300410273e9d1865e8493ebe31fc");
//define("pKAMEN_ID", "802917");
//define("pKAMEN_KEY", "F9BB2777920805ADA05E8E0713C1B3C2");
//define("pKAMEN_NOTIFY_URL", "http://charge.66173.cn/kamen_notify.php");
//define("pHENGJING_NOTIFY_URL", "http://charge.66173.cn/hengjing_notify.php");
//define("pHENGJING_KEY", "brsqkrbqcjcwiac");
//define("NIUBI_KEY", "7759218ed5075e4dc1702e4d39d7f787");
//define("LEBA_KEY", "a53651b4f58f7c8606cf83ec56ac1b66");
//ini_set('session.use_trans_sid', 0); //设置垃圾回收最大生存时间
ini_set('session.use_cookies', 1);
ini_set('session.cookie_path', '/'); //多主机共享保存 SESSION ID 的 COOKIE
//ini_set('session.save_path', "/tmp");

ini_set('session.gc_maxlifetime',36600);
//ini_set("session.save_handler", "memcache");
//ini_set("session.save_path", "tcp://10.10.96.209:11211");
ini_set("display_errors","On");
error_reporting(E_ALL);
error_reporting(0);
//error_reporting(E_ALL);
////先访问的页面做设置
session_start();
require 'vendor/autoload.php';
spl_autoload_register('core_autoloader', false, true);