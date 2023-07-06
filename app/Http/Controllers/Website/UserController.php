<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
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
        $departments = Department::orderBy('name', 'ASC')->get();
        $roles = Role::all();
        $permissions = Permission::all();
        // dd($permissions);
        
        return view('website.pages.user.create', compact('departments', 'roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required' ,
            'dept_id' => 'required',
            'email' => 'required|unique:users,email',

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
                'dept_id' => $request->dept_id,
            ]);

            // $permission = Permission::findOrFail($request->input('permission_id'));
            // $user->givePermissionTo($permission);

            if ($request->has('permission_id')) {
                // Mengambil permission yang diperlukan
                $permission = Permission::findOrFail($request->input('permission_id'));
        
                // Menetapkan permission kepada user
                $user->givePermissionTo($permission);
        
                // Mengisi model_has_permissions
                $permissionId = $request->input('permission_id');
                $modelType = 'App\Models\User';
                $modelId = $user->id;
        
                DB::table('model_has_permissions')->insert([
                    'permission_id' => $permissionId,
                    'model_type' => $modelType,
                    'model_id' => $modelId,
                ]);
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
        return view('website.pages.user.show_data_user');
    }

    public function show_data_user_ajax(Request $request)
    {
        // return Auth::user()->dept_id;
        $data = User::orderBy('name', 'DESC')
                    ->join('department', 'users.dept_id', '=', 'department.id')
                    ->select('users.*', 'department.name as dept_name');;
        // return $data;
        return DataTables::eloquent($data)->make(true);
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return redirect()->back()->with('error', 'Delete Item');
    }
}
