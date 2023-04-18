<?php

namespace App\Http\Services;

use App\Exceptions\CustomJsonException;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use Illuminate\Http\Request;

class RazorpayService
{
    private $razorpay_api = null;

    public function __construct()
    {
       $this->razorpay_api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
    }

    public function generate_order_id(String $receipt, Int $amount): String
    {
        $orderData = [
            'receipt'         => $receipt,
            'amount'          => $amount*100, // 39900 rupees in paise
            'currency'        => 'INR',
            'partial_payment' => false,
        ];

        $razorpayOrder = $this->razorpay_api->order->create($orderData);
        $razorpayOrderId = $razorpayOrder['id'];
        return $razorpayOrderId;
    }

    public function verify_signature(String $razorpay_order_id, String $razorpay_payment_id, String $razorpay_signature): bool{
        try
        {
            $attributes = array(
                'razorpay_order_id' => $razorpay_order_id,
                'razorpay_payment_id' => $razorpay_payment_id,
                'razorpay_signature' => $razorpay_signature,
                'status' => 1,
            );

            $this->razorpay_api->utility->verifyPaymentSignature($attributes);
            return true;
        }
        catch(SignatureVerificationError $e)
        {
            //$error = 'Razorpay Error : ' . $e->getMessage();
            // throw new CustomJsonException('Donation verification failed', 400);
            return false;
        }
    }

    public function verify_webhook(Request $request) : array|null{

        $webhookSignature = $request->header('X-Razorpay-Signature');
        $webhookBody = $request->getContent();
        $data = json_decode($webhookBody);

        if($data->entity === "event" && $data->event === "payment.authorized"){
            $webhookSecret = env('RAZORPAY_WEBHOOK_SECRET');

            try
            {
                $this->razorpay_api->utility->verifyWebhookSignature($webhookBody, $webhookSignature, $webhookSecret);
                return ["payment_id" => $data->payload->payment->entity->id, "order_id" => $data->payload->payment->entity->order_id];
            }
            catch(SignatureVerificationError $e)
            {
                //$error = 'Razorpay Error : ' . $e->getMessage();
                // error_log('failed');
                return null;
            }
        }
        return null;

    }

}
