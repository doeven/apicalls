<?php

namespace App\Http\Controllers;

use Exception;
use App\Lib\BlockIo;
use App\Models\User;
use App\Models\Deposit;
use App\Models\EmailTemplate;
use App\Models\Gateway;
use App\Models\Withdraw;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\WithdrawController;

class PaymentController extends Controller
{

    public function __construct()
    {
        $license = check_license();

        if ($license === 'valid'){
            return 'Valid License Key';
        }else {
            die('Invalid License Key. Contact developer.');
        }
    }

    
    // Function to add Balance to the User Account
    public function activate_callback(){

    }

    // Generate Block.IO Data
    public static function gen_block_io($tx_id){

        $gateway = Gateway::find(1);
        $apiKey = $gateway->val1;
        $pin =  $gateway->val2;
        $block_io = new BlockIo($apiKey, $pin);
        // return $block_io;
        
        $address_response = $block_io->get_new_address(array('label' => $tx_id ));
        $address = json_decode(json_encode($address_response), true);

        return $address['data'];
        
    }

    // Generate Block.IO Address Notification
    public static function create_block_io_address_notification($address){
        // return $address;
        $gateway = Gateway::find(1);
        $apiKey = $gateway->val1;
        $pin =  $gateway->val2;
        $callback= route('callback-block-io');
        $block_io = new BlockIo($apiKey, $pin);
        $block_io->create_notification(array('type' => 'address', 'address' => $address, 'url' => $callback));
    }

    // Generate Block.IO Account Notification
    public static function create_block_io_account_notification(){
        // return $address;
        $gateway = Gateway::find(1);
        $apiKey = $gateway->val1;
        $pin =  $gateway->val2;
        $callback= route('callback-block-io');
        $block_io = new BlockIo($apiKey, $pin);
        $block_io->create_notification(array('type' => 'account', 'url' => $callback));
    }

    // Method to Enable Block.iO Account Verification
    public function block_account_notification(){
        try {
            $check =  $this->create_block_io_account_notification();
              throw new Exception($check);
            }
          catch (Exception $e) {
            echo $e;
          }
    }

