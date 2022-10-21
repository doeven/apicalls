<?php

use Laravel\Fortify\Features;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KYCController;
use App\Http\Controllers\P2PController;
use App\Http\Controllers\CronController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DepositController;


// For the Email
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DonationPackController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Test Page
Route::get('/test', [TestController::class, 'hello']);
Route::post('/ticket/create', [TicketsController::class, 'create']); // Create a new Ticket

Route::post('/investment/create', [InvestmentController::class, 'create']); // Create a new Investment


Route::get('/user/transaction-data', [TransactionController::class, 'transaction_data']); // All User Transaction Data
Route::post('/upload', [KYCController::class, 'upload']);
Route::get('/kyc/id/{id}', [KYCController::class, 'single']); // View a Specific KYC by ID
Route::get('/kyc/{filter}', [KYCController::class, 'index']); // List All Uploaded KYCs by Users
Route::get('/kyc/user/{user}', [KYCController::class, 'user']); // View a Specific KYC by User
Route::get('/user/referrals', [UserController::class, 'user_referrals']); // Get Users Referred by Logged in User
Route::get('/p2p/pledges', [P2PController::class, 'pledge_list']); // Send A Pledge Post Request
Route::apiResource('/page', PageController::class);
Route::apiResource('/alert', AlertController::class);


Route::get('/admin/users/{all}', [AdminController::class, 'all_users']); // View All Users


Route::get('/send-email', function(){
    $data = array(
        'user_id' => 2,
        'tx_id' => 'te3453',
        'description' => 'This is it',
        'amount' => 442,
        'balance' => 20,
        'fee' => 2,
        'type' => 4
    );
    return create_transaction($data);
});




Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// All Cron Routes
Route::get('/crons/due-investment', [CronController::class, 'balance_due_investments']);
Route::get('/crons/pop/ban', [AdminController::class, 'block_no_pop']); // Ban All Donors with No POP in 24 hours

// User
Route::get('/username-check/{username}', [UserController::class, 'get_username_check']); // Check if a Username Exists


Route::post('/withdraws', [PaymentController::class, 'withdraw']);
Route::get('/p2p/packs/active', [DonationPackController::class, 'donation_packs']); // Show All Donation Packages

// Callback URLs
// Route::post('/callback/block-io', [PaymentController::class, 'callback_block_io'])->name('callback-block-io');
Route::post('/callback/block-io', [PaymentController::class, 'callback_block_io'])->name('callback-block-io');

Route::post('/callback/coingate', [PaymentController::class, 'callback_coingate'])->name('callback-coingate'); // CoinGate Callback
Route::post('/callback/alfacoins', [PaymentController::class, 'callback_alfacoins'])->name('callback-alfacoins'); // AlfaCoins Callback

// Gateway URLS
Route::get('/payment/enable-block-io-notification', [PaymentController::class, 'block_account_notification'])->name('enable-block-io');


Route::get('/transactions/mine/{filter}', [TransactionController::class, 'mine']); // All Logged In User Transactions

// Packages
Route::get('/packages/active', [PackageController::class, 'packages']); // Show All Packages

// News Created
Route::apiResource('/news', NewsController::class);


