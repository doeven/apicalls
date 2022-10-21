<?php

namespace App\Http\Controllers;

use App\Models\Kyc;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KYCController extends Controller
{   
    // Method to Upload user KYC
    public function upload(Request $request){

        $user = User::whereId(Auth::user()->id)->first();
        $kycUser = Kyc::whereUserId(Auth::user()->id)->first();
        
        // Check if User has been verified 
        if($user->kyc == 1){
            return "User Already Verified";
        }

        // Check if User has Uploaded KYC 
        if($kycUser != NULL && $kycUser->status == 0 ){
            return "You verification details is pending review";
        } else if($kycUser != NULL && $kycUser->status == 2 ){
            $kycUser->delete();
        }

        // Check For Upload Data
        if ($request->hasFile('front') && $request->hasFile('back')) {
            
            //  Let's Run Some Validation
            if ($request->file('front')->isValid() && $request->file('back')->isValid()) {
                //
                $validated = $request->validate([
                    'type' => 'required',
                    'number' => 'required',
                    'front' => 'mimes:jpeg,png,jpg|max:2048',
                    'back' => 'mimes:jpeg,png,jpg|max:2048',
                ]);
                $extensionFront = $request->front->extension();
                $extensionBack = $request->back->extension();

                $userData = User::whereid(Auth::user()->id)->first();

                $trans = array(" " => "-", ":" => "-");
                
                $frontName = $userData->username.strtr(now(), $trans).'front';
                $backName = $userData->username.strtr(now(), $trans).'back';
                
                $request->front->storeAs('/user/kyc', $frontName.'.'.$extensionFront, 'public');
                $request->back->storeAs('/user/kyc', $backName.'.'.$extensionBack, 'public');
                
                // Assign a Storage URL
                $urlFront = '/user/kyc/'.$frontName.'.'.$extensionFront;
                $urlBack = '/user/kyc/'.$backName.'.'.$extensionBack;
                
                // Create the Entry to KYC table
                $kycEntry = Kyc::create([
                   'user_id' => $userData->id,
                   'front' => $urlFront,
                   'back' => $urlBack,
                   'type' => $validated['type'],
                   'number' => $validated['number'],
                ]);

                return 'Successfully Uploaded and waiting for approval';
            }
        }
        abort(500, 'Could not upload image :(');
        

    }

    // View All KYC Document Uploaded with Sort
    public function index($filter='all'){
        // Get User Data
        $new = array();
        $counter = 0;

        if($filter == 'all'){
            $result = Kyc::whereIn('status', [0,1,2])->orderBy('id', 'DESC')->paginate(10);
        } elseif($filter == 'unverified'){
            $result = Kyc::whereStatus(0)->orderBy('id', 'DESC')->paginate(10);
        }
        elseif($filter == 'verified'){
            $result = Kyc::whereStatus(1)->orderBy('id', 'DESC')->paginate(10);
        }
        elseif($filter == 'rejected'){
            $result = Kyc::whereStatus(2)->orderBy('id', 'DESC')->paginate(10);
        }
        else{
            // $getUser = User::whereUsername($filter)->first();
            $getUser = User::where('username', 'LIKE', '%'.$filter.'%')->orWhere('email', 'LIKE', '%'.$filter.'%')->orWhere('fname', 'LIKE', '%'.$filter.'%')->orWhere('lname', 'LIKE', '%'.$filter.'%')->pluck('id');

            // $result = Kyc::all()->orderBy('id', 'DESC')->paginate(10);
            if($getUser != NULL){
                $result = Kyc::whereIn('user_id', $getUser)->orderBy('id', 'DESC')->paginate(10);
            }else{
                $result = KYC::where('user_id', -1)->paginate(10);
            }

        }


        if($result->isEmpty()){return NULL;}
        // Let's Get the UserData
        foreach($result as $m){
            $new[$counter] = $m;
            $new[$counter]['user'] = $m->user;
            $result[$counter] = $new[$counter];
            $counter++;
        } 
        return $result;
    }

    // View A single KYC Inforation
    public function single($id){
        $result = Kyc::whereId($id)->get();
        return $result;
    }

    // View A single KYC Inforation by USER ID
    public function user($user){
        $result = Kyc::whereUserId($user)->get();
        return $result;
    }

    // Verify a Specific User KYC
    public function verify(Request $request){
        $result = Kyc::whereId($request->id)->first();

        if($request->status == 'verify'){ $status = 1;}else{$status = 0;}

        
        if($result == NULL){
            return "KYC Data doesn't exist";
        }
        
        $result['status'] = $status;
        $result->save();
        
        $user = User::whereId($result->user_id)->first();
        $user['kyc'] = $status;
        $user->save();

        // Send a Notification

        if($request->status == 'verify'){ 
            return "User KYC Information has been Verified";
        }else{
            return "User KYC Information has been set to Unverified";
        }

    }

    public function my_kyc(){
        $kyc = Kyc::whereUserId(Auth::user()->id)->first();
        if($kyc != NULL){
            return $kyc;
        }
        return NULL;
    }

    // Verify all Pending KYC Documents
    public function verify_all(){
        
        $user = User::whereId(Auth::user()->id)->first();

        if($user->id == 1){

            $kyc = Kyc::whereIn('status', [0])->get();
            foreach($kyc as $e){
                $eachKyc = KYC::whereId($e->id)->first();
                $eachKyc['status'] = 1;
                $eachKyc->save();
                
                $eachUser = User::whereId($e->user_id)->first();
                $eachUser['kyc'] = 1;
                $eachUser->save();
            }
    
            return 'Approved all Pending KYC Documents';
        
        }else{
           return 'Only Admin User can perform this operation.';
        }
    }

}
