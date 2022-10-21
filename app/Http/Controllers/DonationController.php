<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonationController extends Controller
{
    // Get People You Are Supposed to Make Payment To (You were matched to )
    public function donations(){
        // Get User Data
        $new = array();
        $counter = 0;

        $donations = Donation::whereUserId(Auth::user()->id)->whereStatus(0)->get();

        foreach($donations as $m){
            $new[$counter] = $m;
            $new[$counter]['donor'] = $m->donor;
            $new[$counter]['receiver'] = $m->receiver;
            $new[$counter]['rec_bank'] = $m->rec_bank;
            $donations[$counter] = $new[$counter];
            $counter++;
        } 
        return $donations;
    }

    // Get People You Are Supposed to RECEIVE PAYMENT FROM (You were matched to pay you)
    public function donations_receiver_side(){
        // Get User Data
        $new = array();
        $counter = 0;

        $donations = Donation::whereTo(Auth::user()->id)->whereStatus(0)->get();

        foreach($donations as $m){
            $new[$counter] = $m;
            $new[$counter]['donor'] = $m->donor;
            $new[$counter]['receiver'] = $m->receiver;
            $new[$counter]['rec_bank'] = $m->rec_bank;
            $donations[$counter] = $new[$counter];
            $counter++;
        } 
        return $donations;
    }
}
