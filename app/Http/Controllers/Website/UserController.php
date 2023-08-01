<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Department;
use Carbon\Carbon;
use DataTables;
use Auth;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{

    public function create()
    {
        
        $departments = Department::all()->sortBy('name')->pluck('name', 'id');

        $permissions = Permission::all()->sortBy('id')->pluck('name', 'id');
        
        return view('website.pages.user.create', compact('departments', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required' ,
            'email' => 'required|unique:users,email',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
            'departments' => 'nullable|array',
            'departments.*' => 'exists:departments,id',

        ], [
            'email.unique' => 'Email already exists',
            // ...
        ]);

        try
        {
            $user = User::create([
                'name' => $request->name ,
                'password' => bcrypt($request->password) ,
                'email' => $request->email ,
            ]);
            foreach ($request->input('departments') as $departmentId) {
                $user->departments()->attach($departmentId, ['model_type' => 'App\Models\User']);
            }
            // $permission = Permission::findOrFail($request->input('permission_id'));
            // $user->givePermissionTo($permission);

            if ($request->has('permissions')) {
                // Mengambil permission yang diperlukan
                // $permission = Permission::findOrFail($request->input('permission_id'));
        
                // Menetapkan permission kepada user
                // $user->givePermissionTo($request->input('permissions'));
        
                // // Mengisi model_has_permissions
                // $permissionId = $request->input('permission_id');
                // $modelType = 'App\Models\User';
                // $modelId = $user->id;
        
                // DB::table('model_has_permissions')->insert([
                //     'permission_id' => $permissionId,
                //     'model_type' => $modelType,
                //     'model_id' => $modelId,
                // ]);

                $permissions = $request->input('permissions', []);
                // $departments = $request->input('departments', []);
                // $user->departments()->sync($validatedData['departments']);
                $user->syncPermissions($permissions);
            }
            
            return redirect()->back()->with('success', 'Success Create User');
            
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function show_data_user()
    {
        $users = User::all();
        
        return view('website.pages.user.show_data_user', compact('users'));
    }

    public function show_data_user_ajax(Request $request)
    {
        // return Auth::user()->dept_id;
        $data = User::orderBy('name', 'ASC');
                    // ->join('departments', 'users.dept_id', '=', 'department.id')
                    // ->select('users.*', 'department.name as dept_name');;
        // return $data;
        return DataTables::eloquent($data)->make(true);
    }

    public function edit()
    {
        $user = Auth::user();
        $departments = Department::pluck('name', 'id');

        $permissions = Permission::pluck('name', 'id');

        if ($user->profileIncomplete()) {
            session()->flash('incomplete', 'Please complete your data.');
        }

        return view('website.pages.user.edit', compact('user', 'departments', 'permissions'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:4|confirmed',
            'nohp' => 'nullable|string|max:14',
            'npk' => 'nullable|string|min:6',
        ]);

        $user->npk = $request->input('npk');
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if ($request->has('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->nohp = $request->input('nohp');
        $user->save();

        if ($user->profileIncomplete()) {
            Session::flash('incomplete', 'Please complete your data!!!');
        } else {
            Session::forget('incomplete');
            Session::flash('complete', 'Your data is complete, enjoy using our website.');
        }

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function destroy(User $user)
    {
        if (Auth::user()->can('can_master')) {
            // Hapus user
            $user->where('id', '3')->delete();
            
            return redirect()->back()->with('success', 'User deleted successfully.');
        }

        return redirect()->back()->with('error', 'You do not have permission to delete this user.');
    }
}