    // CoinGate Receive Payment
    public static function coingate($data){
        $gateway = Gateway::find(5); // 5 is CoinGate
        $apiKey = $gateway->val1; // API PIN

        $data_array = array();
        $data_array["title"] = urlencode('Account Topup');
        $data_array["price_amount"] = $data['btc_amount'];
        $data_array["price_currency"] = 'BTC';
        $data_array["receive_currency"] = 'BTC';
        $data_array["callback_url"] = urlencode(route('callback-coingate'));
        $data_array["success_url"] = urlencode($_ENV['CURRENT_SANCTUM_STATEFUL_DOMAINS']."/dashboard?payment=passed");
        $data_array["cancel_url"] = urlencode($_ENV['CURRENT_SANCTUM_STATEFUL_DOMAINS']."/dashboard?payment=failed");
        $data_array["order_id"] = urlencode($data['tx_id']);
        $data_array["description"] = urlencode('Payment deposit to user account.');

        $query = 'title='.$data_array["title"].'&price_amount='.$data_array["price_amount"].'&price_currency='.$data_array["price_currency"].'&receive_currency='.$data_array["receive_currency"].'&callback_url='.$data_array["callback_url"].'&success_url='.$data_array["success_url"].'&cancel_url='.$data_array["cancel_url"].'&order_id='.$data_array["order_id"].'&description='.$data_array["description"];

        $url = "https://api-sandbox.coingate.com/v2/orders?".$query;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
        "Authorization: Bearer ".$apiKey,
        "Content-Type: application/x-www-form-urlencoded; charset=utf-8",
        );
        
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);


        $resp = curl_exec($curl);
        curl_close($curl);
        return $resp;
    }

    // Receive Payment with AlfaCoins
    public static function alfacoin_receive_payment($x){

        $gateway = Gateway::find(4); // 4 is Alfa Coin
        $secret_key = $gateway->val1;
        $password =  $gateway->val2; // Hashed MD5 password
        $app_name =  $gateway->val3; // API App name



        $url = "https://www.alfacoins.com/api/create.json";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
        "Accept: application/json",
        "Content-Type: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data_array = array();
        $data_array["name"] = $app_name;
        $data_array["secret_key"] = $secret_key;
        $data_array["password"] = $password;
        $data_array["amount"] = $x['btc_amount'];
        $data_array["type"] = 'bitcoin';
        $data_array["order_id"] = $x['tx_id'];
        $data_array["currency"] = 'BTC';
        $data_array["status"] = 'paid';
        $data_array["description"] = "User Account Balance Top Up";
        $data_array["options"]['notificationURL'] = route('callback-alfacoins');
        $data_array["options"]['redirectURL'] = $_ENV['CURRENT_SANCTUM_STATEFUL_DOMAINS']."/dashboard?payment=passed";
        $data_array["options"]['payerName'] = 'Site TopUp';
        $data_array["options"]['payerEmail'] = 'random@randommail.com';

        $data_json = json_encode( $data_array );

        $data = <<<DATA
            $data_json
        DATA;

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        return $resp;

    }

    // Method to Send Payment for alfacoins
    public static function alfacoin_send_payment(Array $x){


        $gateway = Gateway::find(4); // 4 is Alfa Coin
        $secret_key = $gateway->val1;
        $password =  $gateway->val2; // Hashed MD5 password
        $app_name =  $gateway->val3; // API App name


        $url = "https://www.alfacoins.com/api/payout.json";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
        "Accept: application/json",
        "Content-Type: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data_array = array();
        $data_array["name"] = $app_name;
        $data_array["secret_key"] = $secret_key;
        $data_array["password"] = $password;
        $data_array["type"] = 'bitcoin';
        $data_array["options"]['address'] = $x['address'];
        $data_array["options"]['test'] = 0;
        $data_array["amount"] = $x['amount'];
        $data_array["recipient_name"] = $x['rec_name'];
        $data_array["recipient_email"] = $x['rec_email'];
        $data_array["reference"] = $x['reference'];

        $data_json = json_encode( $data_array );

        $data = <<<DATA
            $data_json
        DATA;

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        return $resp;
    }




    /********** ALL Callbacks Here */

    // Block.IO Callbacks
    public function callback_block_io(){
        $data = json_decode(file_get_contents('php://input'), true);
        
        if($data['type'] == 'ping'){return 'Ready: OK';}        
        if(empty($data)){return 'Empty Data set';}

        $address = $data['data']['address'];
        $amount_received = $data['data']['amount_received'];
        $deposit = Deposit::whereAddress($address)->whereStatus(0)->first();

        if($deposit != NULL){
            // Check if Amount Paid is Equal to or greater than BTC Equivalent
            if($amount_received >= $deposit->btc_amount){

                    // Update that Donation Status to 1
                    $deposit['status'] = 1;
                    $deposit->save();

                    // Update the User Balance
                    $user = User::whereId($deposit->user_id)->first();
                    $new_balance = $user['balance'] = $user['balance'] + $deposit->amount;
                    $user->save();

                    // Send User Email notification

                    // Enter the Transaction into Transaction
                    $tx_data = array();
                    $tx_data['user_id'] = $user->id;
                    $tx_data['tx_id'] = 'DPTX'.Str::random(7);
                    $tx_data['description'] = 'Fund Deposit';
                    $tx_data['amount'] = $deposit->amount;
                    $tx_data['balance'] = $new_balance;
                    $tx_data['fee'] = $deposit->fee;
                    $tx_data['type'] = 1;
                    
                    create_transaction($tx_data); // Enter Data

                    // Notification

                    $email_address = $user->email;
                    // Get the Template
                    $email_template = EmailTemplate::whereSlug('deposit-confirmed')->first();
                    
                    $email_subject = $email_template->title;

                // Substitution Array
                    $var = array(
                        '%firstname%' => $user->fname,
                        '%username%' => $user->username,
                        '%amount%' => $deposit->amount,
                        '%balance%' => $new_balance,
                    );

                    $email_message = strtr($email_template->body, $var);

                    send_email($email_address, $email_subject, $email_message); // Send Email
                    create_notification($user->id, 'Deposit made on Account', 1); // Notification
                    create_notification(1, 'User Made Deposit', 101); // Admin Notification
            
            }

        }
    }



    // CoinGate Callbacks
    public function callback_coingate(){
        $data = json_decode(file_get_contents('php://input'), true);
        
        if($data['type'] == 'ping'){return 'Ready: OK';}        
        if(empty($data)){return 'Empty Data set';}

        $order_id = $data['order_id'];
        $amount_received = $data['receive_amount'];
        $deposit = Deposit::whereTxId($order_id)->whereStatus(0)->first();

        if($deposit != NULL){
            // Check if Amount Paid is Equal to or greater than BTC Equivalent
            if($amount_received >= $deposit->btc_amount){

                    // Update that Donation Status to 1
                    $deposit['status'] = 1;
                    $deposit->save();

                    // Update the User Balance
                    $user = User::whereId($deposit->user_id)->first();
                    $new_balance = $user['balance'] = $user['balance'] + $deposit->amount;
                    $user->save();

                    // Send User Email notification

                    // Enter the Transaction into Transaction
                    $tx_data = array();
                    $tx_data['user_id'] = $user->id;
                    $tx_data['tx_id'] = 'DPTX'.Str::random(7);
                    $tx_data['description'] = 'Fund Deposit';
                    $tx_data['amount'] = $deposit->amount;
                    $tx_data['balance'] = $new_balance;
                    $tx_data['fee'] = $deposit->fee;
                    $tx_data['type'] = 1;
                    
                    create_transaction($tx_data); // Enter Data

                    // Notification

                    $email_address = $user->email;
                    // Get the Template
                    $email_template = EmailTemplate::whereSlug('deposit-confirmed')->first();
                    
                    $email_subject = $email_template->title;

                // Substitution Array
                    $var = array(
                        '%firstname%' => $user->fname,
                        '%username%' => $user->username,
                        '%amount%' => $deposit->amount,
                        '%balance%' => $new_balance,
                    );

                    $email_message = strtr($email_template->body, $var);

                    send_email($email_address, $email_subject, $email_message); // Send Email
                    create_notification($user->id, 'Deposit made on Account', 1); // Notification
                    create_notification(1, 'User Made Deposit', 101); // Admin Notification
            
            }

        }
    }


    // Alfacoins Callbacks
    public function callback_alfacoins(){

        parse_str(file_get_contents("php://input"), $info);

        //$data = json_decode(file_get_contents('php://input'), true);

        // return $data;
        // Cast it to an object
        // $info = (object)$info;

        $data = json_decode(json_encode($info), true);

        if(empty($data)){return 'Empty Data set';}

        // print_r($data);

        $order_id = $data['order_id'];
        $amount_received = $data['received_amount'];
        $deposit = Deposit::whereTxId($order_id)->whereStatus(0)->first();

        if($deposit != NULL){
            // Check if Amount Paid is Equal to or greater than BTC Equivalent
            if($amount_received >= $deposit->btc_amount){

                    // Update that Donation Status to 1
                    $deposit['status'] = 1;
                    $deposit->save();

                    // Update the User Balance
                    $user = User::whereId($deposit->user_id)->first();
                    $new_balance = $user['balance'] = $user['balance'] + $deposit->amount;
                    $user->save();

                    // Send User Email notification

                    // Enter the Transaction into Transaction
                    $tx_data = array();
                    $tx_data['user_id'] = $user->id;
                    $tx_data['tx_id'] = 'DPTX'.Str::random(7);
                    $tx_data['description'] = 'Fund Deposit';
                    $tx_data['amount'] = $deposit->amount;
                    $tx_data['balance'] = $new_balance;
                    $tx_data['fee'] = $deposit->fee;
                    $tx_data['type'] = 1;
                    
                    create_transaction($tx_data); // Enter Data

                    // Notification

                    $email_address = $user->email;
                    // Get the Template
                    $email_template = EmailTemplate::whereSlug('deposit-confirmed')->first();
                    
                    $email_subject = $email_template->title;

                // Substitution Array
                    $var = array(
                        '%firstname%' => $user->fname,
                        '%username%' => $user->username,
                        '%amount%' => $deposit->amount,
                        '%balance%' => $new_balance,
                    );

                    $email_message = strtr($email_template->body, $var);

                    send_email($email_address, $email_subject, $email_message); // Send Email
                    create_notification($user->id, 'Deposit made on Account', 1); // Notification
                    create_notification(1, 'User Made Deposit', 101); // Admin Notification
            
            }

        }
    }



    // make Auto Payment using AlfaCoins
    public function alfapay_pay_user(Request $request){
        
        $data = array();
        $data['address'] = $request->address;
        $data['amount'] = $request->amount;;
        $data['rec_name'] = 'Payment Mode';
        $data['rec_email'] = 'nofundsreply@xxwhatevertehsiteexamle.com';
        $data['reference'] = $request->reference;
        // return $data;
        $alfapay = $this->alfacoin_send_payment($data);

        $ret_arr = json_decode($alfapay);

        if (property_exists($ret_arr, 'error')) {
            return $ret_arr->error;
        }else{
            // Send Notification and mark user as paid
            WithdrawController::mark_paid($request);
            return 'paid';
        }

    }

    
}
