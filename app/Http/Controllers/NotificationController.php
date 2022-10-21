<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function my_notifications(){
        $notifs = Notification::whereUserId(Auth::user()->id)->limit(10)->orderBy('id', 'DESC')->get();

        if($notifs->isEmpty()){return NULL;}
        return $notifs;
    }

    // Store a Transaction
    public static function store($data)
    {
        $result = Notification::create($data);

        // Put Up in Notification Pane
        
        $user = User::whereId($data['user_id'])->first();
        $user->notif = $user->notif + 1;
        $user->save();

        if($result){
            return ["result" => "Notification Created Successfully"];
        } else{
            return ["result" => "An Error Creating Notification"];
        }

    }

    // Create a Transaction
    public function create($data){
        $result = $this->store($data);
        return $result;
    }
}
