<?php

use App\Role\UserRole;
use Illuminate\Http\Request;

// Controllers Import
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KYCController;
use App\Http\Controllers\P2PController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\WithdrawController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DonationPackController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\PrivateMessageController;
use App\Http\Controllers\WithdrawMethodController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

// All URLs that Require Login for Access
Route::group(['middleware' => 'auth:sanctum'], function(){

        // Route to SEND Deposit Request
        Route::post('/deposit', [DepositController::class, 'deposit']);
        
        // Withdrawals
        Route::post('/withdraw', [WithdrawController::class, 'withdraw']); // Make a Withdraw Request
        Route::get('/withdraws/mine/{filter}', [WithdrawController::class, 'mine']); // Logged In Withdrawal Request
        Route::post('/user-transfer-fund', [WithdrawController::class, 'transfer']); // Make a Transfer to Another User
        
        
Route::get('/run', [TestController::class, 'run']);


        // Return Logged In User Data
        Route::get('/user', [UserController::class, 'user_info']); // Get User Information
        Route::get('/user/{id}', [UserController::class, 'user_data']); // Get User Information by ID
        Route::get('/user/session/check', [UserController::class, 'login_code_session_check']); // Check the Login Code Session
        Route::post('/user/session/set', [UserController::class, 'login_code_session_set']); // Set the Login Code Session
        Route::get('/user/session/set-logged', [UserController::class, 'set_logged_in_session']); // Set for Logged In User Session
        Route::post('/user/session/send-email', [UserController::class, 'two_fa_email']); // Send Two FA Email Out
        Route::post('/user/twofa/change', [UserController::class, 'change_twofa_type']); // Change User 2FA Type
        Route::get('/user-info/{id}', [UserController::class, 'user_data_public']); // Get User Information by ID
        Route::patch('/user/update/profile', [UserController::class, 'update_user']); // Get User Information by ID
        Route::get('/user/ref/mine', [UserController::class, 'user_referrals']); // Get Users Referred by Logged in User
        Route::get('/user/ref-deposit/mine', [UserController::class, 'user_referrals_deposit']); // Get Total Deposit from Users Referred by Logged in User 
        Route::get('/user/ref/{id}', [UserController::class, 'user_referrals_by_id']); // Get Users Referred by User ID
        Route::get('/user/transaction/data', [TransactionController::class, 'transaction_data']); // All User Transaction Data
        Route::get('/user/transaction/pledge', [TransactionController::class, 'user_pledge_data']); // All User Pledge Data
        Route::post('/user/change-pass', [UserController::class, 'change_pass']); // Logged In User Change Password
        Route::patch('/user/bank/', [UserController::class, 'bank_update']); // Update my bank details
        Route::get('/user/bank/details/logged', [UserController::class, 'bank_info']); // Get Logged In User Bank Details
        Route::get('/user/notifications/alert', [NotificationController::class, 'my_notifications']); // Get Logged In User Notifications
        Route::post('/profile/upload', [UserController::class, 'upload_profile_photo']); // Upload User Profile Photo

        Route::post('admin/change-pass/{id}', [UserController::class, 'admin_change_pass']); // Change User Password by ID for Admins
        
        // All Ticket Routes
        // Route::get('/tickets', [TicketsController::class, 'index'])->middleware('check_user_role:' . UserRole::ROLE_ADMIN); // Create a new Ticket
        Route::get('/tickets/{filter}', [TicketsController::class, 'index']); // List All Tickets
        Route::post('/ticket/create', [TicketsController::class, 'create']); // Create a new Ticket
        Route::get('/ticket/{id}', [TicketsController::class, 'show']); // Show a Specific Ticket
        Route::get('/ticket/user/{id}', [TicketsController::class, 'user']); // Show Tickets by User
        Route::delete('/ticket/{id}', [TicketsController::class, 'destroy']); // Delete a Ticket
        Route::get('/ticket/mark/{id}/{status}', [TicketsController::class, 'mark']); // Mark Ticket as OPEN/CLOSED
        Route::post('/ticket/reply', [TicketsController::class, 'reply']); // Reply to a Ticket
        Route::get('/tickets/mine/{filter}', [TicketsController::class, 'mine']); // Show Logged in User Tickets == all, closed, open ==

        // All Private message Reply Routes
        // Route::get('/replies', [PrivateMessageController::class, 'index'])->middleware('check_user_role:' . UserRole::ROLE_ADMIN); // Create a new Reply
        Route::get('/messages', [PrivateMessageController::class, 'index']); // List All Replies
        Route::post('/message/create', [PrivateMessageController::class, 'create']); // Create a new Reply
        Route::get('/message/{id}', [PrivateMessageController::class, 'show']); // Show a Specific Reply
        Route::get('/message/user/{id}', [PrivateMessageController::class, 'user']); // Show Replies by User
        Route::delete('/message/{id}', [PrivateMessageController::class, 'destroy']); // Delete a Reply
        Route::post('/message/mark/{id}', [PrivateMessageController::class, 'mark']); // Mark Reply as OPEN/CLOSED
        Route::post('/message/reply', [PrivateMessageController::class, 'reply']); // Reply to a Reply
        Route::get('/messages/mine', [PrivateMessageController::class, 'mine']); // Show Logged in User Replies

        // Payment Gateway Methods
        Route::apiResource('/gateway', GatewayController::class);

        // Package Methods
        Route::apiResource('/package', PackageController::class);
        Route::get('/packages/active', [PackageController::class, 'packages']); // Show All Packages

        // Donation Package Methods
        Route::apiResource('/p2p/packs', DonationPackController::class);
        Route::get('/p2p/packages/active', [DonationPackController::class, 'donation_packs']); // Show All Donation Packages

        // Withdraw Methods
        Route::apiResource('/withdraw/method', WithdrawMethodController::class);

        // Level Methods
        Route::apiResource('/level', LevelController::class);

        // Email Templates
        Route::apiResource('/email', EmailTemplateController::class);

        // Pages Created
        Route::apiResource('/page', PageController::class);

        // News Created
        Route::apiResource('/news', NewsController::class);

        // Alert Created
        Route::apiResource('/alert', AlertController::class);

        // Sliders
        Route::apiResource('/slider', SliderController::class);

        // All Investment API Routes
        Route::post('/investment/create', [InvestmentController::class, 'create']); // Create a new Investment
        Route::get('/investments', [InvestmentController::class, 'index']); // All Site Transactions
        Route::get('/investment/id/{id}', [InvestmentController::class, 'show']); // Single by ID
        Route::get('/investments/user/{id}', [InvestmentController::class, 'user']); // Investments by A User
        Route::get('/investments/mine/{filter}', [InvestmentController::class, 'mine']); // Investments by Logged In User

        // All Site Settings
        //Route::get('/settings', [SettingController::class, 'index']); // List Settings
        Route::patch('/settings', [SettingController::class, 'update']); // Update Settings
        Route::get('/settings/purge', [SettingController::class, 'purge']); // Purge Settings

        // All P2P Settings
        Route::get('/settings/p2p', [P2PController::class, 'index']); // List Settings
        Route::patch('/settings/p2p', [P2PController::class, 'update']); // Update Settings

        // All KYC Routes
        Route::get('/kyc/{filter}', [KYCController::class, 'index']); // List All Uploaded KYCs by Users with filters, all, verified, unverified
        Route::post('/kyc/upload', [KYCController::class, 'upload']); // Upload KYC by User
        Route::get('/kyc/id/{id}', [KYCController::class, 'single']); // View a Specific KYC by ID
        Route::get('/kyc/user/{user}', [KYCController::class, 'user']); // View a Specific KYC by User
        Route::post('/kyc/verify', [KYCController::class, 'verify']); // Verify a KYC
        Route::post('/kyc/verify-all', [KYCController::class, 'verify_all']); // Verify all pending KYC
        Route::get('/kyc/logged/user', [KYCController::class, 'my_kyc']); // Logged In User KYC

        // All Goal Routes
        Route::get('/goal/{filter}', [GoalController::class, 'index']); // List All Uploaded KYCs by Users with filters, all, verified, unverified

        // Admin Pull Goals
        Route::post('/goal/upload', [GoalController::class, 'upload']); // Upload Goal by User
        Route::get('/goal/logged/user', [GoalController::class, 'my_goal']); // User Pull Goals

        // All P2P routes
        Route::post('/p2p/pledge', [P2PController::class, 'pledge']); // Send A Pledge Post Request
        Route::get('/p2p/pledge/{list}', [P2PController::class, 'pledge_list']); // View All Plegdes, Active, etc

        Route::get('/p2p/donations/{list}', [P2PController::class, 'donation_entries']); // View All Donation Entries (all, completed, nopop, uploaded)
        Route::get('/p2p/pledges/mine/{list}', [P2PController::class, 'my_pledge_entries']); // View All User Pledge Entries (all, completed, progress, disabled)
        Route::post('/p2p/user/pop', [P2PController::class, 'pop_upload']); // Upload POP donation_id, pop
        Route::post('/p2p/confirm/pop', [P2PController::class, 'confirm_pop']); // Confirm POP donation_id

        // Donations - All Donations to be PAID
        Route::get('/p2p/my/receivers', [DonationController::class, 'donations']); // View All Donation I'm supposed to make (all, completed, nopop, uploaded)
        Route::get('/p2p/my/donors', [DonationController::class, 'donations_receiver_side']); // View All Donation I'm supposed to Receive from (all, completed, nopop, uploaded)

        



        // All Transaction API routes
        Route::post('/transaction/create', [TransactionController::class, 'create']); // Create a new Transaction
        Route::get('/transactions/{filter}', [TransactionController::class, 'index']); // All Site Transactions
        Route::get('/deposits/{filter}', [DepositController::class, 'index']); // All Site Deposits
        Route::get('/user-transactions/{userId}/{filter}', [TransactionController::class, 'single_user']); // All Specified User Site Transactions
        Route::get('/transaction/mine/{filter}', [TransactionController::class, 'mine']); // All Logged In User Transactions
        Route::get('/transaction/id/{id}', [TransactionController::class, 'show']); // Single by ID
        Route::get('/transaction/tx/{id}', [TransactionController::class, 'tx_id']); // Single Transaction by Transaction ID
        Route::get('/transaction/user/{id}', [TransactionController::class, 'user']); // Transactions by A User

        // Admin Routes
        
        Route::get('/admin/users/{all}', [AdminController::class, 'all_users']); // View All Users
        Route::patch('/admin/user/{id}', [AdminController::class, 'update_user']); // Edit a User
        Route::patch('/admin/referrer/{id}', [AdminController::class, 'update_referrer']); // Edit User Referrer
        Route::patch('/admin/user-kyc/{id}', [AdminController::class, 'update_kyc']); // Update KYC Status
        Route::post('/admin/user-kyc-update', [AdminController::class, 'update_user_kyc']); // Update KYC Status and User KYC Column
        Route::post('/admin/activate-user', [AdminController::class, 'act_user']); // Activate a user
        Route::post('/admin/deactivate-user', [AdminController::class, 'deact_user']); // DeActivate a user
        Route::post('/admin/paid/activate', [AdminController::class, 'act_paid']); // DeActivate PAID Status
        Route::post('/admin/paid/deactivate', [AdminController::class, 'deact_paid']); // DeActivate PAID Status
        Route::post('/admin/ban', [AdminController::class, 'ban']); // Ban a user
        Route::post('/admin/unban', [AdminController::class, 'unban']); // Unban a user
        Route::post('/admin/twofa', [AdminController::class, 'twoFA']); // Unlock 2FA for user
        Route::post('/admin/email/single', [AdminController::class, 'send_user_email']); // Send Email to Each User with Username
        Route::post('/admin/email/all/users', [AdminController::class, 'send_all_users_email']); // Send Email to All Users
        Route::patch('/admin/bank/{id}', [AdminController::class, 'bank_update']); // Update user bank details
        Route::get('/admin/user/ref/{id}', [AdminController::class, 'user_referrals']); // Get Users I Referred
        Route::get('/admin/user/ref-dep/{id}', [AdminController::class, 'user_referrals_deposit']); // Get Total Deposit of Users ID Referred
        Route::get('/admin/transaction/data', [TransactionController::class, 'site_transaction_data']); // All User Transaction Data


        Route::get('/admin/withdraws/{filter}', [WithdrawController::class, 'withdraws']); // All Withdrawals with filter tags
        Route::post('/admin/withdraw-update/', [WithdrawController::class, 'update_withdraws']); // Update Withdraws to , delete, pay, unpay
        Route::post('/admin/withdraw-mass/', [WithdrawController::class, 'mass_withdraws']); // Mass Withdrawals 

        // Make Auto Payments
        Route::post('/admin/auto-pay-alfacoin', [PaymentController::class, 'alfapay_pay_user']); // Make a Transfer to Another User


        //P2P
        Route::get('/admin/match/auto', [AdminController::class, 'auto_match']); // Auto Merge Feature
        Route::post('/admin/match/manual', [AdminController::class, 'manual_match']); // Manual Match/Merge Feature
        Route::get('/admin/pop/ban', [AdminController::class, 'block_no_pop']); // Ban All Donors with No POP in 24 hours
        Route::post('/admin/add/receiver', [AdminController::class, 'add_receiver']); // P2P Add User to Receivers
        Route::post('/admin/pledges/user', [AdminController::class, 'user_pledge_entries']); // View All User Pledge Entries (user_id, list[all, completed, progress, disabled])
        Route::get('/admin/donors/{filter}', [AdminController::class, 'all_donors']); // View All P2P Donors
        Route::get('/admin/receivers/{filter}', [AdminController::class, 'all_receivers']); // View All P2P Receivers

        
        
        
        
 });


// Login Route
Route::post('/login', [UserController::class, 'index']);

// Logout Route
Route::get('logout', [UserController::class, 'logout']);

// Active Sliders
Route::get('/slider/active', [SliderController::class, 'active']);

// Site Settings Without Authentication
Route::get('/settings', [SettingController::class, 'index']); // List Settings
