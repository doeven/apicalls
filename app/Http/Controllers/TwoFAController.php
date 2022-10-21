<?php
  
namespace App\Http\Controllers;
  
use App\Models\UserCode;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
  
class TwoFAController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        return view('2fa');
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function store(Request $request)
    {
        $request->validate([
            'code'=>'required',
        ]);
  
        $find = UserCode::where('user_id', auth()->user()->id)
                        ->where('code', $request->code)
                        ->where('updated_at', '>=', now()->subMinutes(2))
                        ->first();
  
        if (!is_null($find)) {
            Session::put('user_2fa', auth()->user()->id);
            return redirect()->route('home');
        }
  
        return 'You entered wrong code.';
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function resend()
    {
        $user = Auth::user();
        // auth()->user()->generateCode();
        User::generateCode($user->id);
  
        return 'We sent you code on your email.';
    }
}