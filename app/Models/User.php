<?php

namespace App\Models;

use App\Models\Bank;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Mail;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomResetPassword;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Exception;
use Illuminate\Support\Facades\Auth;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fname',
        'lname',
        'username',
        'email',
        'mobile',
        'balance',
        'dob',
        'act_time',
        'banned',
        'ver_status',
        'roles',
        'street',
        'city',
        'state',
        'post_code',
        'country',
        'tracked_country',
        'position',
        'referrer',
        'parent',
        'password',
        'kyc',
        'notif',
        'alert',
        'btc_address',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'twofa_type',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'roles' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];


    /***
     * @param string $role
     * @return $this
     */
    public function addRole(string $role)
    {
        $roles = $this->getRoles();
        $roles[] = $role;
        
        $roles = array_unique($roles);
        $this->setRoles($roles);

        return $this;
    }

    /**
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles)
    {
        $this->setAttribute('roles', $roles);
        return $this;
    }

    /***
     * @param $role
     * @return mixed
     */
    public function hasRole($role)
    {
        return in_array($role, $this->getRoles());
    }

    /***
     * @param $roles
     * @return mixed
     */
    public function hasRoles($roles)
    {
        $currentRoles = $this->getRoles();
        foreach($roles as $role) {
            if ( ! in_array($role, $currentRoles )) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        $roles = $this->getAttribute('roles');

        if (is_null($roles)) {
            $roles = [];
        }

        return $roles;
    }

    // All User Ticket Comments
    public function comments()
    {
        return $this->hasMany(TicketComment::class);
    }

    // Al User Tickets
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // All User KYC Data
    public function kyc()
    {
        return $this->hasOne(Kyc::class);
    }

    // User Bank Details
    public function bank_details()
    {
        return $this->hasOne(Bank::class, 'user_id', 'id');
    }

    // User level details
    public function level()
    {
        return $this->hasOne(UserLevel::class, 'user_id', 'id');
    }

    // Custom Reset Password
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token, $this->fname, $this->email));
    }

    // Custom Verification Email
    public function sendEmailVerificationNotification()
    {
        $this->notify(new EmailVerificationNotification($this->fname));
    }

    // Generate the User Login Code
    public static function generateCode($user_id)
    {
        $code = rand(100000, 999999);

        // Get User
        $user = User::whereId($user_id)->first();
        
        UserCode::updateOrCreate(
            [ 'user_id' => $user->id ],
            [ 'code' => $code ]
        );
        
        try {
            
            $details = [
                'title' => 'Login Verification Code',
                'code' => $code
            ];
                 // Get the Template
                 $email_template = EmailTemplate::whereSlug('email-login-verify')->first();
                    
                 $email_subject = $email_template->title;

             // Substitution Array
                 $var = array(
                     '%code%' => $details['code'],
                 );

                 $email_message = strtr($email_template->body, $var);
            
            // Send the Email with codes
            send_email($user->email, $details['title'], $email_message);
    
        } catch (Exception $e) {
            info("Error: ". $e->getMessage());
        }
    }


}
