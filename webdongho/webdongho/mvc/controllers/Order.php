<?php

class order extends ControllerBase
{
    public function add()
    {
        // if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $order = $this->model("orderModel");
        if (isset($_SESSION['voucher'])) {
            $voucher = $this->model("voucherModel");
            $check = $voucher->used($_SESSION['voucher']['code']);
            if ($check) {
                $result = $order->add($_SESSION['user_id'], $_POST['total'], $_SESSION['voucher']['percentDiscount']);
            } else {
                echo '<script>alert("Mã giảm giá không đúng hoặc số lượng đã hết!");window.history.back();</script>';
                die();
            }
        } else {
            $result = $order->add($_SESSION['user_id'], $_POST['total']);
        }

        if ($result) {
            if ($_POST['paymentMethod'] != "cod") {
                $this->payment($result, $_POST['total'], $_POST['paymentMethod']);
            } else {
                $cart = $this->model("cartModel");
                $cart->deleteCart();
                unset($_SESSION['cart']);
                unset($_SESSION['voucher']);
                $this->redirect("order", "message", [
                    "message" => "success"
                ]);
            }
        } else {
            $this->redirect("order", "message", [
                "message" => "fail"
            ]);
        }
        // }
    }

    public function checkout()
    {
        $order = $this->model("orderModel");
        $result = $order->getByuserId($_SESSION['user_id']);
        // Fetch
        $orderList = $result->fetch_all(MYSQLI_ASSOC);

        $this->view("client/order", [
            "headTitle" => "Đơn đặt hàng của tôi",
            "orderList" => $orderList
        ]);
    }

    public function detail($orderId)
    {
        $orderDetail = $this->model("orderDetailModel");
        $result = $orderDetail->getByorderId($orderId);

        $order = $this->model("orderModel");
        $resultOrder = $order->getById($orderId)->fetch_assoc();
        $status = false;
        if ($resultOrder['status'] == "received") {
            $status = true;
        }
        // Fetch
        $orderDetailList = $result->fetch_all(MYSQLI_ASSOC);
        $this->view("client/orderDetail", [
            "headTitle" => "Chi tiết đơn hàng: " . $orderId,
            "orderId" => $orderId,
            "orderDetailList" => $orderDetailList,
            "status" => $status,
            "paymentStatus" => $resultOrder['paymentStatus'],
            "deliveryStatus" => $resultOrder['status']
        ]);
    }

    public function message($message)
    {
        if ($message == "success") {
            $this->view("client/message", [
                "headTitle" => "Thông báo",
                "message" => "Đặt hàng thành công!",
                "thanks" => "Cuộc sống có nhiều lựa chọn, cảm ơn vì bạn đã chọn và tin tưởng NHÓM 10 STORE!"
            ]);
        } else {
            $this->view("client/message", [
                "headTitle" => "Thông báo",
                "message" => "Đặt hàng thất bại!"
            ]);
        }
    }

    public function returnPayment()
    {
        $order = orderModel::getInstance();
        if (isset($_GET['vnp_TransactionStatus']) && $_GET['vnp_TransactionStatus'] == "00") {
            $result = $order->payment($_GET['orderId'], "VNPay");
        } else if (isset($_GET['resultCode']) && $_GET['resultCode'] == "0") {
            $result = $order->payment($_GET['orderId'], "Momo");
        }
        $this->view("client/returnPayment", [
            "headTitle" => "Thông báo",
        ]);
    }

