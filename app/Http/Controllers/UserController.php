<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\User;
use App\Models\Level;
use App\Models\Donation;
use App\Models\UserCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //

    function index(Request $request)
    {
        $user= User::where('email', $request->email)->first();
        // print_r($data);
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response([
                    'message' => ['These credentials do not match our records.']
                ], 404);
            }
        
             $token = $user->createToken('my-app-token')->plainTextToken;
        
            $response = [
                'user' => $user,
                'token' => $token
            ];
        
             return response($response, 201);
    }


    // Get User Information
    function user_info(){
        $result = Auth::user();
        $result['initials'] = $result->fname[0].$result->lname[0];
        // $result['role'] = $result->getRoles;
        $result->level;
        $levelOn = Level::whereId($result->level['level'])->first();
        $Bank = Bank::whereUserId($result->id)->first();
        $result['level_name'] = $levelOn->name;
        $result['level_max'] = $levelOn->max;
        $result['level_amount'] = $levelOn->amount;
        $result['bank'] = $Bank;
        if($result->two_factor_secret != NULL){$result['twoFA'] = true;}else{$result['twoFA'] = false;}

        $ref = User::whereId($result->referrer)->first();

        if($ref != NULL){
            $result['referrer_id'] = $ref->id;
            $result['referrer_name'] = $ref->username;
            $result['upline_data'] = $ref;
        }else{
            $result['referrer_id'] = 0;
            $result['referrer_name'] = 'No Referrer';
            $result['upline_data'] = NULL;
        }


        return $result;
    }

    // Get User Information by ID
    function user_data($id){
        $result = User::whereId($id)->first();

        $result->level;
        $levelOn = Level::whereId($result->level['level'])->first();
        $result['avvv'] = 'hgrs';
        $result['level_name'] = $levelOn->name;
        $result['level_amount'] = $levelOn->amount;
        $result['level_members'] = $levelOn->max;

        $ref = User::whereId($result->referrer)->first();

        if($ref!= NULL){
            $result['referrer_id'] = $ref->id;
            $result['referrer_name'] = $ref->username;
            $result['upline_data'] = $ref;
        }else{
            $result['referrer_id'] = 0;
            $result['referrer_name'] = 'No Referrer';
            $result['upline_data'] = NULL;
        }

        return $result;
    }

    // Get User Information by ID
    function user_data_public($id){

        $data = User::whereId($id)->first();
        if($data == NULL) {return "An Error Occured. No such User ID";}
        $result = array();
        $result['fname'] = $data->fname;
        $result['lname'] = $data->lname;
        $result['username'] = $data->username;
        
        return $result;
    }

    // Update user Bank Details
    public function bank_update(Request $request){

        $user = User::whereId(Auth::user()->id)->first();

        // Check if the Same User is Logged In
        // if($user->id != $id){ return "You are not permitted to update this record"; }

        $bank_detials = Bank::whereUserId($user->id)->first();

        $bank_detials['acc_num'] = $request->acc_num;
        $bank_detials['acc_name'] = $request->acc_name;
        $bank_detials['bank'] = $request->bank;

        $bank_detials->save();

        return 'Bank Records Updated';

    }
    // Get Logged In User bank Info
    public function bank_info(){
        $bank_data = Bank::whereUserId(Auth::user()->id)->first();
        return $bank_data;
    }
