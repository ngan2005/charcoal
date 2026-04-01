<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class VNPayService
{
    private $vnp_Url;
    private $vnp_TmnCode;
    private $vnp_HashSecret;
    private $vnp_ReturnUrl;
    private $vnp_Api;

    public function __construct()
    {
        $this->vnp_Url = config('services.vnpay.vnp_Url');
        $this->vnp_TmnCode = config('services.vnpay.vnp_TmnCode');
        $this->vnp_HashSecret = config('services.vnpay.vnp_HashSecret');
        $this->vnp_ReturnUrl = config('services.vnpay.vnp_ReturnUrl');
        $this->vnp_Api = config('services.vnpay.vnp_Api');
    }

    public function createPaymentUrl($orderId, $amount, $orderInfo, $request)
    {
        $vnp_OrderInfo = $orderInfo;
        $vnp_Amount = $amount * 100; // VNPay yêu cầu amount * 100
        $vnp_TxnRef = $orderId . '_' . time(); // unique transaction reference
        $vnp_IpAddr = $request->ip();
        $vnp_CreateDate = date('YmdHis');

        $inputData = [
            'vnp_Version' => '2.1.0',
            'vnp_Command' => 'pay',
            'vnp_TmnCode' => $this->vnp_TmnCode,
            'vnp_Amount' => $vnp_Amount,
            'vnp_CreateDate' => $vnp_CreateDate,
            'vnp_CurrCode' => 'VND',
            'vnp_IpAddr' => $vnp_IpAddr,
            'vnp_Locale' => 'vn',
            'vnp_OrderInfo' => $vnp_OrderInfo,
            'vnp_OrderType' => 'other',
            'vnp_ReturnUrl' => $this->vnp_ReturnUrl,
            'vnp_TxnRef' => $vnp_TxnRef,
        ];

        ksort($inputData);
        $query = [];
        $hashdata = '';
        $i = 0;

        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . '=' . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . '=' . urlencode($value);
                $i = 1;
            }
            $query[] = urlencode($key) . '=' . urlencode($value);
        }

        $vnp_SecureHash = hash_hmac('sha512', $hashdata, $this->vnp_HashSecret);
        $query[] = 'vnp_SecureHash=' . $vnp_SecureHash;
        $vnpUrl = $this->vnp_Url . '?' . implode('&', $query);

        return $vnpUrl;
    }

    public function handleReturn($request)
    {
        $vnp_HashSecret = $this->vnp_HashSecret;
        $inputData = [];
        $returnData = [];

        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == 'vnp_') {
                $inputData[$key] = $value;
            }
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);

        ksort($inputData);
        $hashdata = '';
        $i = 0;

        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . '=' . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . '=' . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);

        $vnp_TxnRef = $inputData['vnp_TxnRef'] ?? '';
        $orderIdParts = explode('_', $vnp_TxnRef);
        $orderId = $orderIdParts[0] ?? null;

        $returnData['success'] = ($secureHash == $vnp_SecureHash);
        $returnData['order_id'] = $orderId;
        $returnData['vnp_ResponseCode'] = $inputData['vnp_ResponseCode'] ?? '';
        $returnData['vnp_TransactionStatus'] = $inputData['vnp_TransactionStatus'] ?? '';
        $returnData['vnp_TxnRef'] = $vnp_TxnRef;
        $returnData['vnp_Amount'] = $inputData['vnp_Amount'] ?? '';
        $returnData['vnp_BankCode'] = $inputData['vnp_BankCode'] ?? '';

        return $returnData;
    }

    public function updateOrderPayment($orderId, $responseCode, $transactionStatus, $bankCode = null, $txnRef = null)
    {
        $paymentStatus = ($responseCode == '00' && $transactionStatus == '00') ? 'paid' : 'failed';
        $paymentMethod = 'vnpay';

        DB::table('orders')
            ->where('OrderID', $orderId)
            ->update([
                'PaymentStatus' => $paymentStatus,
                'PaymentMethod' => $paymentMethod,
                'PaymentTransactionRef' => $txnRef,
                'PaymentBankCode' => $bankCode,
                'PaymentCompletedAt' => ($paymentStatus == 'paid') ? now() : null,
                'UpdatedAt' => now(),
            ]);

        return $paymentStatus;
    }
}
