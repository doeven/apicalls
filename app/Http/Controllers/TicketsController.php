<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;

//Models
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TicketComment;
use Illuminate\Support\Facades\Auth;


class TicketsController extends Controller
{
    public function index($filter){

        // all,closed,open
        switch ($filter) {
            case 'all':
                $result = Ticket::orderBy('updated_at', 'DESC')->get();        
                break;
            case 'closed':
                $result = Ticket::whereStatus('closed')->orderBy('updated_at', 'DESC')->get();         
                break;
            case 'open':
                $result = Ticket::whereStatus('open')->orderBy('updated_at', 'DESC')->get();         
                break;
            default:
                $result = Ticket::orderBy('updated_at', 'DESC')->get();
                break;
        }

        if($result == NULL || $result->isEmpty()) { return "You do not have any Tickets";} // Check if Empty Result
        // $result->comments; // Add comments to the model
        return $result;
    }

    // To Create a Ticket
    public function create(Request $request){

        $result = $this->store($request);
        return $result;
    }

    // Return the ticket with it's comments
    public function show($id){
        $result = Ticket::whereId($id)->first();
        if($result == NULL) { return "Invalid Ticket ID";} // Check if Empty Result

        if(Auth::user()->roles != NULL){
            $result['a_unread'] = 0;
            $result->save();
        }else{
            $result['unread'] = 0;
            $result->save();
        }
        $result->comments; // Add comments to the model
        return $result;
    }

    // Return the ticket with it's comments for Logged In User
    public function mine($filter){

        // all,closed,open
        switch ($filter) {
            case 'all':
                $result = Ticket::whereUserId(Auth::user()->id)->orderBy('id', 'DESC')->get();        
                break;
            case 'closed':
                $result = Ticket::whereUserId(Auth::user()->id)->whereStatus('closed')->orderBy('id', 'DESC')->get();         
                break;
            case 'open':
                $result = Ticket::whereUserId(Auth::user()->id)->whereStatus('open')->orderBy('id', 'DESC')->get();         
                break;
            default:
                $result = Ticket::whereUserId(Auth::user()->id)->orderBy('id', 'DESC')->get();
                break;
        }

        if($result == NULL || $result->isEmpty()) { return "You do not have any Tickets";} // Check if Empty Result
        // $result->comments; // Add comments to the model
        return $result; 
    }

    public function user($id){
        $result = Ticket::whereUserId($id)->get();
        return $result;
    }

    // Delete the Requested Ticket
    public function destroy($id){

        if(Auth::user()->street != 'admin'){ return 'You do not have this Authorization'; }

        $ticket = Ticket::whereId($id)->first();

        // Check if the ID still exists in DB
        if($ticket == NULL){
            return "Invalid Ticket ID";
        }

        // DELETE function for the ID
        $result = $ticket->delete();

        // Delete All Replies Related to the Ticket.


        return "Ticket Deleted Successfully";

    }

    // Mark the Ticket as Open or Closed
    public function mark($id, $status){
        $result = Ticket::whereId($id)->first();
        if($result == NULL){return "Invalid Ticket";}

        if($status == 'open'){
            $result['status'] = 'open';
            $result->save();
            return "Ticket Marked as Open";
        } elseif($status == 'closed'){
            $result['status'] = 'closed';
            $result->save();
            return "Ticket Marked as Closed";
        }else{
            return "Invalid Status";
        }

    }

    // Handle Reply to a Ticket
    public function reply(Request $request){

        // Check if the Ticket Exists
        $ticket = Ticket::whereId($request->ticket_id)->first();
        if($ticket == NULL){return "Invalid Ticket";}

        // Validate the Entry
        $this->validate($request, [
            'ticket_id'     => 'required',
            'comment'  => 'required'
        ]);

        // Enter the Values
        $reply = new TicketComment([
            'ticket_id' => $request->input('ticket_id'),
            'user_id'   => Auth::user()->id,
            'comment'     => $request->input('comment'),
        ]);

        $reply->save();

        // Send An Email if Admin Replies
            if(Auth::user()->roles != NULL ) {

                $ticket['unread'] += 1;
                $ticket->save();

                $rep_user = User::whereId($ticket->user_id)->first();

                // Notification
                $email_address = $rep_user->email;
                $email_subject = 'Your Ticket Has Been Replied';
                $email_message = 'Hi '.$rep_user->fname.'<br/> Your ticket has been replied.<br/><br/>Regards';
                send_email($email_address, $email_subject, $email_message); // Send Email
                create_notification(Auth::user()->id, 'Support Replied Your Ticket', 61); // Notification
                
            }else{
                $ticket['a_unread'] += 1;
                $ticket->save();
                create_notification(1, 'User Replied to Ticket', 161); // Admin Notification
            }

        return "Ticket Replied";

    }


    public function store(Request $request)
    {
        $this->validate($request, [
                'title'     => 'required',
                'category'  => 'required',
                'priority'  => 'required',
                'message'   => 'required'
            ]);

            $ticket = new Ticket([
                // 'user_id'   => $request->input('user_id'),
                'user_id'   => Auth::user()->id,
                'category_id'  => $request->input('category'),
                'ticket_id' => strtoupper( 'TK'.Str::random(10) ),
                'title'     => $request->input('title'),
                'priority'  => $request->input('priority'),
                'message'   => $request->input('message'),
                'status'    => "open",
            ]);

            $ticket->save();

            // Send An Email

            // Notification

            $email_address = Auth::user()->email;
            $email_subject = 'A New Ticket Opened';
            $email_message = 'Hi '.Auth::user()->fname.'<br/> You have created a new ticket which would be replied to by the support team in a few hours.<br/><br/>Regards';

            send_email($email_address, $email_subject, $email_message); // Send Email
            create_notification(Auth::user()->id, 'New Ticket Created', 60); // Notification
            create_notification(1, 'User Created a New Ticket', 160); // Admin Notification

            return "A New Ticket Has Been Opened";
    }
}
