<?php

namespace App\Http\Controllers;

use App\Lib\CoinPaymentsAPI;
use App\Models\EmailTemplate;
use App\Models\User;
use App\Models\Gateway;
use App\Models\Withdraw;
use App\Models\Investment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isEmpty;

class WithdrawController extends Controller
{
    // Let's Withdraw : Withdraw Request
    public function withdraw(Request $request){

        $site_settings = settings();

        // Check if Use has verified their KYC
        if(Auth::user()->kyc == 0){
            return "You have to Verify your account by uploading your KYC";
        }
        
        // Let's Validate the Deposit Entry
            $validatedData = $request->validate([
                'amount' => 'required',
                'fee' => 'required',
                'method_name' => 'required',
                'detail' => 'required'
            ], [
                'amount.required' => 'Amount should be entered',
                'fee.required' => 'A fee is to be charged',
                'method_name.required' => 'Select Payment Method',
                'detail.required' => 'Kindly update your BTC Address on your profile settings page'
            ]);

        
        // Check If User Has a Transaction
        if($site_settings->rinv == 1){

            $inv = Investment::whereUserId(Auth::user()->id)->whereStatus(1)->get();
            if( $inv->isEmpty() || $inv == null ){
                return "You need an Ongoing/Active Investment in order to Withdraw. Kindly visit the Investment page to activate an Investment.";
            }

        }

        // Return Error if amount doesn't meet minimum withdrawal
        if($validatedData['amount'] < $site_settings->min_wd){
            return "The minimum withdrawal limit is ".$site_settings->symbol.$site_settings->min_wd;
        }
 
        
        
        // Get User Data
            $thisUser = User::whereId( Auth::user()->id )->first();

            // Kill Script if user Doesn't Exist
                if( $thisUser == NULL ){die('Invalid User ID');}

        // If User Balance is Higher than Withdraw Amount

        if ( $thisUser['balance'] > $validatedData['amount'] ) {
            
                // Generate the Withdrawal ID
                        $wd_id = 'WDTX'.Str::random(7);
                        $validatedData['wd_id'] = $wd_id;
                        $validatedData['user_id'] = $thisUser->id;

                // Log into the Database (Withdraw Table) this entry
                        $withdraw = Withdraw::create($validatedData);
                
                // Subtract from User Balance
                $thisUser['balance'] = $thisUser['balance'] - $validatedData['amount'];
                $thisUser->save();

                // Send Notification to Current User

                // Return the Payment Data to the Front End
                if($withdraw){
                    return 'Withdraw Request Successful';
                }

                // Notification

                $email_address = $thisUser->email;
                $email_subject = 'You Requested a Withdrawal';
                $email_message = 'Hi '.$thisUser->fname.'<br/> This email is a confirmation that you requested withdrawal from your account.';

                create_notification($thisUser->id, 'You Requested Withdrawal', 11); // Notification
                create_notification(1, 'User Requested Withdrawal', 111); // Create Admin Notification
                send_email($email_address, $email_subject, $email_message); // Send Email

            }
        // Otherwise
        else{
                return "Insufficient balance";
            }

    }

    // All Withdraws
    public function withdraws($filter='all'){

        // Get User Data
        $new = array();
        $counter = 0;

        switch ($filter) {
            case 'all':
                $withdraws = Withdraw::whereIn('status', [0,1])->orderBy('id', 'DESC')->paginate(10);        
                break;
            case 'paid':
                $withdraws = Withdraw::whereStatus(1)->orderBy('processing_time', 'DESC')->paginate(10);
                break;
            case 'unpaid':
                $withdraws = Withdraw::whereIn('status', [0])->orderBy('id', 'DESC')->paginate(10);
                break;
            default:
            $withdraws = Withdraw::whereIn('status', [0,1])->orderBy('id', 'DESC')->paginate(10);                

        }
        if($withdraws->isEmpty()){return NULL;}
        // Let's Get the UserData
        foreach($withdraws as $m){
            $new[$counter] = $m;
            $new[$counter]['user'] = $m->user;
            $result[$counter] = $new[$counter];
            $counter++;
        } 

        return $withdraws;


    }

