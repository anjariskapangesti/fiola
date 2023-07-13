<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\FolderAccess;
use App\Models\FolderAccessPath;
use App\Models\Department;
use App\Models\Folder;
use App\Models\SubFolder;
use App\Models\User;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;
use Auth;

class FolderAccessController extends Controller
{
    public function create()
    {
        $departments = Department::all();
        $folders = Folder::orderBy('name', 'ASC')->get();
        $subfolders = SubFolder::orderBy('name', 'ASC')->get();
        // dd($subfolders);
        return view('website.pages.folder-access.create', compact(['departments', 'folders', 'subfolders']));
    }

    public function subfolder_ajax(Request $request)
    {
        $data['subfolders'] = SubFolder::join('folders', 'folders.id','subfolders.folder_id')
        ->where("folders.name", $request->folder_id)
                                ->orderBy('subfolders.name')
                                ->get(["subfolders.name", "subfolders.id"]);
  
        return response()->json($data);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required',
                'folder' => 'required',
                'subfolder' => 'required',
                'permission' => 'required',
                'purpose' => 'required',
            ]);


            DB::transaction(function () use ($request) {
                $final_status = 'created';
                $user = Auth::user();
                $folder_name = Folder::all();
            //    dd($folder_name);

                $folderaccess = FolderAccess::create([
                    'username' => $request->username,
                    'purpose' => $request->purpose,
                    'created_by' => $user->id,
                    'created_dept' => $user->departments->pluck('id')->first(),
                    'final_status' => $final_status,
                ]);
                $folderaccess->save();

                for ($i = 0; $i < count($request->folder ); $i++) {
                    FolderAccessPath::create([
                        'folder_access_id' => $folderaccess->id,
                        'folder' => $request->folder[$i],
                        'subfolder' => $request->subfolder[$i],
                        'permission' => $request->permission[$i],
                    ]);
                }
            });
            return redirect()->back()->with('success', 'Success Create Form');
        } catch (Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function show_manager_approval()
    {
        return view('website.pages.folder-access.approval_manager');
    }

    public function show_manager_approval_ajax()
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = FolderAccess::join('users', 'form_folder_access.created_by', '=', 'users.id')
                            ->select('form_folder_access.id', 'username', DB::Raw('form_folder_access.username as creator_username'), 
                                    ('form_folder_access.purpose as creator_purpose'), ('users.name as creator_created_by'))
                            ->where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                    $query->where('created_dept', $firstDepartmentId)
                                    ->orWhere('created_dept', $lastDepartmentId);
                                    })
                            ->where('final_status','created')
                            ->orderBy('form_folder_access.id', 'desc')
                            ->with('form_folder_access_path')                                                
                            ->get();

        return DataTables::of($data)->make(true);
    }

    public function approve_manager(Request $request)
    {
        $id=$request->id;
        
        $type=$request->type;
        
        $folderaccess = FolderAccess::findOrFail($id);
        
        if($type=='ok'){
            $folderaccess->is_manager_approve=1;
            $folderaccess->final_status='Manager Approve';
        }else{
            $folderaccess->is_manager_approve=0;
            $folderaccess->final_status='Manager Reject';
            $folderaccess->manager_note=$request->manager_note;
        }
        $folderaccess->manager_approval_date= Carbon::now();
        $folderaccess->save();
        return "Request is Saved!";        
    }

    public function show_data_manager_approval()
    {
        $departmetns = Department::all();
        $folders = Folder::orderBy('name', 'ASC')->get();
        $subfolders = SubFolder::orderBy('name', 'ASC')->get();
        // dd($subfolders);
        return view('website.pages.folder-access.show_data_manager_approval', compact(['departmetns', 'folders', 'subfolders']));
    }

    public function show_data_manager_approval_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = FolderAccess::join('users', 'form_folder_access.created_by', '=', 'users.id')
                            ->select('form_folder_access.id', 'username', DB::Raw('form_folder_access.username as creator_username'), 
                                    ('form_folder_access.purpose as creator_purpose'), ('users.name as creator_created_by'),
                                    ('form_folder_access.manager_approval_date as manager_date'))
                            ->where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                    $query->where('created_dept', $firstDepartmentId)
                                    ->orWhere('created_dept', $lastDepartmentId);
                                    })
                            ->where('is_manager_approve','1')
                            ->orderBy('form_folder_access.id', 'desc')
                            ->with('form_folder_access_path');

        return DataTables::eloquent($data)->make(true);
    }

    /// ITD APPROVE ///

    public function show_it_approval()
    {
        return view('website.pages.folder-access.approval_it');
    }

    public function show_it_approval_ajax(Request $request)
    {
        $data = FolderAccess::where('final_status','Manager Approve');
        return DataTables::eloquent($data)->make(true);
    }

    public function show_data_it_approval()
    {
        $depts = Department::all();
        return view('website.pages.folder-access.show_data_it_approval', compact(['depts']));
    }

    public function show_data_it_approval_ajax(Request $request)
    {
        // return Auth::user()->dept_id;
        $data = FolderAccess::where('created_dept', Auth::user()->dept_id)->where('is_it_approve','1');
        // return $data;
        return DataTables::eloquent($data)->make(true);
    }

    public function approve_it(Request $request)
    {
        $id=$request->id;
        $type=$request->type;
        $folderaccess = FolderAccess::findOrFail($id);
        if($type=='ok'){
            $folderaccess->is_it_approve=1;
            $folderaccess->final_status='IT Approve';
        }else{
            $folderaccess->is_it_approve=0;
            $folderaccess->final_status='IT Reject';
            $folderaccess->it_note=$request->it_note;
        }
        $folderaccess->it_approval_date= Carbon::now();
        $folderaccess->save();
        return "Request is Saved!";
    }
}
