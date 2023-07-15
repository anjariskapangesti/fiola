<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Department;
use App\Models\Folder;
use App\Models\NewFolder;
use App\Models\NewFolderAccess;
use App\Models\User;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;
use Auth;

class NewFolderController extends Controller
{
    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $folders = Folder::orderBy('name', 'ASC')->get();

        // dd($subfolders);
        return view('website.pages.new-folder.create', compact(['departments', 'folders']));
    }    

    public function store(Request $request)
    {
        if (Auth::user()->can('can_approve_mgr')) {
            $finalStatus = 'Manager Approve';
        } else {
            $finalStatus = 'created';
        }
        
        try {
            $request->validate([
                'no_reg' => 'unique',
                'foldername' => 'required',
                'mainpath' => 'required',
                'username' => 'required',
                'department' => 'required',
                'permission' => 'required',
                'purpose' => 'required',
            ]);

            $year = date('y');
            $month = date('m');
            $lastForm = DB::table('form_new_folder')
                          ->select('no_reg')
                          ->orderBy('no_reg', 'desc')
                          ->first();
            $lastNumber = ($lastForm) ? substr($lastForm->no_reg, -3) : '000';
            
            $lastMonth = ($lastForm) ? substr($lastForm->no_reg, 6, 2) : '00';            
            if ($lastMonth !== $month){
                $lastNumber = '000';
            }            
            $newNumber = str_pad((intval($lastNumber) + 1), strlen($lastNumber), '0', STR_PAD_LEFT);            
            $no_reg = 'NEF/' . $year . $month . '/' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

            // DB::transaction(function () use ($request) {
            //     $final_status = 'created';
            //     $user = Auth::user();                

            //     $newfolder = NewFolder::create([
            //         'no_reg' => $no_reg,
            //         'foldername' => $request->foldername,
            //         'mainpath' => $request->mainpath,
            //         'purpose' => $request->purpose,
            //         'created_by' => $user->id,
            //         'created_dept' => $user->departments->pluck('id')->first(),
            //         'final_status' => $final_status,
            //     ]);
            //     $newfolder->save();
            // $final_status = 'created';
            $user = Auth::user();
            // $folder_name = Folder::all();
    
            $newfolder = new NewFolder();
            $newfolder->no_reg = $no_reg;
            $newfolder->foldername = $request->foldername;
            $newfolder->mainpath = $request->mainpath;
            $newfolder->purpose = $request->purpose;
            $newfolder->created_by = $user->id;
            $newfolder->created_dept = $user->departments->pluck('id')->first();
            $newfolder->final_status = $finalStatus;
            $newfolder->save();

            for ($i = 0; $i < count($request->username ); $i++) {
                NewFolderAccess::create([
                    'new_folder_id' => $newfolder->id,
                    'username' => $request->username[$i],
                    'department' => $request->department[$i],
                    'permission' => $request->permission[$i],
                ]);
            }
            // });
            return redirect()->back()->with('success', 'Success Create Form');
        } catch (Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function show_data_form()
    {
        $departmetns = Department::all();
        $folders = Folder::orderBy('name', 'ASC')->get();
        // dd($subfolders);
        return view('website.pages.new-folder.show_data_form', compact(['departmetns', 'folders']));
    }

    public function show_data_form_ajax(Request $request)
    {
        $data = NewFolder::join('users', 'form_new_folder.created_by', '=', 'users.id')
                            ->select('form_new_folder.id', 'foldername', 
                                    ('form_new_folder.mainpath'),
                                    ('form_new_folder.purpose'), ('users.name as creator_created_by'),
                                    ('form_new_folder.final_status'))
                            ->where('created_by', Auth::user()->id)
                            ->orderBy('form_new_folder.id', 'desc')
                            ->with('form_new_folder_access');                            

        return DataTables::of($data)->make(true);
    }

    // MGR //
    public function show_manager_approval()
    {
        return view('website.pages.new-folder.approval_manager');
    }

    public function show_manager_approval_ajax()
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = NewFolder::join('users', 'form_new_folder.created_by', '=', 'users.id')
                            ->select('form_new_folder.id', 'foldername', DB::Raw('form_new_folder.foldername as creator_foldername'), 
                                    ('form_new_folder.mainpath as creator_mainpath'),
                                    ('form_new_folder.purpose as creator_purpose'), ('users.name as creator_created_by'),)
                            ->where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                    $query->where('created_dept', $firstDepartmentId)
                                    ->orWhere('created_dept', $lastDepartmentId);
                                    })
                            ->where('final_status','created')
                            ->orderBy('form_new_folder.id', 'desc')
                            ->with('form_new_folder_access')                                                
                            ->get();

        return DataTables::of($data)->make(true);
    }

    public function approve_manager(Request $request)
    {
        $id=$request->id;
        
        $type=$request->type;
        
        $newfolder = NewFolder::findOrFail($id);
        
        if($type=='ok'){
            $newfolder->is_manager_approve=1;
            $newfolder->final_status='Manager Approve';
        }else{
            $newfolder->is_manager_approve=0;
            $newfolder->final_status='Manager Reject';
            $newfolder->manager_note=$request->manager_note;
        }
        $newfolder->manager_approval_date= Carbon::now();
        $newfolder->save();
        return "Request is Saved!";        
    }

    public function show_data_manager_approval()
    {
        $departmetns = Department::all();
        $folders = Folder::orderBy('name', 'ASC')->get();
        // dd($subfolders);
        return view('website.pages.new-folder.show_data_manager_approval', compact(['departmetns', 'folders']));
    }

    public function show_data_manager_approval_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = NewFolder::join('users', 'form_new_folder.created_by', '=', 'users.id')
                            ->select('form_new_folder.id', 'foldername', 
                                    ('form_new_folder.mainpath'),
                                    ('form_new_folder.purpose as creator_purpose'), ('users.name as creator_created_by'),
                                    ('form_new_folder.manager_approval_date as manager_approval_date'))
                            ->where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                    $query->where('created_dept', $firstDepartmentId)
                                    ->orWhere('created_dept', $lastDepartmentId);
                                    })
                            ->where('is_manager_approve','1')
                            ->orderBy('form_new_folder.id', 'desc')
                            ->with('form_new_folder_access');                            

        return DataTables::of($data)->make(true);
    }

    /// ITD APPROVE ///

    public function show_it_approval()
    {
        return view('website.pages.new-folder.approval_it');
    }

    public function show_it_approval_ajax(Request $request)
    {
        $data = NewFolder::join('users', 'form_new_folder.created_by', '=', 'users.id')
                            ->select('form_new_folder.id', 'foldername', DB::Raw('form_new_folder.foldername as creator_foldername'), 
                                    ('form_new_folder.mainpath as creator_mainpath'),
                                    ('form_new_folder.purpose'), ('users.name as creator_created_by'),)
                            ->where('final_status','Manager Approve')
                            ->orderBy('form_new_folder.id', 'desc')
                            ->with('form_new_folder_access')                                                
                            ->get();

        return DataTables::of($data)->make(true);
    }

    public function approve_it(Request $request)
    {
        $id=$request->id;
        $type=$request->type;
        $newfolder = NewFolder::findOrFail($id);
        if($type=='ok'){
            $newfolder->is_it_approve=1;
            $newfolder->final_status='IT Approve';
        }else{
            $newfolder->is_it_approve=0;
            $newfolder->final_status='IT Reject';
            $newfolder->it_note=$request->it_note;
        }
        $newfolder->it_approval_date= Carbon::now();
        $newfolder->save();
        return "Request is Saved!";
    }

    public function show_data_it_approval()
    {
        $departments = Department::all();
        return view('website.pages.new-folder.show_data_it_approval', compact(['departments']));
    }

    public function show_data_it_approval_ajax(Request $request)
    {
        $data = NewFolder::join('users', 'form_new_folder.created_by', '=', 'users.id')
                            ->select('form_new_folder.id', 'foldername', 
                                    ('form_new_folder.mainpath'),
                                    ('form_new_folder.purpose'), ('users.name as creator_created_by'),
                                    ('form_new_folder.it_approval_date as it_approval_date'))
                            ->where('is_it_approve','1')
                            ->orderBy('form_new_folder.id', 'desc')
                            ->with('form_new_folder_access');                            

        return DataTables::of($data)->make(true);
    }

    
}
