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

        $auth = User::where('id', Auth::user()->id)
                                    ->whereNull('nohp')
                                    ->count(); 

                                    $auth = User::where('id', Auth::user()->id)
                                    ->whereNull('nohp')
                                    ->count(); 
        
        $data = NewFolder::where('created_by', Auth::user()->id)->where('final_status', 'Finished')->where('is_confirm', 0)->count();
        if ($auth > 0) {
            return redirect()->route('website.user.edit');
        } else if($data > 0){
            return redirect()->route('website.new-folder.show_data_form')->with('info', 'Please confirm!');
        }else{
            return view('website.pages.new-folder.create', compact(['departments', 'folders']));
        }        
    }    

    public function store(Request $request)
    {
        if (Auth::user()->can('can_approve_mgr')) {
            $finalStatus = 'Manager Approve';
            $isManagerApprove = 1;
            $managerApprovalDate = Carbon::now();
        } elseif (Auth::user()->can('can_approve_executives')) {
            $finalStatus = 'Manager Approve';
            $isManagerApprove = 1;
            $managerApprovalDate = Carbon::now();
        } else {
            $finalStatus = 'created';
            $isManagerApprove = null;
            $managerApprovalDate = null;
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
            $newfolder->is_manager_approve = $isManagerApprove;
            $newfolder->manager_approval_date = $managerApprovalDate;
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
            return redirect()->route('website.new-folder.show_data_form')->with('success', 'Success Create Form');
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
                                    ('form_new_folder.final_status'),
                                    ('form_new_folder.manager_note'),
                                    ('form_new_folder.it_note'),
                                    ('form_new_folder.it_mgr_note'),
                                    ('form_new_folder.finish_note'),
                                    ('form_new_folder.is_confirm'))
                            ->where('created_by', Auth::user()->id)
                            ->orderBy('form_new_folder.id', 'desc')
                            ->with('form_new_folder_access');                            

        return DataTables::of($data)->make(true);
    }

    public function approve_form(Request $request)
    {
        $id=$request->id;
        $type=$request->type;
        $newfolder = NewFolder::findOrFail($id);
        if($type=='ok'){
            $newfolder->is_confirm=1;
        }else{
            $newfolder->is_confirm=0;
        }
        $newfolder->save();
        return "Confirm is Saved!";
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
            $newfolder->manager_note=$request->manager_note;
        }else{
            $newfolder->is_manager_approve=0;
            $newfolder->final_status='Manager Reject';
            $newfolder->manager_note=$request->manager_note;
            $newfolder->is_finish=0;
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
                                    ('form_new_folder.manager_approval_date as manager_approval_date'),
                                    ('form_new_folder.manager_note'))
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
                                    ('form_new_folder.purpose'), ('users.name as creator_created_by'),
                                    ('form_new_folder.manager_note'))
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
            $newfolder->it_note=$request->it_note;
        }else{
            $newfolder->is_it_approve=0;
            $newfolder->final_status='IT Reject';
            $newfolder->it_note=$request->it_note;
            $newfolder->is_finish=0;
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
                                    ('form_new_folder.it_approval_date as it_approval_date'),
                                    ('form_new_folder.manager_note'),
                                    ('form_new_folder.it_note'))
                            ->where('is_it_approve','1')
                            ->orderBy('form_new_folder.id', 'desc')
                            ->with('form_new_folder_access');                            

        return DataTables::of($data)->make(true);
    }

    /// ITD MGR APPROVE ///

    public function show_it_mgr_approval()
    {
        return view('website.pages.new-folder.approval_it_mgr');
    }

    public function show_it_mgr_approval_ajax(Request $request)
    {
        $data = NewFolder::join('users', 'form_new_folder.created_by', '=', 'users.id')
                            ->select('form_new_folder.id', 'foldername', 
                                    ('form_new_folder.mainpath'),
                                    ('form_new_folder.purpose'), ('users.name as creator_created_by'),
                                    ('form_new_folder.manager_note'),
                                    ('form_new_folder.it_note'))
                            ->where('final_status','IT Approve')
                            ->orderBy('form_new_folder.id', 'desc')
                            ->with('form_new_folder_access')                                                
                            ->get();

        return DataTables::of($data)->make(true);
    }

    public function approve_it_mgr(Request $request)
    {
        $id=$request->id;
        $type=$request->type;
        $newfolder = NewFolder::findOrFail($id);
        if($type=='ok'){
            $newfolder->is_it_mgr_approve=1;
            $newfolder->final_status='IT MGR Approve';
            $newfolder->it_mgr_note=$request->it_mgr_note;
        }else{
            $newfolder->is_it_mgr_approve=0;
            $newfolder->final_status='IT MGR Reject';
            $newfolder->it_mgr_note=$request->it_mgr_note;
            $newfolder->is_finish=0;
        }
        $newfolder->it_mgr_approval_date= Carbon::now();
        $newfolder->save();
        return "Request is Saved!";
    }

    public function show_data_it_mgr_approval()
    {
        $departments = Department::all();
        return view('website.pages.new-folder.show_data_it_mgr_approval', compact(['departments']));
    }

    public function show_data_it_mgr_approval_ajax(Request $request)
    {
        $data = NewFolder::join('users', 'form_new_folder.created_by', '=', 'users.id')
                            ->select('form_new_folder.id', 'foldername', 
                                    ('form_new_folder.mainpath'),
                                    ('form_new_folder.purpose'), ('users.name as creator_created_by'),
                                    ('form_new_folder.it_mgr_approval_date'),
                                    ('form_new_folder.manager_note'),
                                    ('form_new_folder.it_note'),
                                    ('form_new_folder.it_mgr_note'))
                            ->where('is_it_mgr_approve','1')
                            ->orderBy('form_new_folder.id', 'desc')
                            ->with('form_new_folder_access');                            

        return DataTables::of($data)->make(true);
    }

    /// EXECUTION ///

    public function show_execution()
    {
        return view('website.pages.new-folder.approval_execution');
    }

    public function show_execution_ajax(Request $request)
    {
        $data = NewFolder::join('users', 'form_new_folder.created_by', '=', 'users.id')
                            ->select('form_new_folder.id', 'foldername', 
                                    ('form_new_folder.mainpath'),
                                    ('form_new_folder.purpose'), ('users.name as creator_created_by'),
                                    ('form_new_folder.manager_note'),
                                    ('form_new_folder.it_note'),
                                    ('form_new_folder.it_mgr_note'))
                            ->where('final_status','IT MGR Approve')
                            ->orderBy('form_new_folder.id', 'desc')
                            ->with('form_new_folder_access')                                                
                            ->get();

        return DataTables::of($data)->make(true);
    }

    public function approve_execution(Request $request)
    {
        $id=$request->id;
        $type=$request->type;
        $newfolder = NewFolder::findOrFail($id);
        $newfolderaccesss = NewFolderAccess::where('new_folder_id', $id)->get();

        $user = $newfolder->createdBy;

        if($type=='ok'){
            $isi = "FORM NEW FOLDER\n\n";                
        
            $isi .= "\nNew Folder Name : *" . $request->foldername ."*";   
            $isi .= "\nMain Path : *" . $newfolder->mainpath ."*";      
    
            foreach ($newfolderaccesss as $newfolderaccess) {
                $isi .= "\n\nUsername : " . $newfolderaccess->username;
                $isi .= "\nDepartment : " . $newfolderaccess->department;
                $isi .= "\nPermission : " . $newfolderaccess->permission;
            }
            $isi .= "\n\nPurpose : " . $newfolder->purpose;
    
            $isi .= "\n\nStatus : Finished";
    
            $isi .= "\n\nManager Note : " . $newfolder->manager_note;
            $isi .= "\nITD Note : " . $newfolder->it_note;
            $isi .= "\nITD Manager Note : " . $newfolder->it_mgr_note;
            $isi .= "\n\nNote : " . $request->finish_note;
    
            $nomor = $user->nohp;;
    
            $token = "v2n49drKeWNoRDN4jgqcdsR8a6bcochcmk6YphL6vLcCpRZdV1";
                $message = sprintf("----------FIOLA----------%c$isi%c------------------------- ", 10, 10);
                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://app.ruangwa.id/api/send_message',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'token='.$token.'&number='.$nomor.'&message='.$message,
                ));
                $response = curl_exec($curl);
                curl_close($curl);
            $newfolder->foldername=$request->foldername;
            $newfolder->is_confirm=0;
            $newfolder->is_finish=1;
            $newfolder->final_status='Finished';
            $newfolder->finish_note=$request->finish_note;
        }else{
            $newfolder->is_finish=0;
            $newfolder->final_status='Rejected';
            $newfolder->finish_note=$request->finish_note;
        }
        $newfolder->finish_date= Carbon::now();
        $newfolder->save();
        return "Request is Saved!";
    }

    public function show_data_execution()
    {
        $departments = Department::all();
        return view('website.pages.new-folder.show_data_execution', compact(['departments']));
    }

    public function show_data_execution_ajax(Request $request)
    {
        $data = NewFolder::join('users', 'form_new_folder.created_by', '=', 'users.id')
                            ->select('form_new_folder.id', 'foldername', 
                                    ('form_new_folder.mainpath'),
                                    ('form_new_folder.purpose'), ('users.name as creator_created_by'),
                                    ('form_new_folder.final_status'),
                                    ('form_new_folder.finish_date'),
                                    ('form_new_folder.manager_note'),
                                    ('form_new_folder.it_note'),
                                    ('form_new_folder.it_mgr_note'),
                                    ('form_new_folder.finish_note'))
                            ->where('is_finish','1')->orWhere('is_finish','0')
                            ->orderBy('form_new_folder.id', 'desc')
                            ->with('form_new_folder_access');                            

        return DataTables::of($data)->make(true);
    }
}
