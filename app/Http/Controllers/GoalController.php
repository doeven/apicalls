<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GoalController extends Controller
{   
    // Method to Upload user GOAL
    public function upload(Request $request){

        $user = User::whereId(Auth::user()->id)->first();
        
        $goalUser = Goal::whereUserId(Auth::user()->id)->first();

        // Check For Upload Data
        if ($request->hasFile('image') ) {
            
            //  Let's Run Some Validation
            if ($request->file('image')->isValid()) {
                //
                $validated = $request->validate([
                    'goal' => 'required',
                    'cost' => 'required',
                    'image' => 'mimes:jpeg,png,jpg|max:2048',
                    'date' => 'required',
                ]);
                $extensionImage = $request->image->extension();

                $userData = User::whereid(Auth::user()->id)->first();

                $trans = array(" " => "-", ":" => "-");
                
                $imageName = $userData->username.strtr(now(), $trans).'image';
                
                $request->image->storeAs('/user/goal', $imageName.'.'.$extensionImage, 'public');
                
                // Assign a Storage URL
                $urlImage = '/user/goal/'.$imageName.'.'.$extensionImage;


                // Check if User has Uploaded GOAL 
                    if($goalUser == NULL ){
                        // Create the Entry to GOAL table
                        $goalEntry = Goal::create([
                            'user_id' => $userData->id,
                            'goal' => $validated['goal'],
                            'cost' => $validated['cost'],
                            'image' => $urlImage,
                            'date' => $validated['date'],
                         ]);
                         return 'Goal Successfully Uploaded';
                    } else {
                        // Edit  GOAL table
                        $goalUser['goal'] = $validated['goal'];
                        $goalUser['cost'] = $validated['cost'];
                        $goalUser['image'] =  $urlImage;
                        $goalUser['date'] = $validated['date'];
                        $goalUser->save();
                        return 'Goal Editted Successfully';
                    }
                
            }
        }
        abort(500, 'Could not upload image :(');
        
    }

    // View All GOAL Document Uploaded with Sort
    public function index($filter='all'){
        // Get User Data
        $new = array();
        $counter = 0;

        if($filter == 'all'){
            $result = Goal::orderBy('id', 'DESC')->paginate(10);}
        else{
            // $getUser = User::whereUsername($filter)->first();
            $getUser = User::where('username', 'LIKE', '%'.$filter.'%')->orWhere('email', 'LIKE', '%'.$filter.'%')->orWhere('fname', 'LIKE', '%'.$filter.'%')->orWhere('lname', 'LIKE', '%'.$filter.'%')->pluck('id');

            // $result = Goal::all()->orderBy('id', 'DESC')->paginate(10);
            if($getUser != NULL){
                $result = Goal::whereIn('user_id', $getUser)->orderBy('id', 'DESC')->paginate(10);
            }else{
                $result = Goal::where('user_id', -1)->paginate(10);
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

    // View A single GOAL Inforation
    public function single($id){
        $result = Goal::whereId($id)->get();
        return $result;
    }

    // View A single GOAL Information by USER ID
    public function user($user){
        $result = Goal::whereUserId($user)->get();
        return $result;
    }

    // Pull User Goal
    public function my_goal(){
        $goal = Goal::whereUserId(Auth::user()->id)->first();
        if($goal != NULL){
            return $goal;
        }
        return NULL;
    }

}
