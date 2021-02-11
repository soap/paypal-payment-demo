<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Omnipay\Omnipay;

class PaymentController extends Controller
{
 
    public $gateway;
 
    public function __construct()
    {
        $this->gateway = Omnipay::create('PayPal_Rest');
        if (config('paypal.mode') == 'sandbox') {

            $this->gateway->initialize([
                'clientId' => config('paypal.sandbox.client_id'),
                'secret' => config('paypal.sandbox.secret'),
                'testMode' => true
            ]);
        }else {
            $this->gateway->initialize([
                'clientId' => config('paypal.live.client_id'),
                'secret' => config('paypal.live.secret'),
                'testMode' => false
            ]);
        }    
    }
 
    public function index()
    {
        return view('payment');
    }
 
    public function charge(Request $request)
    {
        if($request->input('submit'))
        {
            try {
                $response = $this->gateway->purchase(array(
                    'amount' => $request->input('amount'),
                    'currency' => config('paypal.currency'),
                    'returnUrl' => url('paymentsuccess'),
                    'cancelUrl' => url('paymenterror'),
                ))->send();
          
                if ($response->isRedirect()) {
                    $response->redirect(); // this will automatically forward the customer
                } else {
                    // not successful
                    return $response->getMessage();
                }
            } catch(Exception $e) {
                return $e->getMessage();
            }
        }
    }
 
    public function payment_success(Request $request)
    {
        // Once the transaction has been approved, we need to complete it.
        if ($request->input('paymentId') && $request->input('PayerID'))
        {
            $transaction = $this->gateway->completePurchase(array(
                'payer_id'             => $request->input('PayerID'),
                'transactionReference' => $request->input('paymentId'),
            ));
            $response = $transaction->send();
         
            if ($response->isSuccessful())
            {
                // The customer has successfully paid.
                $arr_body = $response->getData();
         
                // Insert transaction data into the database
                $isPaymentExist = Payment::where('payment_id', $arr_body['id'])->first();
         
                if(!$isPaymentExist)
                {
                    $payment = new Payment;
                    $payment->payment_id = $arr_body['id'];
                    $payment->payer_id = $arr_body['payer']['payer_info']['payer_id'];
                    $payment->payer_email = $arr_body['payer']['payer_info']['email'];
                    $payment->amount = $arr_body['transactions'][0]['amount']['total'];
                    $payment->currency = config('paypal.currency');
                    $payment->payment_status = $arr_body['state'];
                    $payment->save();
                }
         
                return "Payment is successful. Your transaction id is: ". $arr_body['id'];
            } else {
                return $response->getMessage();
            }
        } else {
            return 'Transaction is declined';
        }
    }
 
    public function payment_error()
    {
        return 'User is canceled the payment.';
    }
 
    public function checkout()
    {
        $paypal_js = 'https://www.paypal.com/sdk/js?client-id=' . config('paypal.sandbox.client_id') . '&currency=USD';
        return view('paypal.checkout', compact('paypal_js')); 
    }
}
