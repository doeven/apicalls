<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PrivateMessage;
use App\Models\PrivateMessageReply;
use Illuminate\Support\Facades\Auth;

class PrivateMessageController extends Controller
{
    public function index(){
        $result = PrivateMessage::all();
        return $result;
    }

    // To Create a Private Message
    public function create(Request $request){

        // If the user You Are sending message to isn't a Parent or Child, CAN'T SEND
        $referrer = get_referrer_id(Auth::user()->id);
        $referral = get_referrer_id($request->receiver_id);

        // Get Where The Two Users Already sent each other a message
        $joint = PrivateMessage::where([
            ['sender_id', '=', Auth::user()->id ],
            ['receiver_id', '=', $request->receiver_id],
        ])->orWhere([
            ['sender_id', '=', $request->receiver_id],
            ['receiver_id', '=', Auth::user()->id ],
        ])->first();

        // return $joint;

        if(Auth::user()->id == $referral  || $referrer == $request->receiver_id){

                // If there is a previous message between these users, add there as reply
                if($joint != NULL){
                    $reply = new PrivateMessageReply([
                        'pm_id'   => $joint->id,
                        'user_id'   => Auth::user()->id,
                        'reply'     => $request->input('message'),
                        ]);
                    
                    $reply->save();

                    // if($reply){return "Private Message Sent";}

                }else{

                    // Otherwise, create a new message
                    $request['sender_id'] =  Auth::user()->id;
                    $request['status'] =  1;
                    $request['unread'] =  1;

                    // return $request;
                    $result = $this->store($request);
                }
                
                
                // Send An Email and notification to receiver
                
                $rep_user = User::whereId($request->receiver_id)->first();
                
                // Notification
                $email_address = $rep_user->email;
                $email_subject = 'Your have a new private message';
                $email_message = 'Hi '.$rep_user->fname.'<br/> You received a new private message from "'.Auth::user()->username.'"<br/><br/>Kindly login to respond.<br/><br/>Regards';
                send_email($email_address, $email_subject, $email_message); // Send Email
                create_notification($rep_user->id, 'You Received a Private Message', 71); // Notification
                
                
                return "Private Message Sent!";
            }


        return "You can not send a DM to this user. User has to be either a parent or child in referral tree.";
    }

    // Return the ticket with it's comments
    public function show($id){
        $result = PrivateMessage::whereId($id)->first();
        if($result == NULL) { return "Invalid Private Message ID";} // Check if Empty Result
        $result->unread = 0;
        $result->save();
        // Let's get other party
        if($result->sender_id == Auth::user()->id){
            $result['partner'] = User::whereId($result->receiver_id)->first();
        }else{
            $result['partner'] = User::whereId($result->sender_id)->first();
        }
        $result->replies; // Add Replies to the model
        return $result;
    }

    // Return the ticket with it's comments for Logged In User
    public function mine(){

        // Get User Data
        $new = array();
        $counter = 0;
    
        $result = PrivateMessage::where('sender_id', Auth::user()->id)->orWhere('receiver_id', Auth::user()->id)->orderBy('updated_at', 'DESC')->get();
        if($result == NULL || $result->isEmpty()) { return "You do not have any Private Messages";} // Check if Empty Result
        // Let's Get the UserData
        foreach($result as $m){
            $new[$counter] = $m;
            // Let's get other party
            if($m->sender_id == Auth::user()->id){
                $new[$counter]['partner'] = User::whereId($m->receiver_id)->first();
            }else{
                $new[$counter]['partner'] = User::whereId($m->sender_id)->first();
            }

            $result[$counter] = $new[$counter];
            $counter++;
        } 
        
        // $result->comments; // Add comments to the model
        return $result;
    }

    public function user($id){
        $result = PrivateMessage::whereUserId($id)->get();
        if($result == NULL || $result->isEmpty()) { return "This User Doesn't Have a Private Message";} // Check if Empty Result
        return $result;
    }

    // Delete the Requested Private Message
    public function destroy($id){

        $pm = PrivateMessage::whereId($id)->first();
        // Check if the ID still exists in DB
        if($pm == NULL || $pm->isEmpty()){
            return "Invalid Private Message ID";
        }

        // DELETE function for the ID
        $result = $pm->delete();

        // Delete All Replies Related to the Private Messages.


        return "Private Message Deleted Successfully";

    }

    // Handle Reply to a Private Message
    public function reply(Request $request){

        // Check if the Private Message Exists
        $message = PrivateMessage::whereId($request->input('pm_id'))->first();
        if($message == NULL){return "Invalid Private Message";}
        
        // Validate the Entry
        $this->validate($request, [
            'reply'  => 'required'
            ]);
            
        // Enter the Values
        $reply = new PrivateMessageReply([
                'pm_id'   => $request->input('pm_id'),
                'user_id'   => Auth::user()->id,
                'reply'     => $request->input('reply'),
                ]);
            
        $reply->save();

        // Send An Email and notification to receiver

            $message['unread'] += 1;
            $message->save();

            // Check Who Is Sending + Receiving
            if($message->sender_id == Auth::user()->id){
                $rep_user = User::whereId($message->receiver_id)->first();
            } else {
                $rep_user = User::whereId($message->sender_id)->first();
            }

            // $rep_user = User::whereId($message->receiver_id)->first();

            // Notification
            $email_address = $rep_user->email;
            $email_subject = 'Your have a new private message';
            $email_message = 'Hi '.$rep_user->fname.'<br/> You received a new private message from "'.Auth::user()->username.'"<br/><br/>Kindly login to respond.<br/><br/>Regards';
            send_email($email_address, $email_subject, $email_message); // Send Email
            create_notification($rep_user->id, 'You Received a Private Message', 71); // Notification

            return 'Message sent';

    }

    public function store(Request $request)
    {
        /* $this->validate($request, [
                'user_id'     => 'required',
                'message'   => 'required'
            ]);
 */
            $message = new PrivateMessage([
                'sender_id'   => Auth::user()->id,
                'receiver_id'   => $request->receiver_id,
                'message'   => $request->message,
                'status'    => 1,
                'unread'    => 1,
            ]);

            $message->save();

            // Send An Email

            return "A New Message Has Been Sent";
    }
}
