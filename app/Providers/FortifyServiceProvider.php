<?php

namespace App\Providers;

use App\Models\User;
use App\Models\UserCode;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Hash;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\ServiceProvider;
use App\Actions\Fortify\AuthenticateUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Controllers\UserController;
use Exception;
use Stevebauman\Location\Facades\Location;


class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        
        // Allow Username or Email Login
        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)
            ->orWhere('username', $request->email)
            ->first();
        
                if ($user &&
                    Hash::check($request->password, $user->password)) {
                        // Get the Country Details
                        $ip = $request->ip(); //Dynamic IP address get
                        if($ip == '127.0.0.1'){
                            $user['tracked_country'] = 'Untracked';
                        }else{
                            $data = Location::get($ip);
                            $user['tracked_country'] = $data->countryName;
                        }

                        // Save Last Seen
                        $user['last_seen'] = now();
                        $user->save();

                        

                        // Check if it's a p2p Site
                        if(settings()->p2p == 1){
                            // Check if Banning Registered but No Pledge is turned ON
                            if(p2p_rules()->nplbn == 1){
                                no_pledge_block($user->id);
                            }

                            // Check Users who haven't uploaded POP
                            UserController::block_no_pop($user->id);

                            // Check Users's who haven't gotten confrimed POP after upload
                            UserController::block_no_pop_approved($user->id);
                        }

                        // Let's Generate the Code
                        if(TRUE){
                            // User::generateCode($user->id);
                        }
                    return $user;
                }
        });

        
    }
}
