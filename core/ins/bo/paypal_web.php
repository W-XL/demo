<?php

use \PayPal\Api\Payer;
use \PayPal\Api\Item;
use \PayPal\Api\ItemList;
use \PayPal\Api\Details;
use \PayPal\Api\Amount;
use \PayPal\Api\Transaction;
use \PayPal\Api\RedirectUrls;
use \PayPal\Api\Payment;
use \PayPal\Exception\PayPalConnectionException;
use PayPal\Api\PaymentExecution;

COMMON('sdkCore');
DAO('paypal_dao');

class paypal_web extends sdkCore{

    public $DAO;
    public $qa_user_id;
    public $lang;
    public $gameVer;
    public $paypal;

    public function __construct(){
        parent::__construct();
        $this->DAO = new android_pay_dao();
        $oauth = new \PayPal\Auth\OAuthTokenCredential(ClientID, ClientSecret);
        $this->paypal = new \PayPal\Rest\ApiContext($oauth);
        $config = array('mode' => 'live');
        $this->paypal->setConfig($config);
        $this->qa_user_id = array();
        $this->lang = 'en';
        if(isset($_SERVER['HTTP_USER_AGENT1'])){
            $header = base64_decode(substr($_SERVER['HTTP_USER_AGENT1'],1));
            $header = explode("&",$header);
            foreach($header as $k=>$param){
                $param = explode("=",$param);

                if($param[0] == 'sdkver'){
                    $this->sdkver = $param[1];
                }elseif($param[0] == 'channel'){
                    $this->guild_code = $param[1];
                }elseif($param[0] == 'ver'){
                    $this->gameVer = $param[1];
                }
                if($param[0] == 'lang' && $param[1]){
                    $this->lang = strtolower($param[1]);
                }
            }
        }
    }

    public function set_usr_session($key, $data){
        $this->DAO->set_usr_session($key, $data);
    }

    public function get_usr_session($key=''){
        return $this->DAO->get_usr_session($key);
    }

    public function paypal_view(){
        $this->display("paypal_view.html");
    }

    public function paypal_checkout(){

        if (!isset($_POST['product'], $_POST['price'])) {
            die("lose some params");
        }
        $product = $_POST['product'];
        $price = $_POST['price'];

        $total = $price ;
        $shipping = 0;
        $payer = new Payer();
//        credit_card
        $payer->setPaymentMethod('paypal');

        $item = new Item();
        $item->setName($product)
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($price);

        $itemList = new ItemList();
        $itemList->setItems([$item]);

        $details = new Details();
        $details->setShipping($shipping)
            ->setSubtotal($price);

        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($total)
            ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("支付描述内容")
            ->setInvoiceNumber(uniqid());

        $redirectUrls = new RedirectUrls();
//        $redirectUrls->setReturnUrl(SITE_URL . '/pay.php?success=true')
//            ->setCancelUrl(SITE_URL . '/pay.php?success=false');
        $redirectUrls->setReturnUrl(SITE_URL . '/return_url.php?success=true')
            ->setCancelUrl(SITE_URL . '/return_url.php?success=false');


        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions([$transaction]);

        try {
            $create = $payment->create($this->paypal);
            $this->err_log(var_export($create,1),'pp_check');
        } catch (PayPalConnectionException $e) {
            echo $e->getData();
            die();
        }

        $approvalUrl = $payment->getApprovalLink();
        header("Location: {$approvalUrl}");
    }

    public function paypal_pay(){
        $result = array("errcode"=>1,"message"=>"参数不正确");
        $_POST['order_id']=$_GET['order_id'];
        if(!$_POST['order_id']){
            $result['message'] = '订单号异常';
            die("0".base64_encode(json_encode($result)));
        }
        $order_info = $this->DAO->get_order_by_id($_POST['order_id']);
        if(!$order_info){
            $result['message'] = '未查询到订单';
            die("0".base64_encode(json_encode($result)));
        }elseif($order_info['status'] != '0'){
            $result['message'] = '订单状态异常';
            die("0".base64_encode(json_encode($result)));
        }
        $this->paypal_go_pay($order_info);
    }

    public function paypal_go_pay($data){
        if (!isset($data['app_order_id'], $data['pay_price'])) {
            die("lose some params");
        }

        $product = $data['app_order_id'];//暂时用cp订单号代替
        $price = $data['pay_price'];

        $total = $price ;
        $shipping = 0;
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item = new Item();
        $item->setName($product)
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($price);

        $itemList = new ItemList();
        $itemList->setItems([$item]);

        $details = new Details();
        $details->setShipping($shipping)
            ->setSubtotal($price);

        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($total)
            ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("支付描述内容")
            ->setInvoiceNumber(uniqid());

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(SITE_URL . '/return_url.php?success=true')
            ->setCancelUrl(SITE_URL . '/return_url.php?success=false');

        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions([$transaction]);

        try {
            $payment->create($this->paypal);
        } catch (PayPalConnectionException $e) {
            echo $e->getData();
            die();
        }

        $approvalUrl = $payment->getApprovalLink();
        header("Location: {$approvalUrl}");
    }

    public function return_url($paymentId,$PayerID){
        $payment = Payment::get($paymentId, $this->paypal);

        $execute = new PaymentExecution();
        $execute->setPayerId($PayerID);

        try{
            $payment->execute($execute, $this->paypal);
        }catch(Exception $e){
            die($e);
        }
        echo '支付成功！感谢支持!';
    }


    public function webhook(){
        $bodyReceived = file_get_contents('php://input'); // 获取通知的全部内容
        $output = '';
        try {
            $output = \PayPal\Api\WebhookEvent::validateAndGetReceivedEvent($bodyReceived, $this->paypal);
        } catch (\InvalidArgumentException $ex) {
            // This catch is based on the bug fix required for proper validation for PHP. Please read the note below for more details.
            // If you receive an InvalidArgumentException, please return back with HTTP 503, to resend the webhooks. Returning HTTP Status code [is shown here](http://php.net/manual/en/function.http-response-code.php). However, for most application, the below code should work just fine.
            http_response_code(503);
        } catch (Exception $ex) {
            exit(1);
        }
        if($output){
            $result = array();
            $callbackArr = json_decode($bodyReceived, true);
            switch($callbackArr['event_type']){     // 这里做switch处理是因为我的项目中有其它接口也用到了这个webhook，所以，可以根据你的项目处理
                case 'PAYMENT.SALE.COMPLETED':      // 判断是否支付完成
                    $result = $this->eventPaymentSaleComoleted($callbackArr); // 获取支付完成的信息
                    break;
            }
            return $result;
        } else {
            exit;
        }
    }

    public function eventPaymentSaleComoleted($callbackArr){
        $paymentId = $callbackArr['resource']['parent_payment'];
        try {
            $payment = \PayPal\Api\Payment::get($paymentId, $this->paypal);
        } catch (Exception $ex) {
            exit(1);
        }
        $transactions = $payment->getTransactions();
        $relatedResources = $transactions[0]->getRelatedResources();
        $sale = $relatedResources[0]->getSale();
        $saleId = $sale->getId();
        $invoiceNumber = $transactions[0]->invoice_number;
        $result = array(
            'out_trade_no' => $invoiceNumber,
            'total_fee' => $callbackArr['resource']['amount']['total'],
            'api_trade_no' => $saleId,
        );
        return $result;
    }
}
