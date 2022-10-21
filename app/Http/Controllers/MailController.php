<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// For Email
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;
use Symfony\Component\HttpFoundation\Response;

class MailController extends Controller
{
    public static function sendEmail($to, $subject, $message, $queue=FALSE) {
   
        $mailData = [
            'title' => 'Demo Email',
            'subject' => $subject,
            'message' => $message
        ];
  
        Mail::to($to)->send(new SendEmail($mailData));
   
        return response()->json([
            'message' => 'Email has been sent.'
        ], Response::HTTP_OK);
    }
}
