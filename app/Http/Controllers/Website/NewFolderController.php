<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\NewFolder;
use App\Models\NewFolderPath;
use App\Models\NewFolderUser;
use App\Models\Folder;
use App\Models\Department;
use App\Models\User;
use App\Models\Alert;

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

        $data = NewFolder::where('created_by', Auth::user()->id)
                            ->where(function($query) {
                                    $query->where('final_status', 'LIKE', '%Reject%')
                                        ->orWhere('final_status', 'Finished');
                            })
                            ->where('is_confirm', 0)
                            ->count();
        
        if ($auth > 0) {
            return redirect()->route('website.user.edit');
        } else if($data > 0){
            return redirect()->route('website.new-folder.list')->with('info', 'Please confirm!');
        }else{
            return view('website.pages.new-folder.create', compact(['departments', 'folders']));
        }
    }

    public function store(Request $request)
    {
        $isManagerApprove = null;
        $managerApprovalDate = null;
        $isItApprove = null;
        $itApprovalDate = null;
        $isItManagerApprove = null;
        $itManagerApprovalDate = null;

        if (Auth::user()->hasDepartment('ITD') && Auth::user()->can('approve_mgr')) {
            $finalStatus = 'IT MGR Approve';
            $isItManagerApprove = 1;
            $itManagerApprovalDate = Carbon::now();
        } elseif (Auth::user()->can('approve_mgr') || Auth::user()->can('approve_gm') || Auth::user()->can('approve_vp') || Auth::user()->can('approve_pres')) {
            $finalStatus = 'Manager Approve';
            $isManagerApprove = 1;
            $managerApprovalDate = Carbon::now();
        } elseif (Auth::user()->hasDepartment('ITD')) {
            $finalStatus = 'IT Approve';
            $isItApprove = 1;
            $itApprovalDate = Carbon::now();
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
            $no_reg = 'NFS/' . $year . $month . '/' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    
            $user = Auth::user();
    
            $newfolder = new NewFolder();
            $newfolder->no_reg = $no_reg;
            $newfolder->purpose = $request->purpose;
            $newfolder->created_by = $user->id;
            $newfolder->created_dept = $user->departments->pluck('id')->first();
            $newfolder->final_status = $finalStatus;
            $newfolder->is_manager_approve = $isManagerApprove;
            $newfolder->is_it_approve = $isItApprove;
            $newfolder->is_it_mgr_approve = $isItManagerApprove;
            $newfolder->manager_approval_date = $managerApprovalDate;
            $newfolder->it_approval_date = $itApprovalDate;
            $newfolder->it_mgr_approval_date = $itManagerApprovalDate;
            $newfolder->save();
    
            for ($i = 0; $i < count($request->foldername ); $i++) {
                NewFolderPath::create([
                    'new_folder_id' => $newfolder->id,
                    'foldername' => $request->foldername[$i],
                    'mainpath' => $request->mainpath[$i],
                ]);
            }

            for ($i = 0; $i < count($request->username ); $i++) {
                NewFolderUser::create([
                    'new_folder_id' => $newfolder->id,
                    'username' => $request->username[$i],
                    'department' => $request->department[$i],
                    'permission' => $request->permission[$i],
                ]);
            }
    
            return redirect()->route('website.new-folder.list')->with('success', 'Create Successfully');
        } catch (Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function list()
    {
        return view('website.pages.new-folder.list');
    }

    public function list_ajax(Request $request)
    {
        $data = NewFolder::where('created_by', Auth::user()->id)
                        ->join('public.users', 'form_new_folder.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_new_folder.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_new_folder.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_new_folder.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_new_folder.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_new_folder.finish_by', 'finish.id')
                        ->select('form_new_folder.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'DESC')
                        ->with('form_new_folder_path')
                        ->with('form_new_folder_user');

        return DataTables::eloquent($data)->make(true);
    }
    
    public function approve_form(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $newfolder = NewFolder::findOrFail($id);

        if ($type == 'confirm') {
            $newfolder->is_confirm = 1;
        } else {
            $newfolder->is_confirm = 0;
        }
        $newfolder->save();

        return "Confirm Successfully";
    }

    public function delete_form(Request $request)
    {
        $id = $request->id;

        $newfolder = NewFolder::findOrFail($id);
        $newfolder->delete();

        return "Delete Successfully";
    }
    
    // MGR //
    public function manager_approval()
    {
        return view('website.pages.new-folder.manager_approval');
    }

    public function manager_approval_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = NewFolder::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
            ->where('final_status', 'created')
            ->join('public.users', 'form_new_folder.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_new_folder.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_new_folder.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_new_folder.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_new_folder.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_new_folder.finish_by', 'finish.id')
                        ->select('form_new_folder.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
            ->orderBy('created_at', 'ASC')
            ->with('form_new_folder_path')
            ->with('form_new_folder_user');

        return DataTables::eloquent($data)->make(true);
    }

    public function manager_approve(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $newfolder = NewFolder::findOrFail($id);

        if ($type == 'approve') {
            $newfolder->is_manager_approve = 1;
            $newfolder->final_status = 'Manager Approve';
            $newfolder->manager_note = $request->manager_note;
            $newfolder->manager_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $newfolder->is_manager_approve = 0;
            $newfolder->final_status = 'Manager Reject';
            $newfolder->manager_note = $request->manager_note;
            $newfolder->manager_approve_by = Auth::user()->id;
            $newfolder->is_finish = 0;
            $newfolder->is_confirm = 0;
            $return = "Reject Successfully";
        }
        $newfolder->manager_approval_date = Carbon::now();
        $newfolder->save();
        return $return;
    }

    public function manager_approved()
    {
        return view('website.pages.new-folder.manager_approved');
    }

    public function manager_approved_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = NewFolder::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
            ->whereNotNull('is_manager_approve')
            ->join('public.users', 'form_new_folder.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_new_folder.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_new_folder.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_new_folder.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_new_folder.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_new_folder.finish_by', 'finish.id')
                        ->select('form_new_folder.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
            ->orderBy('manager_approval_date', 'DESC')
            ->with('form_new_folder_path')
            ->with('form_new_folder_user');

        return DataTables::eloquent($data)->make(true);
    }

    /// ITD APPROVE ///

    public function it_approval()
    {
        return view('website.pages.new-folder.it_approval');
    }

    public function it_approval_ajax(Request $request)
    {
        $data = NewFolder::where('final_status', 'Manager Approve')
                        ->join('public.users', 'form_new_folder.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_new_folder.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_new_folder.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_new_folder.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_new_folder.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_new_folder.finish_by', 'finish.id')
                        ->select('form_new_folder.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'ASC')
                        ->with('form_new_folder_path')
                        ->with('form_new_folder_user');

        return DataTables::eloquent($data)->make(true);
    }

    public function it_approve(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $newfolder = NewFolder::findOrFail($id);
        $newfolderpaths = NewFolderPath::where('new_folder_id', $id)->get();
        $newfolderusers = NewFolderUser::where('new_folder_id', $id)->get();
        
        if ($type == 'approve') {
            $newfolder->is_it_approve = 1;
            $newfolder->final_status = 'IT Approve';
            $newfolder->it_note = $request->it_note;
            $newfolder->it_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $newfolder->is_it_approve = 0;
            $newfolder->final_status = 'IT Reject';
            $newfolder->it_note = $request->it_note;
            $newfolder->is_finish = 0;
            $newfolder->is_confirm = 0;
            $newfolder->it_approve_by = Auth::user()->id;
            $return = "Reject Successfully";
        }
        $newfolder->it_approval_date = Carbon::now();
        $newfolder->save();
        
        if ($request->notifikasi == 'Ya') {
            $isi = "FORM NEW FOLDER\n";
            $isi .= "*TUNGGU APPROVE IT MANAGER*";
            $isi .= "\n\nREQUESTOR";
            $isi .= "\nNama : *" . $newfolder->createdBy->name . "*";
            $isi .= "\nDepartment : *" . $newfolder->createdBy->departments->pluck('code')->implode(', ') . "*";
            $isi .= "\nPurpose : " . $newfolder->purpose;
            $isi .= "\n\nNote : Dear Pak Ferry, Mohon untuk dicek tunggu approve pada FIOLA. Terimakasih";

            $isi .= "\n\nApproved ITD by : " . Auth::user()->name;

            $nomors = Alert::where('role', 'IT Manager')->get();

            foreach ($nomors as $nomor) {
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
                    CURLOPT_POSTFIELDS => 'token=' . $token . '&number=' . $nomor->nohp . '&message=' . $message,
                ));

                $response = curl_exec($curl);
                curl_close($curl);
            }
        }
        return $return;
    }

    public function it_approved()
    {
        return view('website.pages.new-folder.it_approved');
    }

    public function it_approved_ajax(Request $request)
    {
        $data = NewFolder::whereNotNull('is_it_approve')
                        ->join('public.users', 'form_new_folder.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_new_folder.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_new_folder.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_new_folder.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_new_folder.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_new_folder.finish_by', 'finish.id')
                        ->select('form_new_folder.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('manager_approval_date', 'DESC')
                        ->with('form_new_folder_path')
                        ->with('form_new_folder_user');

        return DataTables::eloquent($data)->make(true);
    }

    /// ITD MGR APPROVE ///

    public function it_mgr_approval()
    {
        return view('website.pages.new-folder.it_mgr_approval');
    }

    public function it_mgr_approval_ajax(Request $request)
    {
        $data = NewFolder::where('final_status', 'IT Approve')
                        ->join('public.users', 'form_new_folder.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_new_folder.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_new_folder.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_new_folder.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_new_folder.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_new_folder.finish_by', 'finish.id')
                        ->select('form_new_folder.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'ASC')
                        ->with('form_new_folder_path')
                        ->with('form_new_folder_user');

        return DataTables::eloquent($data)->make(true);
    }

    public function it_mgr_approve(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $newfolder = NewFolder::findOrFail($id);

        if ($type == 'approve') {
            $newfolder->is_it_mgr_approve = 1;
            $newfolder->final_status = 'IT MGR Approve';
            $newfolder->it_mgr_note = $request->it_mgr_note;
            $newfolder->it_mgr_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $newfolder->is_it_mgr_approve = 0;
            $newfolder->final_status = 'IT MGR Reject';
            $newfolder->it_mgr_note = $request->it_mgr_note;
            $newfolder->it_mgr_approve_by = Auth::user()->id;
            $newfolder->is_finish = 0;
            $newfolder->is_confirm = 0;
            $return = "Reject Successfully";
        }
        $newfolder->it_mgr_approval_date = Carbon::now();
        $newfolder->save();
        return $return;
    }

    public function it_mgr_approved()
    {
        return view('website.pages.new-folder.it_mgr_approved');
    }

    public function it_mgr_approved_ajax(Request $request)
    {
        $data = NewFolder::whereNotNull('is_it_mgr_approve')
                        ->join('public.users', 'form_new_folder.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_new_folder.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_new_folder.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_new_folder.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_new_folder.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_new_folder.finish_by', 'finish.id')
                        ->select('form_new_folder.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'DESC')
                        ->with('form_new_folder_path')
                        ->with('form_new_folder_user');

        return DataTables::eloquent($data)->make(true);
    }

    /// EXECUTION ///

    public function execution()
    {
        return view('website.pages.new-folder.execution');
    }

    public function execution_ajax(Request $request)
    {
        $data = NewFolder::whereIn('final_status', ['IT MGR Approve', 'On Progress'])
                        ->join('public.users', 'form_new_folder.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_new_folder.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_new_folder.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_new_folder.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_new_folder.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_new_folder.finish_by', 'finish.id')
                        ->select('form_new_folder.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'ASC')
                        ->with('form_new_folder_path')
                        ->with('form_new_folder_user');

        return DataTables::eloquent($data)->make(true);
    }

    public function execution_approve(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $newfolder = NewFolder::findOrFail($id);
        $newfolderusers = NewFolderUser::where('new_folder_id', $id)->get();
        $newfolderpaths = NewFolderPath::where('new_folder_id', $id)->get();

        $user = $newfolder->createdBy;

        if ($type == 'approve') {
            $newfolder->is_finish = 1;
            $newfolder->is_confirm = 0;
            $newfolder->finish_by = Auth::user()->id;
            $newfolder->final_status = 'Finished';
            $newfolder->finish_note = $request->finish_note;
            $newfolder->finish_date = Carbon::now();
            $return = "Approve Successfully";
        } else if ($type == 'progress') {
            $newfolder->is_on_progress = 1;
            $newfolder->final_status = 'On Progress';
            $newfolder->on_progress_note = $request->on_progress_note;
            $newfolder->on_progress_by = Auth::user()->id;
            $newfolder->on_progress_date = Carbon::now();
            $return = "Progress Successfully";
        } else {
            $newfolder->is_finish = 0;
            $newfolder->is_confirm = 0;
            $newfolder->final_status = 'Rejected';
            $newfolder->finish_note = $request->finish_note;
            $newfolder->finish_by = Auth::user()->id;
            $newfolder->finish_date = Carbon::now();
            $return = "Reject Successfully";
        }
        $newfolder->save();

        if ($request->notifikasi == 'Ya') {
            $isi = "FORM FOLDER ACCESS\n\n";
            
            $isi .= "Path : \n";
            $nopath = 1;
            foreach($newfolderpaths as $newfolderpath)
            {
                $isi .= $nopath++ . ". " . $newfolderpath->mainpath . " - " . $newfolderpath->foldername . "\n";
            }

            $isi .= "\nUser : \n";
            $nouser = 1;
            foreach($newfolderusers as $newfolderuser)
            {
                $isi .= $nouser++ . ". " . $newfolderuser->username . " - " . $newfolderuser->department . " - " . $newfolderuser->permission . "\n";
            }
            
            $isi .= "\nPurpose : " . $newfolder->purpose;
            
            $isi .= "\n\nStatus : *Finished*";
            
            $isi .= "\n\nManager Note : " . $newfolder->manager_note;
            $isi .= "\nITD Note : " . $newfolder->it_note;
            $isi .= "\nITD Manager Note : " . $newfolder->it_mgr_note;
            $isi .= "\n\nFinish Note : " . $request->finish_note;
            
            $isi .= "\n\nExecution by : " . Auth::user()->name;
            
            $nomor = $user->nohp;
            
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
                CURLOPT_POSTFIELDS => 'token=' . $token . '&number=' . $nomor . '&message=' . $message,
            ));
            $response = curl_exec($curl);
            curl_close($curl);
        }

        return $return;
    }

    public function finished()
    {
        return view('website.pages.new-folder.finished');
    }

    public function finished_ajax(Request $request)
    {
        $data = NewFolder::whereNotNull('is_finish')
                        ->join('public.users', 'form_new_folder.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_new_folder.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_new_folder.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_new_folder.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_new_folder.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_new_folder.finish_by', 'finish.id')
                        ->select('form_new_folder.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'DESC')
                        ->with('form_new_folder_path')
                        ->with('form_new_folder_user');

        return DataTables::eloquent($data)->make(true);
    }

    public function get_data_subfolder(Request $request)
    {
        $data['subfolders'] = SubFolder::where('folder_id', $request->folder_id)
                                    ->orderBy('id')
                                    ->get(['name']);

        return response()->json($data);
    }
}
