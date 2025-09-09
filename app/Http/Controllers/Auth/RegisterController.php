<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

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
            'phone' => 'required',
            'city' => 'required|string',
            'state' => 'required|string',
            'joining_date' => 'required|string',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    //Function for submit user
    protected function create(array $data) {
        //Get last employee code
        $lastEmployee = User::OrderBy('ID', 'DESC')->first();
        //Check if employee code exist or not
        if ($lastEmployee && $lastEmployee->employee_code) {
            //Remove leading zeros and increment
            $lastCode = intval($lastEmployee->employee_code);
            $newCode = str_pad($lastCode + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newCode = '0001';
        }
        //Create user
        return User::create([
            'employee_code' => $newCode,
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'city' => $data['city'],
            'state' => $data['state'],
            'joining_date' => $data['joining_date'],
            'status' => 'Pending',
        ]);
    }

    //Function for register after created account
    protected function registered(Request $request, $user) {
        //logout user after registration 
        $this->guard()->logout();
        //Redirect back with success message
        return redirect()->route('login')
            ->with('success', 'Your account has been created successfully. Please wait for manager approval before login.')
            ->with('openSignup', true);
    }

    //Function for validation eror
    public function register(Request $request) {
        try {
            $this->validator($request->all())->validate();
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator, 'register')
                ->withInput()
                ->with('openSignup', true); 
        }
        $user = $this->create($request->all());
        return $this->registered($request, $user);
    }
}
