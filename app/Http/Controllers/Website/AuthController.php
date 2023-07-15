<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Http\Requests\Website\LoginRequest;

class AuthController extends Controller
{
    	/**
         * Get login page
         */
        public function showLoginPage()
        {
            if (auth()->user()) {
                return redirect('/');
            }

            return view('website.auth.login');
        }

        /**
         * Authenticate user
         */
        public function authenticate(LoginRequest $request)
        {
            $login = $request->input('email');
            $password = $request->input('password');
            
            // Periksa apakah login menggunakan NPK atau email
            $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'npk';
            
            $credentials = [
                $field => $login,
                'password' => $password
            ];
            // dd($credentials);
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
        
                if ($user->profileIncomplete()) {
                    return redirect()->route('website.user.edit');
                }
        
                return redirect()->route('website.home');
            }
        
            return redirect()->back()->withErrors(['unauthenticate' => 'Wrong email or NPK and password']);
        }

        // protected function sendFailedLoginResponse(Request $request)
        // {
        //     throw ValidationException::withMessages([
        //         $this->username() => [trans('auth.failed')],
        //     ])->redirectTo(route('login'))->withInput();
        // }

        /**
         * Logout
         */
        public function logout()
        {
            Auth::logout();

            return redirect()->route('website.auth.login');
        }
    }
