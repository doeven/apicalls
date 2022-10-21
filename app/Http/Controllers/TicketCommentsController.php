<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Models
use App\Models\TicketComment;

class TicketCommentsController extends Controller
{
    public function postComment(Request $request)
    {
        $this->validate($request, [
            'ticket_id'   => 'required',
            'user_id'   => 'required',
            'comment'   => 'required'
        ]);

            $comment = TicketComment::create([
                'ticket_id' => $request->input('ticket_id'),
                'user_id'    => $request->input('user_id'),
                'comment'    => $request->input('comment'),
            ]);

            // send mail if the user commenting is not the ticket owner
            

            return 'Reply Sent';
    }
}
