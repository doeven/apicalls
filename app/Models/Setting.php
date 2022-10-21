<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = array( 
        'title', 
        'tagline', 
        'email',
        'mobile',
        'address',
        'email_toggle',
        'sms_toggle',
        'status',
        'about_text',
        'logo',
        'facebook',
        'twitter',
        'linkedin',
        'instagram',
        'youtube',
        'pinterest',
        'currency',
        'symbol',
        'theme',
        'color_prim',
        'color_sec',
        'footer_credit',
        'footer_desc',
        'user_level_bonus',
        'ref_deep_bonus',
        'paid_act',
        'p2p',
        'p2p_ex',
        'min_wd',
        'dp',
        'rinv',
        'license'
    );

}
