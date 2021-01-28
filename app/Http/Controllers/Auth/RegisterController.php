<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Uuid;

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
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'nomor_hp'=> ['required', 'regex:/^(^\+62|62|^08)(\d{3,4}-?){2}\d{3,4}$/'],
        ],
        [
            'nomor_hp.regex'=>'nomor telepon tidak sesuai',
            'password.min' =>'password minimal 8 karakter',
            'password.confirmed' => 'password dan konfirmasi password tidak sama',
            'email.unique' => 'Email telah terdaftar'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        $id = Uuid::generate(4)->string;
        return User::create([
            'id' => $id,
            'name' => $data['name'],
            'email' => $data['email'],
            'nomor_hp'=> $data['nomor_hp'],
            'password' => Hash::make($data['password']),
            'role' => 2
        ]);
    }
}
