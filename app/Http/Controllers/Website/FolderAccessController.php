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
                'no_reg' => 'unique',
                'username' => 'required',
                'folder' => 'required',
                'subfolder' => 'required',
                'permission' => 'required',
                'purpose' => 'required',
            ]);
    
            $year = date('y');
            $month = date('m');
            $lastForm = DB::table('form_folder_access')
                          ->select('no_reg')
                          ->orderBy('no_reg', 'desc')
                          ->first();
            $lastNumber = ($lastForm) ? substr($lastForm->no_reg, -3) : '000';
            
            $lastMonth = ($lastForm) ? substr($lastForm->no_reg, 6, 2) : '00';            
            if ($lastMonth !== $month){
                $lastNumber = '000';
            }            
            $newNumber = str_pad((intval($lastNumber) + 1), strlen($lastNumber), '0', STR_PAD_LEFT);            
            $no_reg = 'FAC/' . $year . $month . '/' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    
            $final_status = 'created';
            $user = Auth::user();
            // $folder_name = Folder::all();
    
            $folderaccess = new FolderAccess();
            $folderaccess->no_reg = $no_reg;
            $folderaccess->username = $request->username;
            $folderaccess->purpose = $request->purpose;
            $folderaccess->created_by = $user->id;
            $folderaccess->created_dept = $user->departments->pluck('id')->first();
            $folderaccess->final_status = $final_status;
            $folderaccess->save();
    
            for ($i = 0; $i < count($request->folder ); $i++) {
                FolderAccessPath::create([
                    'folder_access_id' => $folderaccess->id,
                    'folder' => $request->folder[$i],
                    'subfolder' => $request->subfolder[$i],
                    'permission' => $request->permission[$i],
                ]);
            }
    
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
                            ->select('form_folder_access.id', 'username', 
                                    ('form_folder_access.purpose'), ('users.name as creator_created_by'))
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
                                    ('form_folder_access.manager_approval_date as manager_approval_date'))
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
        $data = FolderAccess::join('users', 'form_folder_access.created_by', '=', 'users.id')
                            ->select('form_folder_access.id', 'username', 
                                    ('form_folder_access.purpose'), ('users.name as creator_created_by'))
                            ->where('final_status','Manager Approve')
                            ->orderBy('form_folder_access.id', 'desc')
                            ->with('form_folder_access_path')                                                
                            ->get();

        return DataTables::of($data)->make(true);
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

    public function show_data_it_approval()
    {
        $departments = Department::all();
        return view('website.pages.folder-access.show_data_it_approval', compact(['departments']));
    }

    public function show_data_it_approval_ajax(Request $request)
    {
        $data = FolderAccess::join('users', 'form_folder_access.created_by', '=', 'users.id')
                            ->select('form_folder_access.id', 'username', DB::Raw('form_folder_access.username as creator_username'), 
                                    ('form_folder_access.purpose as creator_purpose'), ('users.name as creator_created_by'),
                                    ('form_folder_access.it_approval_date as it_approval_date'))
                            ->where('is_it_approve','1')
                            ->orderBy('form_folder_access.id', 'desc')
                            ->with('form_folder_access_path');

        return DataTables::eloquent($data)->make(true);
    }

    /// ITD MGR APPROVE ///

    public function show_it_mgr_approval()
    {
        return view('website.pages.folder-access.approval_it_mgr');
    }

    public function show_it_mgr_approval_ajax(Request $request)
    {
        $data = FolderAccess::join('users', 'form_folder_access.created_by', '=', 'users.id')
                            ->select('form_folder_access.id', 'username', 
                                    ('form_folder_access.purpose'), ('users.name as creator_created_by'))
                            ->where('final_status','IT Approve')
                            ->orderBy('form_folder_access.id', 'desc')
                            ->with('form_folder_access_path')                                                
                            ->get();

        return DataTables::of($data)->make(true);
    }

    public function approve_it_mgr(Request $request)
    {
        $id=$request->id;
        $type=$request->type;
        $folderaccess = FolderAccess::findOrFail($id);
        if($type=='ok'){
            $folderaccess->is_it_mgr_approve=1;
            $folderaccess->final_status='IT MGR Approve';
        }else{
            $folderaccess->is_it_mgr_approve=0;
            $folderaccess->final_status='IT MGR Reject';
            $folderaccess->it_mgr_note=$request->it_mgr_note;
        }
        $folderaccess->it_mgr_approval_date= Carbon::now();
        $folderaccess->save();
        return "Request is Saved!";
    }

    public function show_data_it_mgr_approval()
    {
        $departments = Department::all();
        return view('website.pages.folder-access.show_data_it_mgr_approval', compact(['departments']));
    }

    public function show_data_it_mgr_approval_ajax(Request $request)
    {
        $data = FolderAccess::join('users', 'form_folder_access.created_by', '=', 'users.id')
                            ->select('form_folder_access.id', 'username', DB::Raw('form_folder_access.username as creator_username'), 
                                    ('form_folder_access.purpose as creator_purpose'), ('users.name as creator_created_by'),
                                    ('form_folder_access.it_mgr_approval_date as it_mgr_approval_date'))
                            ->where('is_it_mgr_approve','1')
                            ->orderBy('form_folder_access.id', 'desc')
                            ->with('form_folder_access_path');

        return DataTables::eloquent($data)->make(true);
    }

    /// EXECUTION ///

    public function show_execution()
    {
        return view('website.pages.folder-access.approval_execution');
    }

    public function show_execution_ajax(Request $request)
    {
        $data = FolderAccess::join('users', 'form_folder_access.created_by', '=', 'users.id')
                            ->select('form_folder_access.id', 'username', 
                                    ('form_folder_access.purpose'), ('users.name as creator_created_by'))
                            ->where('final_status','IT MGR Approve')
                            ->orderBy('form_folder_access.id', 'desc')
                            ->with('form_folder_access_path')                                                
                            ->get();

        return DataTables::of($data)->make(true);
    }

    public function approve_execution(Request $request)
    {
        $id=$request->id;
        $type=$request->type;
        $folderaccess = FolderAccess::findOrFail($id);
        if($type=='ok'){
            $folderaccess->is_finish=1;
            $folderaccess->final_status='Finished';
        }else{
            $folderaccess->is_finish=0;
            $folderaccess->final_status='Rejected';
            $folderaccess->finish_note=$request->finish_note;
        }
        $folderaccess->finish_date= Carbon::now();
        $folderaccess->save();
        return "Request is Saved!";
    }

    public function show_data_execution()
    {
        $departments = Department::all();
        return view('website.pages.folder-access.show_data_execution', compact(['departments']));
    }

    public function show_data_execution_ajax(Request $request)
    {
        $data = FolderAccess::join('users', 'form_folder_access.created_by', '=', 'users.id')
                            ->select('form_folder_access.id', 'username', DB::Raw('form_folder_access.username as creator_username'), 
                                    ('form_folder_access.purpose as creator_purpose'), ('users.name as creator_created_by'),
                                    ('form_folder_access.finish_date as finish_date'))
                            ->where('is_finish','1')
                            ->orderBy('form_folder_access.id', 'desc')
                            ->with('form_folder_access_path');

        return DataTables::eloquent($data)->make(true);
    }
}
