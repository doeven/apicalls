<?php

namespace App\Http\Controllers;


use Illuminate\Support\Str;
use App\Http\Controllers\PaymentController;
//Models//Models
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Deposit;
use App\Models\Gateway;

class DepositController extends Controller
{

    // List All Transactions on the Site
    public static function index($filter)
    {
        // Get User Data
        $new = array();
        $counter = 0;
        
        // all,deposit,withdraw,bonus,lend,admin
        switch ($filter) {
            case 'all':
                $deposits = Deposit::orderBy('id', 'DESC')->paginate(10);         
                break;
            case 'complete':
                $deposits = Deposit::whereStatus(1)->orderBy('id', 'DESC')->paginate(10);         
                break;
            case 'pending':
                $deposits = Deposit::whereStatus(0)->orderBy('id', 'DESC')->paginate(10);         
                break;
            case '':
                $deposits = Deposit::orderBy('id', 'DESC')->paginate(10);         
                break;  
            default:
                $getUser = User::whereUsername($filter)->first();
                if($getUser != NULL){
                    $deposits = Deposit::where('user_id', '=', $getUser->id)->orderBy('id', 'DESC')->paginate(10);
                }else{
                    $deposits = Deposit::orderBy('id', 'DESC')->paginate(10);   
                }
                break;
        }

        if($deposits->isEmpty()){return NULL;}
        // Let's Get the UserData
        foreach($deposits as $m){
            $new[$counter] = $m;
            $new[$counter]['user'] = $m->user;
            $deposits[$counter] = $new[$counter];
            $counter++;
        } 

        return $deposits;  
        
    }


    // Let's Make a Deposit
    public function deposit(Request $request){

        // Let's Validate the Deposit Entry
            $validatedData = $request->validate([
                'amount' => 'required',
                'gateway_id' => 'required',
            ], [
                'amount.required' => 'Enter a Valid Amount',
            ]);

            // Let's get the Gateway Details
            $gateway = Gateway::whereId($request->gateway_id)->first();
            // $gateway = Gateway::whereId(5)->first();

            $rates = file_get_contents("https://blockchain.info/ticker");
                $res = json_decode($rates);
                $btc_rate = $res->USD->last;

                $amount = $request->amount;

                $btc_amount = $request->amount/$btc_rate;
                $btc_final_amount = round($btc_amount, 8); // The BTC Value to Deposit

                $one = $amount + $gateway->fixed_fee; // Check Fixed Fee + Amount
                $two = ($amount * $gateway->perc_fee)/100; // Check Percentage

                $fee = $gateway->fixed_fee + ($two); // Total Fee Charged

                $total_amount = $amount+$fee; // Amount in USD Charged
                $total_usd = $total_amount/$gateway->exchange; // Total USD Based on the Site's Internal Exchange Rate (default is 1)
                $payable_btc = round($total_usd/$btc_rate, 8); // user will pay this amount of BTC


        // Generate the BTC Address and Transaction ID
                $tx_id = 'DPTX'.Str::random(7);
        
                // check if Block.io
                if($gateway->id == 1){

                    // Sample  Data 
                    $sampleData = TRUE;

                    // Get the URL Domain
                    $getHost = $request->getHost();

                    if($getHost == 'localhost' || $getHost == '127.0.0.1') {
                        $sampleData = TRUE;
                    } 
                    else {
                        $sampleData = FALSE;
                    }

                    if($sampleData){
                        $validatedData['address'] = '1FfmbHfnpaZjKFvyi1okTjJJusN455paPH';

                    }else{

                        // Generate Address
                        $gen_address = PaymentController::gen_block_io($tx_id);
                
                        // Add Webhook Notification for the Callback (per address if enabled)
                        if($gateway->val3 == 1){
                            $notification = PaymentController::create_block_io_address_notification($gen_address['address']);
                        }

                        $validatedData['address'] = $gen_address['address'];

                    }                    

                }

                
        
                $validatedData['user_id'] = Auth::user()->id;
                $validatedData['gateway_id'] = $gateway->id;
                $validatedData['amount'] = $amount;
                $validatedData['tx_id'] = $tx_id;
                $validatedData['btc_amount'] = $payable_btc;
                $validatedData['fee'] = $fee;

                // check if AlfaCoins
                if($gateway->id == 4){

                    // Send to Payment Controller
                    $alfacoins = PaymentController::alfacoin_receive_payment($validatedData);
                    $data_array_work = json_decode($alfacoins, true);
                    
                    $validatedData['address'] = $data_array_work['deposit']['address'];
                    $validatedData['alfacoins'] = $data_array_work;

                }
                // check if CoinGate
                if($gateway->id == 5){

                    // Send to Payment Controller
                    $coingate = PaymentController::coingate($validatedData);
                    $data_array_work = json_decode($coingate, true);
                    
                    $validatedData['address'] = 'CoinGate';
                    $validatedData['coingate'] = $data_array_work;

                }

        // Log into the Database (Deposit Table) this entry
                $deposit = Deposit::create($validatedData);

        // Add more data to the validated array
                $validatedData['total'] = $total_usd;
                $validatedData['fixed_fee'] = $gateway->fixed_fee;
                $validatedData['perc_fee'] = $two;
                $validatedData['percentage'] = $gateway->perc_fee;

        // Return the Payment Data to the Front End
        return $validatedData;


    }
}
