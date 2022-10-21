<?php

namespace App\Http\Controllers;

use App\Models\User;

//Models
use App\Models\Deposit;
use App\Models\Investment;
use App\Models\Package;
use App\Models\Pledge;
use App\Models\Transaction;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // List All Transactions on the Site
    public static function index($filter)
    {
        // Get User Data
        $new = array();
        $counter = 0;
        
        // all,deposit,withdraw,bonus,lend,admin
        switch ($filter) {
            case 'all':
                $transactions = Transaction::orderBy('id', 'DESC')->paginate(10);         
                break;
            case 'deposit':
                $transactions = Transaction::whereType(1)->orderBy('id', 'DESC')->paginate(10);         
                break;
            case 'withdraw':
                $transactions = Transaction::whereType(11)->orderBy('id', 'DESC')->paginate(10);         
                break;
            case 'bonus':
                $transactions = Transaction::whereIn('type', [7,8])->orderBy('id', 'DESC')->paginate(10);         
                break;
            case 'lend':
                $transactions = Transaction::whereType(3)->orderBy('id', 'DESC')->paginate(10);         
                break;
            case 'donation-all':
                $transactions = Transaction::whereNotIn('type', [1,3,11])->orderBy('id', 'DESC')->paginate(10);         
                break;
            case 'donations':
                $transactions = Transaction::whereType(4)->orderBy('id', 'DESC')->paginate(10);         
                break;
            case 'sent':
                $transactions = Transaction::whereType(19)->orderBy('id', 'DESC')->paginate(10);         
                break;
            case 'received':
                $transactions = Transaction::whereType(9)->orderBy('id', 'DESC')->paginate(10);         
                break;
            case 'admin':
                    $transactions = Transaction::whereIn('type',[5,12])->orderBy('id', 'DESC')->paginate(10);         
                    break;
            case '':
                $transactions = Transaction::orderBy('id', 'DESC')->paginate(10);         
                break;  
            default:
                $transactions = Transaction::orderBy('id', 'DESC')->paginate(10);         
                break;
        }

        if($transactions->isEmpty()){return NULL;}
        // Let's Get the UserData
        foreach($transactions as $m){
            $new[$counter] = $m;
            $new[$counter]['user'] = $m->user;
            $transactions[$counter] = $new[$counter];
            $counter++;
        } 

        return $transactions;  
        
    }


    // List All Transactions on the Site
    public static function single_user($userId, $filter)
    {
        // Get User Data
        $new = array();
        $counter = 0;

        // all,deposit,withdraw,bonus,lend,admin
        switch ($filter) {
            case 'all':
                $transactions = Transaction::whereUserId($userId)->orderBy('id', 'DESC')->paginate(10);         
                break;
            case 'deposit':
                $transactions = Transaction::whereUserId($userId)->whereType(1)->orderBy('id', 'DESC')->paginate(10);         
                break;
            case 'withdraw':
                $transactions = Transaction::whereUserId($userId)->whereType(11)->orderBy('id', 'DESC')->paginate(10);         
                break;
            case 'bonus':
                $transactions = Transaction::whereUserId($userId)->whereIn('type', [7,8])->orderBy('id', 'DESC')->paginate(10);         
                break;
            case 'lend':
                $transactions = Transaction::whereUserId($userId)->whereType(3)->orderBy('id', 'DESC')->paginate(10);         
                break;
            case 'donation-all':
                $transactions = Transaction::whereUserId($userId)->whereNotIn('type', [1,3,11])->orderBy('id', 'DESC')->paginate(10);         
                break;
            case 'donations':
                $transactions = Transaction::whereUserId($userId)->whereType(4)->orderBy('id', 'DESC')->paginate(10);         
                break;
            case 'sent':
                $transactions = Transaction::whereUserId($userId)->whereType(19)->orderBy('id', 'DESC')->paginate(10);         
                break;
            case 'received':
                $transactions = Transaction::whereUserId($userId)->whereType(9)->orderBy('id', 'DESC')->paginate(10);         
                break;
            case 'admin':
                    $transactions = Transaction::whereUserId($userId)->whereIn('type',[5,12])->orderBy('id', 'DESC')->paginate(10);         
                    break;
            case '':
                $transactions = Transaction::whereUserId($userId)->orderBy('id', 'DESC')->paginate(10);         
                break;  
            default:
                $transactions = Transaction::whereUserId($userId)->orderBy('id', 'DESC')->paginate(10);         
                break;
        }
        
        // $transactions = Transaction::whereUserId($filter)->orderBy('id', 'DESC')->paginate(10);         
               

        if($transactions->isEmpty()){return NULL;}
        // Let's Get the UserData
        foreach($transactions as $m){
            $new[$counter] = $m;
            $new[$counter]['user'] = $m->user;
            $transactions[$counter] = $new[$counter];
            $counter++;
        } 

        return $transactions;  
        
    }

    // List All Transactions by Logged In User
    public static function mine($filter)
    {

        // all,deposit,withdraw,bonus,lend,admin
        switch ($filter) {
            case 'all':
                $transactions = Transaction::whereUserId(Auth::user()->id)->orderBy('id', 'DESC')->paginate(10);         
                break;
            case 'deposit':
                $transactions = Transaction::whereUserId(Auth::user()->id)->whereType(1)->orderBy('id', 'DESC')->paginate(10);         
                break;
            case 'withdraw':
                $transactions = Transaction::whereUserId(Auth::user()->id)->whereType(11)->orderBy('id', 'DESC')->paginate(10);         
                break;
            case 'bonus':
                $transactions = Transaction::whereUserId(Auth::user()->id)->whereIn('type', [7,8])->orderBy('id', 'DESC')->paginate(10);         
                break;
            case 'lend':
                $transactions = Transaction::whereUserId(Auth::user()->id)->whereType(3)->orderBy('id', 'DESC')->paginate(10);         
                break;
            case 'donation-all':
                $transactions = Transaction::whereUserId(Auth::user()->id)->whereNotIn('type', [1,3,11])->orderBy('id', 'DESC')->paginate(10);         
                break;
            case 'donations':
                $transactions = Transaction::whereUserId(Auth::user()->id)->whereType(4)->orderBy('id', 'DESC')->paginate(10);         
                break;
            case 'sent':
                $transactions = Transaction::whereUserId(Auth::user()->id)->whereType(19)->orderBy('id', 'DESC')->paginate(10);         
                break;
            case 'received':
                $transactions = Transaction::whereUserId(Auth::user()->id)->whereType(9)->orderBy('id', 'DESC')->paginate(10);         
                break;
            case 'admin':
                    $transactions = Transaction::whereUserId(Auth::user()->id)->whereIn('type',[5,12])->orderBy('id', 'DESC')->paginate(10);         
                    break;
            case '':
                $transactions = Transaction::whereUserId(Auth::user()->id)->orderBy('id', 'DESC')->paginate(10);         
                break;  
            default:
                $transactions = Transaction::whereUserId(Auth::user()->id)->orderBy('id', 'DESC')->paginate(10);         
                break;
        }

        return $transactions;        
    }

    // Show Specific Transaction Based on ID
    public function show($id)
    {
        $transaction = Transaction::find($id);
        if($transaction == NULL){ die('Transaction ID Does Not Exist');}
        return $transaction;        

    }

    // Show Specific Transaction Based on Transaction ID
    public function tx_id($id)
    {
        $transaction = Transaction::whereTxId($id)->get();
        if($transaction == NULL || $transaction->isEmpty()){ die('Transaction ID Does Not Exist');}
        return $transaction;     

    }

    // Show All Transactions from a User
    public function user($id)
    {
        $transactions = Transaction::whereUserId($id)->get();
        if($transactions == NULL || $transactions->isEmpty()){ die('Transaction ID Does Not Exist');}
        return $transactions;        

    }

    // Store a Transaction
    public static function store($data)
    {
        $result = Transaction::create($data);

        if($result){
            return ["result" => "Transaction Created Successfully"];
        } else{
            return ["result" => "An Error Creating Transaction"];
        }

    }

    // Create a Transaction
    public function create(Request $request){
        $data = $request->all();
        $result = $this->store($data);
        return $result;
    }

    public function transaction_data(){

        $trans_data = array();

        $user = User::whereId(Auth::user()->id)->first();

        // Get Investment Details
        $inv = Investment::whereUserId($user->id)->get();
        
        if($inv->isNotEmpty()){
            $last_pack = $inv->last();
            $package = Package::whereId($last_pack->package_id)->first();
            $trans_data['last_package'] = $package->title;
            $trans_data['last_package_data'] = $last_pack;
            $trans_data['last_package_helper'] = $package;

        }else{
            $trans_data['last_package'] = 'No Package';
        }
        // Deposit Data
        $deposit = Deposit::whereUserId($user->id)->whereStatus(1)->get();
        if($deposit->isNotEmpty()){
            $trans_data['first_deposit'] = $deposit->first()->amount;
            $trans_data['last_deposit'] = $deposit->last()->amount;
            $trans_data['total_deposit'] = $deposit->sum('amount');
        }else{
            $trans_data['first_deposit'] = '0';
            $trans_data['last_deposit'] = '0';
            $trans_data['total_deposit'] = '0';
        }

        // Active Deposit Amount = Amount in the Investment Run
        $active_invest = Investment::whereUserId($user->id)->where('status', '!=', 2)->get();
        // $trans_data['active_investment'] = $deposit->sum('amount');

        if($active_invest->isNotEmpty()){
            // $trans_data['active_investment'] = $deposit->sum('amount');
            $trans_data['active_investment'] = $active_invest->last()->amount;
        }else{
            $trans_data['active_investment'] = '0';
        }
        
        // Withdraw Data
        $withdraw = Withdraw::whereUserId($user->id)->get();

        if($deposit->isNotEmpty()){
            $trans_data['pending_withdraw'] = $withdraw->where('status', 0)->sum('amount');
            $trans_data['completed_withdraw'] = $withdraw->where('status', 1)->sum('amount');
            // $trans_data['last_withdraw'] = $withdraw->last()->amount;
        }else{
            $trans_data['pending_withdraw'] = '0';
            $trans_data['completed_withdraw'] = '0';
            $trans_data['last_withdraw'] = '0';
        }

        // Total Earned
        $earnings = Transaction::whereUserId($user->id)->whereIn('type', [5,6,7,8])->get();
        if($earnings->isNotEmpty()){
            $trans_data['earnings'] = round($earnings->sum('amount'), 2);
        }else{
            $trans_data['earnings'] = '0';
        }

        // Total Referral Bonus
        $ref_earn = Transaction::whereUserId($user->id)->whereIn('type', [7,8])->get();
        if($earnings->isNotEmpty()){
            $trans_data['ref_earn'] = round($ref_earn->sum('amount'), 2);
        }else{
            $trans_data['ref_earn'] = '0';
        }

        // Referrals
        $referrals = User::whereReferrer($user->id);
        $referral_count = $referrals->count();
        $trans_data['direct_referral_count'] = $referral_count;


        // Level Data

        return $trans_data;
    }

    public function user_pledge_data(){
        $trans_data = array();

        $user = User::whereId(Auth::user()->id)->first();
        // Pledge Data
        $pledge = Pledge::whereUserId($user->id)->get();
        if($pledge->isNotEmpty()){
            $trans_data['pending_pledge'] = $pledge->where('status', 0)->sum('amount');
            $trans_data['completed_pledge'] = $pledge->where('status', 1)->sum('amount');
        }else{
            $trans_data['pending_pledge'] = '0';
            $trans_data['completed_pledge'] = '0';
        }

        return $trans_data;
    }

    public function site_transaction_data(){

        $trans_data = array();

        // All Deposits
        $deposit = Deposit::whereStatus(1)->get();
        if($deposit->isEmpty()){$trans_data['total_deposit'] = '0';}else{$trans_data['total_deposit'] = $deposit->sum('amount');}

        $deposit_seven = Deposit::whereStatus(1)->where('created_at', '>=', now()->subDays(7))->get();
        if($deposit_seven->isEmpty()){$trans_data['total_deposit_seven'] = '0';}else{$trans_data['total_deposit_seven'] = $deposit_seven->sum('amount');} 

        $deposit_thirty = Deposit::whereStatus(1)->where('created_at', '>=', now()->subDays(30))->get();
        if($deposit_thirty->isEmpty()){$trans_data['total_deposit_thirty'] = '0';}else{$trans_data['total_deposit_thirty'] = $deposit_thirty->sum('amount');} 
        
        // Total Withdrawals
        $withdraws = Withdraw::whereStatus(1)->get();
        if($withdraws->isEmpty()){$trans_data['total_withdraw'] = '0';}else{$trans_data['total_withdraw'] = $withdraws->sum('amount');}

        $withdraws_seven = Deposit::whereStatus(1)->where('created_at', '>=', now()->subDays(7))->get();
        if($withdraws_seven->isEmpty()){$trans_data['total_withdraw_seven'] = '0';}else{$trans_data['total_withdraw_seven'] = $withdraws_seven->sum('amount');} 

        $withdraws_thirty = Deposit::whereStatus(1)->where('created_at', '>=', now()->subDays(30))->get();
        if($withdraws_thirty->isEmpty()){$trans_data['total_withdraw_thirty'] = '0';}else{$trans_data['total_withdraw_thirty'] = $withdraws_seven->sum('amount');} 



        $date = \Carbon\Carbon::today()->subDays(30);

        $users = User::where('created_at', '>=', $date)->get();
        return $trans_data;
    }


}