    public function payment($orderId = "", $amount = "", $paymentMethod = "")
    {
        if (isset($_POST['orderId'])) {
            $orderId = $_POST['orderId'];
        }
        if (isset($_POST['total'])) {
            $amount = $_POST['total'];
        }
        if (isset($_POST['paymentMethod'])) {
            $paymentMethod = $_POST['paymentMethod'];
        }

        $order = $this->model("orderModel");
        $result = $order->getById($orderId);
        $o = $result->fetch_assoc();

        $user = $this->model("userModel");
        $r = $user->getById($_SESSION['user_id']);
        $u = $r->fetch_assoc();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if ($paymentMethod == "vnpay") {

                $vnp_TmnCode = "3304R5EJ"; //Website ID in VNPAY System
                $vnp_HashSecret = "OOSMDHRGUXTNDDQGJTPWOLYDPFXHQMYE"; //Secret key
                $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
                $vnp_Returnurl = URL_ROOT . "/order/returnPayment/?orderId=" . $o['id'] . "&&";
                $vnp_apiUrl = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html";
                //Config input format
                //Expire
                $startTime = date("YmdHis");
                $expire = date('YmdHis', strtotime('+15 minutes', strtotime(date("YmdHis"))));


                error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
                date_default_timezone_set('Asia/Ho_Chi_Minh');

                /**
                 * Description of vnpay_ajax
                 *
                 * @author xonv
                 */

                $vnp_TxnRef = $orderId; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
                $vnp_OrderInfo = "Mo ta";
                $vnp_OrderType = "billpayment";
                $vnp_Amount = $amount * 100;
                $vnp_Locale = "vn";
                $vnp_BankCode = "";
                $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
                //Add Params of 2.0.1 Version
                $vnp_ExpireDate = date('YmdHis', strtotime('+15 minutes', strtotime(date("YmdHis"))));
                //Billing
                $vnp_Bill_Mobile = $u['phone'];
                $vnp_Bill_Email = $u['email'];
                $fullName = trim($u['fullName']);
                if (isset($fullName) && trim($fullName) != '') {
                    $name = explode(' ', $fullName);
                    $vnp_Bill_FirstName = array_shift($name);
                    $vnp_Bill_LastName = array_pop($name);
                }
                $vnp_Bill_Address = $u['address'];
                $vnp_Bill_City = "Cần Thơ";
                $vnp_Bill_Country = "VN";
                $vnp_Bill_State = "";
                // Invoice
                $vnp_Inv_Phone = "0907892198";
                $vnp_Inv_Email = "nguyenvana@gmail.com";
                $vnp_Inv_Customer = "abc";
                $vnp_Inv_Address = "Cần Thơ";
                $vnp_Inv_Company = "Khong";
                $vnp_Inv_Taxcode = "Không";
                $vnp_Inv_Type = "I";
                $inputData = array(
                    "vnp_Version" => "2.1.0",
                    "vnp_TmnCode" => $vnp_TmnCode,
                    "vnp_Amount" => $vnp_Amount,
                    "vnp_Command" => "pay",
                    "vnp_CreateDate" => date('YmdHis'),
                    "vnp_CurrCode" => "VND",
                    "vnp_IpAddr" => $vnp_IpAddr,
                    "vnp_Locale" => $vnp_Locale,
                    "vnp_OrderInfo" => $vnp_OrderInfo,
                    "vnp_OrderType" => $vnp_OrderType,
                    "vnp_ReturnUrl" => $vnp_Returnurl,
                    "vnp_TxnRef" => $vnp_TxnRef,
                    "vnp_ExpireDate" => $vnp_ExpireDate,
                    "vnp_Bill_Mobile" => $vnp_Bill_Mobile,
                    "vnp_Bill_Email" => $vnp_Bill_Email,
                    "vnp_Bill_FirstName" => $vnp_Bill_FirstName,
                    "vnp_Bill_LastName" => $vnp_Bill_LastName,
                    "vnp_Bill_Address" => $vnp_Bill_Address,
                    "vnp_Bill_City" => $vnp_Bill_City,
                    "vnp_Bill_Country" => $vnp_Bill_Country,
                    "vnp_Inv_Phone" => $vnp_Inv_Phone,
                    "vnp_Inv_Email" => $vnp_Inv_Email,
                    "vnp_Inv_Customer" => $vnp_Inv_Customer,
                    "vnp_Inv_Address" => $vnp_Inv_Address,
                    "vnp_Inv_Company" => $vnp_Inv_Company,
                    "vnp_Inv_Taxcode" => $vnp_Inv_Taxcode,
                    "vnp_Inv_Type" => $vnp_Inv_Type
                );

                if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                    $inputData['vnp_BankCode'] = $vnp_BankCode;
                }
                if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
                    $inputData['vnp_Bill_State'] = $vnp_Bill_State;
                }

                //var_dump($inputData);
                ksort($inputData);
                $query = "";
                $i = 0;
                $hashdata = "";
                foreach ($inputData as $key => $value) {
                    if ($i == 1) {
                        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                    } else {
                        $hashdata .= urlencode($key) . "=" . urlencode($value);
                        $i = 1;
                    }
                    $query .= urlencode($key) . "=" . urlencode($value) . '&';
                }

                $vnp_Url = $vnp_Url . "?" . $query;
                if (isset($vnp_HashSecret)) {
                    $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
                    $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
                }
                $returnData = array(
                    'code' => '00', 'message' => 'success', 'data' => $vnp_Url
                );
                header('Location: ' . $vnp_Url);
            } else if ($paymentMethod == "momo") {
                header('Content-type: text/html; charset=utf-8');


                function execPostRequest($url, $data)
                {
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt(
                        $ch,
                        CURLOPT_HTTPHEADER,
                        array(
                            'Content-Type: application/json',
                            'Content-Length: ' . strlen($data)
                        )
                    );
                    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                    //execute post
                    $result = curl_exec($ch);
                    //close connection
                    curl_close($ch);
                    return $result;
                }


                $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

                $partnerCode = 'MOMOPTSL20211228';
                $accessKey = 'DClbzD3PwrAKjqww';
                $serectkey = 'xYX5Ccf9WjLAVzTZzVj9Y3tQV4eLrD4x';
                $orderInfo = "Thanh toán qua MoMo";
                $amount = $amount;
                $ipnUrl = URL_ROOT . '/order/returnPayment/';
                $redirectUrl = URL_ROOT . '/order/returnPayment/';
                $extraData = "";

                $requestId = time();
                $requestType = "captureWallet";
                $extraData = "";
                //before sign HMAC SHA256 signature
                $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
                $signature = hash_hmac("sha256", $rawHash, $serectkey);
                $data = array(
                    'partnerCode' => $partnerCode,
                    'partnerName' => "Test",
                    "storeId" => "MomoTestStore",
                    'requestId' => $requestId,
                    'amount' => $amount,
                    'orderId' => $orderId,
                    'orderInfo' => $orderInfo,
                    'redirectUrl' => $redirectUrl,
                    'ipnUrl' => $ipnUrl,
                    'lang' => 'vi',
                    'extraData' => $extraData,
                    'requestType' => $requestType,
                    'signature' => $signature,
                    'returnUrl' => 'google.com'
                );
                $result = execPostRequest($endpoint, json_encode($data));
                $jsonResult = json_decode($result, true);  // decode json

                //Just a example, please check more in there
                header('Location: ' . $jsonResult['payUrl']);
            }
        } else {
            $this->view("client/payment", [
                "headTitle" => "Thanh toán",
                "order" => $o,
                "user" => $u
            ]);
        }
    }

    public function received($orderId)
    {
        $order = $this->model("orderModel");
        $result = $order->received($orderId);
        if ($result) {
            $this->redirect("order", "checkout");
        }
    }

    public function delete($orderId)
    {
        $order = $this->model("orderModel");
        $result = $order->delete($orderId);
    }

    public function cancel($orderId)
    {
        $order = $this->model("orderModel");
        $result = $order->cancel($orderId);
        if ($result) {
            $this->redirect("order", "checkout");
        }
    }
}