    // All Withdraws By Logged In User
    public function mine($filter){

        switch ($filter) {
            case 'all':
                $withdraws = Withdraw::whereUserId(Auth::user()->id)->orderBy('id', 'DESC')->paginate(10);        
                break;
            case 'paid':
                $withdraws = Withdraw::whereUserId(Auth::user()->id)->whereStatus(1)->orderBy('processing_time', 'DESC')->paginate(10);
                break;
            case 'unpaid':
                $withdraws = Withdraw::whereUserId(Auth::user()->id)->whereIn('status', [0])->orderBy('id', 'DESC')->paginate(10);
                break;
            default:
            $withdraws = Withdraw::whereUserId(Auth::user()->id)->orderBy('id', 'DESC')->paginate(10);                

        }
        
        return $withdraws;
    }

    // Delete or Approve Withdrawals
    public function update_withdraws(Request $request){
        
        if($request->option == 'delete'){
            $delete = Withdraw::whereId($request->id)->first();
            if($delete == NULL){return "Invalid Withdraw ID Sent";}
            
            // Return Amount to User
            $user = User::whereId($delete->user_id)->first();
            if($user == NULL){return "User no longer exists";}

            $user->balance += ($delete->amount + $delete->fee);
            $user->save();
            
            $delete->delete(); // Delete the Entry

            return "Withdrawal Successfully Deleted";
        }
        else if($request->option == 'pay'){
            
            return $this->mark_paid($request);   
        }

        else if($request->option == 'unpay'){
            $paid = Withdraw::whereId($request->id)->first();
            if($paid == NULL){return "Invalid Withdraw ID Sent";}
            $paid['status'] = 0;
            $paid->save();
            return "Withdrawal successfully marked as UNPAID";    
        }
        
    }

    // Mass Withdrawal with CoinPayment
    public function mass_withdraws(){
        $user = Auth::user();

        $wd = array();

        $currency1 = 'BTC';
        $currency2 = 'USD';

        if($user->id != 1){
            die('You do not have the rights to access this URL');
        }

        // Loop through the Withdrawals to Get their ID 
        $unpaid = Withdraw::whereStatus(0)->get();

        foreach($unpaid as $each){
            $wd[$each['wd_id']]['amount'] = $each['amount'];
            $wd[$each['wd_id']]['address'] = $each['detail'];
            $wd[$each['wd_id']]['currency'] = $currency1;
            $wd[$each['wd_id']]['currency2'] = $currency2;
        }


        // Connect to the API
        $gateway = Gateway::find(3);
        $private_key = $gateway->val1; // Private Keys
        $public_key =  $gateway->val2; // Public Keys
        $cp = new CoinPaymentsAPI();
        $cp->Setup($private_key, $public_key);

        // return $wd;
        // $return = $cp('create_mass_withdrawal', $wd);
        
        // If Payment Has been sent
        foreach($unpaid as $paidUser){
            WithdrawController::mark_paid($paidUser);
        }

        return 'Payments Made';
        
    }

