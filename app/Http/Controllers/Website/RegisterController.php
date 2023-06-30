<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Models\User;
use App\Models\Department;
use Spatie\Permission\Models\Permission;

class RegisterController extends Controller
{

    use RegistersUsers;


    protected $redirectTo = '/home';


    public function __construct()
    {
        
        $this->middleware('guest');
    }


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }


    protected function create(array $data)
    {
        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $permission = Permission::where('name','general')->first();

        $user->givePermissionTo($permission);

        return $user;
    }

    public function showRegisterForm()
    {
        
       return view('website.auth.register');
   }
}
