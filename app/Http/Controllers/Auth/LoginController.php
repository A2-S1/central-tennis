<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);

        if (filter_var(env('RECAPTCHA_ENABLED', false), FILTER_VALIDATE_BOOLEAN)) {
            $token = $request->input('g-recaptcha-response');
            if (!$token) {
                return back()->withErrors(['captcha' => 'Validação reCAPTCHA é obrigatória.'])->throwResponse();
            }
            $secret = env('RECAPTCHA_SECRET_KEY');
            if ($secret) {
                $resp = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $secret,
                    'response' => $token,
                    'remoteip' => $request->ip(),
                ]);
                if (!$resp->ok() || !($resp->json()['success'] ?? false)) {
                    return back()->withErrors(['captcha' => 'Falha na verificação do reCAPTCHA.'])->throwResponse();
                }
            }
        }
    }
}
