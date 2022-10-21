<?php

namespace App\Http\Controllers;

use App\Models\PrivateMessageReply;
use Illuminate\Http\Request;

class PrivateMessageReplyController extends Controller
{
    public function postReply(Request $request)
    {
        $this->validate($request, [
            'pm_id'   => 'required',
            'user_id'   => 'required',
            'reply'   => 'required'
        ]);

            $comment = PrivateMessageReply::create([
                'pm_id' => $request->input('pm_id'),
                'user_id'    => $request->input('user_id'),
                'reply'    => $request->input('reply'),
            ]);

            // send mail if the user commenting is not the ticket owner
            

            return 'Reply Sent';
    }
}
