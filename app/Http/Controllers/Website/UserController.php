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
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;

use Alqaj\Organization\Traits\HasDepartments;

class UserController extends Controller
{
    use HasRoles;
    use HasPermissions;

    use HasDepartments;

    public function create()
    {
        
        $departments = Department::all()->sortBy('name')->pluck('name', 'id');

        $permissions = Permission::all()->sortBy('name')->pluck('name', 'id');
        
        return view('website.pages.user.create', compact('departments', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required' ,
            'email' => 'unique:users,email',
            'permissions' => 'nullable|array',
            // 'permissions.*' => 'exists:permissions,id',
            'departments' => 'nullable|array',
            // 'departments.*' => 'exists:departments,id',

        ], [
            'email.unique' => 'Email already exists',
        ]);

        try
        {
            $user = User::create([
                'name' => $request->name ,
                'password' => bcrypt($request->password) ,
                'npk' => $request->npk ,
            ]);
            foreach ($request->input('departments') as $departmentId) {
                $user->departments()->attach($departmentId);
                // $user->departments()->attach($departmentId, ['model_type' => 'App\Models\User']);
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

    public function list()
    {
        return view('website.pages.user.list');
    }

    public function list_ajax(Request $request)
    {
        $data = User::whereHas('permissions', function ($query) {
                $query->where('permissions.name', 'apps_fiola');
            })
            ->select(
                'users.*',
                DB::raw('GROUP_CONCAT(DISTINCT departments.code SEPARATOR \', \') as department_codes'),
                DB::raw('GROUP_CONCAT(permissions.name SEPARATOR \', \') as permission_names')
            )
            ->join('model_has_departments', 'users.id', 'model_has_departments.model_id')
            ->join('departments', 'model_has_departments.department_id', 'departments.id')
            ->join('model_has_permissions', 'users.id', 'model_has_permissions.model_id')
            ->join('permissions', 'model_has_permissions.permission_id', 'permissions.id')
            ->groupBy('users.id')
            ->orderBy('users.name', 'ASC');
    
        return DataTables::eloquent($data)->make(true);
    }

    //  $data = Account::orderBy('id', 'DESC')
    //                     ->where('created_by', Auth::user()->id)
    //                     ->join('users', 'form_account.created_by', '=', 'users.id')
    //                     ->select('form_account.*', 'users.name as user_name');

    //     return DataTables::eloquent($data)->make(true);

    public function edit()
    {
        $user = Auth::user();

        if ($user->profileIncomplete()) {
            session()->flash('incomplete', 'Please complete your data.');
        }

        return view('website.pages.user.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:4|confirmed',
            'nohp' => 'nullable|string|max:14',
            'npk' => 'nullable|string|min:6',
        ]);

        $user->update([
            'npk' => $request->npk,
            'name' => $request->name,
            'email' => $request->email,
            'nohp' => $request->nohp,
            'password' => $request->change_password ? Hash::make($request->password) : $user->password,
        ]);

        if ($user->profileIncomplete()) {
            Session::flash('incomplete', 'Please complete your data!!!');
        } else {
            Session::forget('incomplete');
            Session::flash('complete', 'Your data is complete, enjoy using our website.');
        }

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $user = User::find($id);
        if (Auth::user()->can('can_master')) {
            $user->delete();
            
            return "User deleted successfully";
        }

        return "Error";
    }
}
