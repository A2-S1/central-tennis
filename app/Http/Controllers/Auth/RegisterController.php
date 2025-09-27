<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

            'plays_tennis' => ['nullable', 'in:0,1'],
            'tennis_level' => ['nullable', 'in:iniciante,intermediario,avancado,especial'],
            'usual_playing_location' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:20'],
            'public_profile' => ['nullable', 'in:0,1'],
        ]);

        if (filter_var(env('RECAPTCHA_ENABLED', false), FILTER_VALIDATE_BOOLEAN)) {
            $validator->after(function ($v) use ($data) {
                $token = $data['g-recaptcha-response'] ?? null;
                if (!$token) {
                    $v->errors()->add('captcha', 'Validação reCAPTCHA é obrigatória.');
                    return;
                }
                $secret = env('RECAPTCHA_SECRET_KEY');
                if ($secret) {
                    $resp = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                        'secret' => $secret,
                        'response' => $token,
                    ]);
                    if (!$resp->ok() || !($resp->json()['success'] ?? false)) {
                        $v->errors()->add('captcha', 'Falha na verificação do reCAPTCHA.');
                    }
                }
            });
        }

        return $validator;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // gerar slug único
        $base = Str::slug($data['name']);
        $slug = $base ?: ('user-'.Str::random(6));
        $n = 1;
        while (DB::table('users')->where('slug', $slug)->exists()) {
            $slug = $base.'-'.(++$n);
        }

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'plays_tennis' => isset($data['plays_tennis']) ? (bool) $data['plays_tennis'] : false,
            'tennis_level' => $data['tennis_level'] ?? null,
            'usual_playing_location' => $data['usual_playing_location'] ?? null,
            'city' => $data['city'] ?? null,
            'state' => $data['state'] ?? null,
            'bio' => $data['bio'] ?? null,
            'public_profile' => isset($data['public_profile']) ? (bool) $data['public_profile'] : false,
            'slug' => $slug,
        ]);
    }
}
