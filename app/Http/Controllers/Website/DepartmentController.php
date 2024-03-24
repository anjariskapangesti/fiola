<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Department;
use Carbon\Carbon;
use DataTables;
use Auth;

class DepartmentController extends Controller
{
    public function create()
    {
        $departments = Department::orderBy('name', 'ASC')->get();
        
        return view('website.pages.department.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            // 'code' => 'required|unique:departments,code' ,
            'name' => 'required' ,            
        ], [
            'code.unique' => 'CODE already exists',
            // ...
        ]);
        
        try
        {
            Department::create([
                'code' => $request->code ,
                'name' => $request->name ,                
            ]);
            // return redirect()->back()->with('success', 'Success Add Department');
            return redirect('/department/show_data_department')->with('success', 'Success Add Department');
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function show_data_department()
    {
        return view('website.pages.department.show_data_department');
    }

    public function show_data_department_ajax(Request $request)
    {
        $data = Department::orderBy('name', 'ASC');
        
        return DataTables::eloquent($data)->make(true);
    }

    public function edit(Request $request)
    {
        $id = $request->id;

        $departments = Department::find($id);
        if (Auth::user()->can('can_master')) {
            $departments->update([
                'name' => $request->name,
                'code' => $request->code,
            ]);
            
            return "Department updated successfully";
        }

        return "Error";
    }

    public function destroy(Request $request)
    {
        $id = $request->id;

        $departments = Department::find($id);
        if (Auth::user()->can('can_master')) {
            $departments->delete();
            
            return "Department deleted successfully";
        }

        return "Error";
    }
}
