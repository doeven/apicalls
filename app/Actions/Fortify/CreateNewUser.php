<?php

namespace App\Actions\Fortify;

use App\Models\Bank;
use App\Models\User;
use App\Models\UserTree;
use App\Models\UserLevel;
use App\Models\EmailTemplate;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        // return $input;
        Validator::make($input, [
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'fname' => ['required', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
        ])->validate();

        // Get Referrer if Available
        if($input['referrer'] != ''){
            $ref = User::whereUsername($input['referrer'])->first();    
        }else{
            $ref = User::whereUsername('admin')->first();    
        }
        $pick = rand(0,1);
        if($pick == 0){ $position = 'L'; }else{$position = 'R';}
        $parent = get_last_child($ref['id'], $position);


        $create_user = User::create([
            'username' => $input['username'],
            'fname' => $input['fname'],
            'lname' => $input['lname'],
            'email' => $input['email'],
            'referrer' => $ref->id,
            'parent' => $parent,
            'position' => $position,
            'password' => Hash::make($input['password']),
        ]);

        

        if($create_user){
            // Create Level Entry
            UserLevel::create([
                'user_id' => $create_user->id,
                'level' => 1,
                'members' => 0,
                'amount' => 0,
                'status' => 0
            ]);

            // Create Tree Entry
            UserTree::create([
                'user_id' => $create_user->id,
                'left_paid' => 0,
                'right_paid' => 0,
                'left_free' => 0,
                'right_free' => 0,
                'left_bv' => 0,
                'right_bv' => 0
            ]);

            // Create Bank Info Entry
            Bank::create([
                'user_id' => $create_user->id
            ]);

            // Update the Parent and Leg
            update_member_below($create_user->id, 'FREE');

            // Check if Activation is Enabled
            if(settings()->paid_act == 1 ){
                activate_user($create_user->id);
            }

            // Create Account Notification Email
                $email_address = $create_user->email;
                    // Get the Template
                    $email_template = EmailTemplate::whereSlug('welcome')->first();
                    
                    $email_subject = $email_template->title;

                // Substitution Array
                    $var = array(
                        '%firstname%' => $create_user->fname,
                        '%username%' => $create_user->username,
                    );

                    $email_message = strtr($email_template->body, $var);

                    // send_email($email_address, $email_subject, $email_message); // Send Email
                    

            return $create_user;
        }
    }
}