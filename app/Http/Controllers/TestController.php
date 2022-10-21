<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Kyc;
use App\Lib\CoinPaymentsAPI;
use App\Models\User;
use App\Models\Pledge;
use App\Models\Deposit;
use App\Models\Gateway;
use App\Models\Donation;
// Models
use App\Models\Withdraw;
use App\Models\Investment;
use App\Models\Transaction;

use App\Http\Controllers\WithdrawController;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TicketComment;
use App\Models\PledgeReceiver;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use Stevebauman\Location\Facades\Location;


use function PHPUnit\Framework\isEmpty;

class TestController extends Controller
{

    // Test Page
    public function hello(Request $request){

        return 'Hello World';

    }
    

}
