<?php

namespace App\Http\Controllers;

use App\Models\User;

//Models
use App\Models\Package;
use App\Models\Investment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvestmentController extends Controller
{
    // List All Investments on the Site
    public static function index()
    {
        $investments = Investment::all();
        return $investments;
        
    }

    // List All Investments by Logged In User
    public static function mine($filter)
    {

        // Get User Data
        $new = array();
        $counter = 0;

        // all,deposit,withdraw,bonus,lend,admin
        switch ($filter) {
            case 'all':
                $transactions = Investment::whereUserId(Auth::user()->id)->orderBy('id', 'DESC')->paginate(10);         
                break;
            case 'complete':
                $transactions = Investment::whereUserId(Auth::user()->id)->whereStatus(0)->orderBy('id', 'DESC')->paginate(10);         
                break;
            case 'on':
                $transactions = Investment::whereUserId(Auth::user()->id)->whereStatus(1)->orderBy('id', 'DESC')->paginate(10);         
                break; 
            default:
                $transactions = Investment::whereUserId(Auth::user()->id)->orderBy('id', 'DESC')->paginate(10);         
                break;
        }

        if($transactions->isEmpty()){return NULL;}
        // Let's Get the UserData
        foreach($transactions as $m){
            $new[$counter] = $m;
            $new[$counter]['user'] = $m->user;
            $new[$counter]['package'] = $m->package;
            $transactions[$counter] = $new[$counter];
            $counter++;
        } 

        return $transactions;        
    }

    // Show Specific Investment Based on ID
    public function show($id)
    {
        $investment = Investment::find($id);
        if($investment == NULL){ die('Investment ID Does Not Exist');}
        return $investment;        

    }

    // Show All Investment from a User
    public function user($id)
    {
        $investments = Investment::whereUserId($id)->get();
        if($investments == NULL || $investments->isEmpty()){ die('Investment ID Does Not Exist');}
        return $investments;        

    }

    // Store an Investment
    public static function store($data)
    {
        
        $result = create_investment($data); // Updated with Bonus

        return $result;

        if($result){
            return ["result" => "Investment Added Successfully"];
        } else{
            return ["result" => "An Error Creating Investment"];
        }

    }

    // Create an Investment
    public function create(Request $request){

        $settings = settings();

        $data = $request->all();
        $data['user_id'] = Auth::user()->id;

        // Check if ReInvestment is Enabled
        if($settings->rinv == 1){

            $inv = Investment::whereUserId(Auth::user()->id)->get()->last();
            if($inv != null && $data['amount'] < $inv->amount){
                return "You have to Invest ".$settings->symbol.$inv->amount." or higher";               
            }


        }

            // For Regular No Reinvest Check
            $result = $this->store($data);
            return $result;

        





    }

    
}
 