    // Transfer to Fund to other Users
    public function transfer(Request $request){

        $site_settings = settings();

        // Check if Use has verified their KYC
        if(Auth::user()->kyc == 0){
            return ["result" => "You have to Verify your account by uploading your KYC", "status"=> "error"];
        }
        
        // Let's Validate the Deposit Entry
            $validatedData = $request->validate([
                'amount' => 'required',
                'username' => 'required'
            ], [
                'amount.required' => 'Amount should be entered',
                'username.required' => 'Enter a Username'
            ]);

        
        // Return Error if amount is greater than user balance
        if($validatedData['amount'] > Auth::user()->balance){
            return ["result" => "You have entered an amount higher than your account balance. Enter a lower amount", "status"=> "error"];
        }

        // Get User Data
            $thisUser = User::whereId( Auth::user()->id )->first();

            // Kill Script if user Doesn't Exist
                if( $thisUser == NULL ){return ["result" => "Invalid User ID", "status"=> "error"];}

        // Let's Find the Receiver Account
            $recUser = User::whereUsername($validatedData['username'])->first();
                if( $recUser == NULL ){return ["result" => "Invalid Receiver Username", "status"=> "error"];}

        // User Cannon Transfer  Money to themself
        if($recUser->id == $thisUser->id){return ["result" => "A user cannot transfer funds to themself", "status"=> "error"];}

        // If User Balance is Higher than Withdraw Amount

        if ( $thisUser['balance'] >= $validatedData['amount'] ) {

            // Subtract from User Balance
                $thisUser['balance'] = $thisUser['balance'] - $validatedData['amount'];
                $thisUser->save();

                // Add to Receiving User Balance (Receiver)
                $recUser['balance'] = $recUser['balance'] + $validatedData['amount'];
                $recUser->save();
            
                // Generate the Transfer ID
                        $wd_id = 'TRTX'.Str::random(7);
                        $validatedData['wd_id'] = $wd_id;
                        $validatedData['user_id'] = $thisUser->id;

                // Log into the Database (Withdraw Table) this entry

                        // Create Sender Transaction Entry
                        $sender_tx_data = array();
                        $sender_tx_data['user_id'] = $thisUser->id;
                        $sender_tx_data['tx_id'] = $wd_id;
                        $sender_tx_data['description'] = 'Transferred fund to "'.$recUser->username.'"';
                        $sender_tx_data['amount'] = $validatedData['amount'];
                        $sender_tx_data['balance'] = $thisUser->balance;
                        $sender_tx_data['fee'] = 0;
                        $sender_tx_data['type'] = 20;
                        create_transaction($sender_tx_data); // Create the Transaction Entry

                        // Create Receiver Transaction Entry
                        $rec_tx_data = array();
                        $rec_tx_data['user_id'] = $recUser->id;
                        $rec_tx_data['tx_id'] = $wd_id;
                        $rec_tx_data['description'] = 'Receieved fund from "'.$thisUser->username.'"';
                        $rec_tx_data['amount'] = $validatedData['amount'];
                        $rec_tx_data['balance'] = $recUser->balance;
                        $rec_tx_data['fee'] = 0;
                        $rec_tx_data['type'] = 10;
                        $transfer = create_transaction($rec_tx_data); // Create the Transaction Entry
                
                

                // Send Notification to Current User

                
                // Notification

                // Sender Notification
                $email_address = $thisUser->email;
                $email_subject = 'Fund Transfer from your Account';
                $email_message = 'Hi '.$thisUser->fname.'<br/> This email is a confirmation that you transferred fund to another user on your account. Log in and check transactions for more details.';

                create_notification($thisUser->id, 'Transferred Fund', 20); // Notification
                create_notification(1, 'User Transferred Fund', 200); // Create Admin Notification
                send_email($email_address, $email_subject, $email_message); // Send Email

                // Receiver Notification
                $rec_email_address = $recUser->email;
                $rec_email_subject = 'Fund Received to your Account';
                $rec_email_message = 'Hi '.$recUser->fname.'<br/> This email is a confirmation that you got fund transferred to you from '.$thisUser->fname.'. Log in and check transactions for more details.';

                create_notification($recUser->id, 'Received Fund', 10); // Notification
                send_email($rec_email_address, $rec_email_subject, $rec_email_message); // Send Email

                // Return the Payment Data to the Front End
                if($transfer){
                    return ["result" => "Fund Transfer to '".$validatedData['username']."' was Successful", "status"=> "success"];
                }




            }
        // Otherwise
        else{
            return ["result" => "Insufficient balance", "status"=> "error"];
            }

    }
    
    // Mark as Paid method
    public static function mark_paid($request){

        $paid = Withdraw::whereId($request->id)->first();
            if($paid == NULL){return "Invalid Withdraw ID Sent";}
            $paid['status'] = 1;
            $paid->save();
            
            $thisUser = User::whereId($paid->user_id)->first();

            // Enter the Transaction into Transaction
            $tx_data = array();
            $tx_data['user_id'] = $thisUser->id;
            $tx_data['tx_id'] = 'WDTX'.Str::random(7);
            $tx_data['description'] = 'Withdrawal Approved';
            $tx_data['amount'] = $paid->amount;
            $tx_data['balance'] = $thisUser->balance;
            $tx_data['fee'] = $paid->fee;
            $tx_data['type'] = 11;
            
            create_transaction($tx_data); // Enter Data

            // Notification

                $email_address = $thisUser->email;


                 // Get the Template
                 $email_template = EmailTemplate::whereSlug('withdrawal-successful')->first();
                    
                 $email_subject = $email_template->title;

             // Substitution Array
                 $var = array(
                     '%firstname%' => $thisUser->fname,
                 );

                 $email_message = strtr($email_template->body, $var);
                 
                send_email($email_address, $email_subject, $email_message); // Send Email
                create_notification($thisUser->id, 'Withdrawal Payment Sent', 11); // Notification
            

            return "Withdrawal successfully marked as PAID"; 

    }
    

}