// Update Logged In User
    public function update_user(Request $request)
    {
        $user = User::whereId(Auth::user()->id)->first();

        if($user == NULL){ die('Not a Valid User ID');}

        $balance = $user->balance;
        $kyc = $user->kyc;

        $validatedData = $request->all();

        // Block Any Patch Request to Manipulate KYC and Balance
        $validatedData['balance'] = $balance;
        $validatedData['kyc'] = $kyc;

        $result = $user->fill($validatedData)->save();

        if($result){
            return ["result" => "User Information Saved"];
        } else{
            return ["result" => "An Error Occured While Updating User Data"];
        }
    }

    // Update User By ID
    public function update_user_id(Request $request, $id)
    {
        $user = User::whereId($id)->first();

        if($user == NULL){ die('Not a Valid User ID');}

        $validatedData = $request->all();

        $result = $user->fill($validatedData)->save();

        if($result){
            return ["result" => "User Information Saved"];
        } else{
            return ["result" => "An Error Occured While Updating User Data"];
        }
    }

    // Method to Upload user Profile Photo (Using Form Data)
    public function upload_profile_photo(Request $request){

        $user = User::whereId(Auth::user()->id)->first();

        // Check For Upload Data
        if ($request->hasFile('profile')) {
            
            //  Let's Run Some Validation
            if ($request->file('profile')->isValid()) {
                //
                $validated = $request->validate([
                    'profile' => 'mimes:jpeg,png,jpg|max:2048'
                ]);
                $extensionProfile = $request->profile->extension();

                $userData = User::whereid(Auth::user()->id)->first();

                $trans = array(" " => "-", ":" => "-");
                
                $profileName = $userData->username.strtr(now(), $trans).'profile';
                
                $request->profile->storeAs('/user/profiles', $profileName.'.'.$extensionProfile, 'public');
                
                // Assign a Storage URL
                $urlProfile = '/user/profiles/'.$profileName.'.'.$extensionProfile;
                
                // Update User's Profile Photo
                $user['profile_photo_path'] = $urlProfile;
                $user->save();

                return 'Profile Photo Successfully Uploaded';
            }
        }
        abort(500, 'Could not upload image :(');
        

    }


    // Change Password Logged In User
    function change_pass(Request $request){

        $loggedInUser = User::whereId(Auth::user()->id)->first();
        
        $loggedInPass = $loggedInUser->password;

        $validated = $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
        ]);

        if (Hash::check($request->old_password, $loggedInPass)) {
            $loggedInUser['password'] = Hash::make($request->new_password);
            $loggedInUser->save();
            return "Password Changed";
        }else{
            return "Incorrect Old Password";
        }
        
    }

    function admin_change_password(Request $request, $id){
        $validated = $request->validate([
            'password' => 'required',
        ]);
        $user = User::whereId($id)->first();
        $user['password'] = Hash::make($request->password);
        $user->save();

        return "Password Changed";
    }

    // Get All Users the Logged In User Referred
    function user_referrals(){
        $result = get_referrals(Auth::user()->id);
        return $result;
    }

    // Get Total Depost from All Users the Logged In User Referred
    function user_referrals_deposit(){
        $result = get_referrals_deposit(Auth::user()->id);
        return $result;
    }

    // Get All Users Specified USER ID has Referred
    function user_referrals_by_id($id){
        $result = get_referrals($id);
        return $result;
    }

    // Block Myself or Donor Users Who Has Been matched but uploaded no POP within 24hours
    public static function block_no_pop($user_id){
        // For Logged In user
        $no_pop = Donation::whereNull('pop')->whereUserId($user_id)->get();
        if($no_pop->isNotEmpty()){
            foreach($no_pop as $n){
                no_pop_block($n->user_id);
            }
        }

        // For Donor User
        $no_pop_d = Donation::whereNull('pop')->whereTo($user_id)->get();
        if($no_pop_d->isNotEmpty()){
            foreach($no_pop_d as $d){
                no_pop_block($d->user_id);
            }
        }

        /* // Notification
        $email_address = $donor_user->email;
        $email_subject = 'Account Suspended';
        $email_message = 'Hi, <br/> Your account has been suspended due to not uploading a POP before end of due time.<br/> You can contact support for further details.';

        send_email($email_address, $email_subject, $email_message); // Send Email
        create_notification($donor_user->id, 'POP confirmed', 26); // Notification */

        
    }

    // Block Myself or Donor Users if POP is uploaded but not approved
    public static function block_no_pop_approved($user_id){
        // For Logged In user
        $no_pop_confirmed = Donation::whereNotNull('pop')->whereUserId($user_id)->get();
        if($no_pop_confirmed->isNotEmpty()){
            foreach($no_pop_confirmed as $n){
                no_pop_confirmed_block($n->user_id);
            }
        }

        // For Donor User
        $no_pop_d_confirmed = Donation::whereNotNull('pop')->whereTo($user_id)->get();
        if($no_pop_d_confirmed->isNotEmpty()){
            foreach($no_pop_d_confirmed as $d){
                no_pop_confirmed_block($d->user_id);
            }
        }

        // Notification
        
    }


    // Log User Out
    function logout(){
        Session::remove('user_2fa');
        $result = Auth::logout();
        return $result;
    }

    // Check if a Username is Available
    function get_username_check($username){
        $user = User::whereUsername($username)->first();
        if($user == NULL){return 0;}else{return 1;}
    }

    // Function to add the Login Code Session
    public function login_code_session_set(Request $request){
        $request->validate([
            'code'=>'required',
        ]);
  
        $find = UserCode::where('user_id', auth()->user()->id)
                        ->where('code', $request->code)
                        ->where('updated_at', '>=', now()->subMinutes(2))
                        ->first();
  
        if (!is_null($find)) {
            Session::put('user_2fa', auth()->user()->id);
            return 'Login Successful';
        }
  
        return 'You entered a wrong code';

    }

    public function set_logged_in_session(){
        Session::put('user_2fa', auth()->user()->id);
        return 'Login Successful';
    }


    // Function to check if Login Code Session Exist
    public function login_code_session_check(){
        if( Session::has('user_2fa') ){
            return 'Logged In Session';
        }else{
            return 'Logged Out Session';
        }
        
    }

    // Change User TwoFA (2FA) Type
    public function change_twofa_type(Request $request){
        $user = User::whereId(Auth::user()->id)->first();

        if($request->type == 1){
            $user['twofa_type'] = 1;
            $user->save();
        }else if($request->type == 2){
            $user['twofa_type'] = 2;
            $user->save();
        }
        else{
            $user['twofa_type'] = 0;
            $user->save();
        }
        return '2FA Type Updated successfully';

    }

    // Send 2FA for Email Login Notification
    public function two_fa_email(){
        $user = Auth::user();
        // return $user;
        return User::generateCode($user->id);
        return "Verification Email Sent";
    }

}
