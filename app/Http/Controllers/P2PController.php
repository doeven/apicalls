<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pledge;
use App\Models\P2pRule;

// Import Models
use App\Models\Donation;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Models\DonationPack;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PledgeReceiver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class P2PController extends Controller
{
    // List All Peer to Perr Settings Saved for Site
    public static function index()
    {
        $rules = P2pRule::first();
        return $rules;
        
    }

    public function pledge_list($list){
        if($list == 'list'){
            $pledges = Pledge::all();
        }
        elseif($list == 'unmatched'){
            $pledges = Pledge::whereStatus(0)->get();
        }
        elseif($list == 'matched'){
            $pledges = Pledge::whereStatus(1)->get();
        }elseif($list == 'complete'){
            $pledges = Pledge::whereStatus(2)->get();
        }else{
            $pledges = Pledge::all();
        }

        if($pledges->isEmpty()){return 'There are no records.';}
        return $pledges;
    }

    // Make a P2P Pledge
    public function pledge(Request $request){

        // Let's Validate the Deposit Entry
        $validatedData = $request->validate([
            'amount' => 'required',
            'pack' => 'required',
        ], [
            'amount.required' => 'Enter a Valid Amount',
            'pack.required' => 'Select a Valid Pack'
        ]);

        $pack = DonationPack::whereId($validatedData['pack'])->first();

        // Check if the Amount Falls Within that Range
        if($validatedData['amount'] >= $pack->min && $validatedData['amount'] <= $pack->max){
            
            $validatedData['user_id'] = Auth::user()->id;

            // Log into the Database (Deposit Table) this entry
            $deposit = Pledge::create($validatedData);

            if($deposit){
                return "Deposit Pledge Successful. Wait to be matched to make payment.";
            }
        }else{
            return 'The amount entered does not fall within the package range';
        }


    }

    // Ban User Who Hasn't Made a Pledge after 24 Hours Register
    public function no_pledge_block($id){
        no_pledge_block($id);
    }

    // Ban User Who Hasn't Made a Pledge after 24 Hours of Match
    public function no_pop_block($id){
        no_pop_block($id);
    }


    public function donation_entries($list){
        if($list = 'all'){
            $donations = Donation::all();
        }
        elseif($list = 'completed'){ // Transaction Completed
            $donations = Donation::whereStatus(1)->get();
        }
        elseif($list = 'nopop'){ // No POP Uploaded Yet
            $donations = Donation::whereStatus(0)->whereNull('pop')->get();
        }
        elseif($list = 'uploaded'){ // Uploaded POP But Not Activated
            $donations = Donation::whereStatus(0)->whereNull('pop')->get();
        }else{ // Other Entries
            $donations = Donation::all();
        }
        if($donations->isEmpty()){return 'No Data to display';}
        return $donations;
    }

    public function my_pledge_entries($list){
        $user_id = Auth::user()->id;
        $pledges = show_pledges($user_id, $list);
        return $pledges;
    }

    // Method to Upload POP
    public function pop_upload(Request $request){

        // $user = User::whereId(Auth::user()->id)->first();
        $donation = Donation::whereUserId(Auth::user()->id)->whereId($request->donation_id)->first();
        
        // Check if User Uploaded POP Already
        if($donation->pop != NULL){
            return "Your POP Approval Is Pending the Receiver's Approval";
        }

        // Check For Upload Data
        if ($request->hasFile('pop')) {
            
            //  Let's Run Some Validation
            if ($request->file('pop')->isValid()) {
                //
                $validated = $request->validate([
                    'pop' => 'mimes:jpeg,png,jpg|max:2048',
                ]);
                $extensionPop = $request->pop->extension();

                $userData = User::whereid(Auth::user()->id)->first();
                
                $trans = array(" " => "-", ":" => "-");
                $popName = $userData->username.'-'.strtr(now(), $trans);

                $request->pop->storeAs('/user/pop', $popName.'.'.$extensionPop, 'public');
                
                // Assign a Storage URL
                $urlPop = '/user/pop/'.$popName.'.'.$extensionPop;
                
                // Update POP To the DB
                $popUpdate = $donation['pop'] = $urlPop;
                $donation->save();

                if($popUpdate){
                    return 'POP Uploaded Successfully';
                }else{
                    return 'Something went wrong with the upload';
                }
            }
        }
        abort(500, 'Could not upload image :(');
    
    }

    public function confirm_pop(Request $request){
        // Pull the Donation with ID and where I am Receiver
        $donation = Donation::where('to', Auth::user()->id)->whereId($request->donation_id)->whereNotNULL('pop')->first();
        if($donation == NULL){return 'The Donor Hasn\'t Uploaded a POP';}

        // Donor Details
        $donor_user = User::whereId($donation->user_id)->first();

        // Change Status to Confirmed: 1 
        $donation['status'] = 1;
        $donation->save();

        // Create Transaction Entry for the donor
        $tx_data = array();
        $tx_data['user_id'] = $donor_user->id;
        $tx_data['tx_id'] = 'PLTX'.Str::random(7);
        $tx_data['description'] = 'Your Payment was confirmed';
        $tx_data['amount'] = $donation->amount;
        $tx_data['balance'] = 0;
        $tx_data['fee'] = 0;
        $tx_data['type'] = 19;
        
        create_transaction($tx_data); // Enter Data


        // Create Transaction Entry for the receiver
        $tx_data = array();
        $tx_data['user_id'] = Auth::user()->id;
        $tx_data['tx_id'] = 'PLTX'.Str::random(7);
        $tx_data['description'] = 'You confirmed payment receipt';
        $tx_data['amount'] = $donation->amount;
        $tx_data['balance'] = 0;
        $tx_data['fee'] = 0;
        $tx_data['type'] = 9;
        
        create_transaction($tx_data); // Enter Data

        // Add Notification to Donor & Receiver
        // Notification

        $email_address = $donor_user->email;
        $email_subject = 'Your POP Has Been Confirmed';
        $email_message = 'Hi '.$donor_user->fname.'<br/> Yay! Your Uploaded POP has been confirmed.';

        send_email($email_address, $email_subject, $email_message); // Send Email
        create_notification($donor_user->id, 'POP confirmed', 19); // Notification for Donor
        create_notification(Auth::user()->id, 'You confirmed POP', 9); // Notification for Donor

        // Check if that's the last one for the Donor if so, set pledge ID to complete: 2
        $don = Donation::where('pl_id', $donation->pl_id)->whereUserId($donation->user_id)->whereStatus(1)->get();
        $donSum = $don->sum('amount'); // Sum all Approved
        $pledge = Pledge::whereId($donation->pl_id)->first();

        if($donSum >= $pledge->amount){
            // Confirm Pledge as Complete
            $pledge['status'] = 2;
            $pledge->save();
            
            // the package on
            $this_pack = DonationPack::whereId($pledge->pack)->first();


            // Add User to the Pledge Receiver Part
            $dataPlRc = array();
            $dataPlRc['user_id'] = $pledge->user_id;
            $dataPlRc['pack'] = $pledge->pack;
            $dataPlRc['to_receive'] = $pledge->amount + (($pledge->amount*$this_pack->percent)/100);

            PledgeReceiver::create($dataPlRc);

            // Add Notification to Donor & Receiver
            // Notification

            $email_subject_two = 'Your Donation is Complete! What\'s Next?';
            $email_message_two = 'Hi '.$donor_user->fname.'<br/> Yay! You have been staged for receiving payment in the upcoming days.';

            send_email($email_address, $email_subject_two, $email_message_two); // Send Email
            create_notification($donor_user->id, 'Donation Cycle Complete', 26); // Notification

            // Deposit Bonus to Referrer
            $pack = DonationPack::whereId($pledge->pack)->first();
            $bonus = ($pledge->amount/100) * $pack->bonus;
            $ref_id = get_referrer_id($pledge->user_id);

            if($ref_id != 0){
                $ref = User::whereId($ref_id)->first();
                $ref['balance'] = $ref['balance'] + round($bonus, 2);
                $ref->save();
                // Create Transaction Entry
                $tx_data = array();
                $tx_data['user_id'] = $ref_id;
                $tx_data['tx_id'] = 'BNTX'.Str::random(7);
                $tx_data['description'] = 'Bonus return from Pledge referral ('.$ref->username.')';
                $tx_data['amount'] = round($bonus, 2);
                $tx_data['balance'] = $ref['balance'];
                $tx_data['fee'] = 0;
                $tx_data['type'] = 7;
                
                create_transaction($tx_data); // Enter Data
                // Notification

                create_notification($ref_id, 'You got Referral Bonus', 7); // Notification
            }

            
        }

        return 'POP has been confirmed successfully';
    }


    // Update Any Setting

    public function update(Request $request, $id=1)
    {
        //
        $settings = P2pRule::find($id);

        if($settings == NULL){ die('No valid Site Setting Exists');}

        $validatedData = $request->all();

        $result = $settings->fill($validatedData)->save();

        if($result){
            return ["result" => "P2P Settings Updated Successfully"];
        } else{
            return ["result" => "An Error Occured While Updating P2P Settings"];
        }
    }

}
