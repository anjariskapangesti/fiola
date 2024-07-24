<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\FolderAccess;
use App\Models\FolderAccessPath;
use App\Models\FolderAccessUser;
use App\Models\Department;
use App\Models\Folder;
use App\Models\SubFolder;
use App\Models\User;
use App\Models\Alert;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;
use Auth;

class FolderAccessController extends Controller
{
    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $folders = Folder::orderBy('name', 'ASC')->get();
        $subfolders = SubFolder::orderBy('name', 'ASC')->get();
        
        $auth = User::where('id', Auth::user()->id)
                                    ->whereNull('nohp')
                                    ->count(); 

        $data = FolderAccess::where('created_by', Auth::user()->id)
                            ->where(function($query) {
                                    $query->where('final_status', 'LIKE', '%Reject%')
                                        ->orWhere('final_status', 'Finished');
                            })
                            ->where('is_confirm', 0)
                            ->count();
        
        if ($auth > 0) {
            return redirect()->route('website.user.edit');
        } else if($data > 0){
            return redirect()->route('website.folder-access.list')->with('info', 'Please confirm!');
        }else{
            return view('website.pages.folder-access.create', compact(['departments', 'folders', 'subfolders']));
        }
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
        } elseif (Auth::user()->can('approve_mgr') || Auth::user()->can('approve_gm') || Auth::user()->can('approve_dir')  || Auth::user()->can('approve_vp') || Auth::user()->can('approve_pres')) {
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
    
            $user = Auth::user();
    
            $folderaccess = new FolderAccess();
            $folderaccess->no_reg = $no_reg;
            $folderaccess->purpose = $request->purpose;
            $folderaccess->created_by = $user->id;
            $folderaccess->created_dept = $user->departments->pluck('id')->first();
            $folderaccess->final_status = $finalStatus;
            $folderaccess->is_manager_approve = $isManagerApprove;
            $folderaccess->is_it_approve = $isItApprove;
            $folderaccess->is_it_mgr_approve = $isItManagerApprove;
            $folderaccess->manager_approval_date = $managerApprovalDate;
            $folderaccess->it_approval_date = $itApprovalDate;
            $folderaccess->it_mgr_approval_date = $itManagerApprovalDate;
            $folderaccess->save();
    
            for ($i = 0; $i < count($request->folder ); $i++) {
                FolderAccessPath::create([
                    'folder_access_id' => $folderaccess->id,
                    'folder' => $request->folder[$i],
                    'subfolder' => $request->subfolder[$i],
                    'subsubfolder' => $request->subsubfolder[$i],
                    'permission' => $request->permission[$i],
                ]);
            }

            for ($i = 0; $i < count($request->username ); $i++) {
                FolderAccessUser::create([
                    'folder_access_id' => $folderaccess->id,
                    'username' => $request->username[$i],
                    'department' => $request->department[$i],
                ]);
            }
    
            return redirect()->route('website.folder-access.list')->with('success', 'Create Successfully');
        } catch (Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function list()
    {
        return view('website.pages.folder-access.list');
    }

    public function list_ajax(Request $request)
    {
        $data = FolderAccess::where('created_by', Auth::user()->id)
                        ->join('public.users', 'form_folder_access.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_folder_access.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_folder_access.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_folder_access.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_folder_access.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_folder_access.finish_by', 'finish.id')
                        ->select('form_folder_access.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'DESC')
                        ->with('form_folder_access_path')
                        ->with('form_folder_access_user');

        return DataTables::eloquent($data)->make(true);
    }
    
    public function approve_form(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $folderaccess = FolderAccess::findOrFail($id);

        if ($type == 'confirm') {
            $folderaccess->is_confirm = 1;
        } else {
            $folderaccess->is_confirm = 0;
        }
        $folderaccess->save();

        return "Confirm Successfully";
    }

    public function delete_form(Request $request)
    {
        $id = $request->id;

        $folderaccess = FolderAccess::findOrFail($id);
        $folderaccess->delete();

        return "Delete Successfully";
    }
    
    // MGR //
    public function manager_approval()
    {
        return view('website.pages.folder-access.manager_approval');
    }

    public function manager_approval_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = FolderAccess::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
            ->where('final_status', 'created')
            ->join('public.users', 'form_folder_access.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_folder_access.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_folder_access.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_folder_access.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_folder_access.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_folder_access.finish_by', 'finish.id')
                        ->select('form_folder_access.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
            ->orderBy('created_at', 'ASC')
            ->with('form_folder_access_path')
            ->with('form_folder_access_user');

        return DataTables::eloquent($data)->make(true);
    }

    public function manager_approve(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $folderaccess = FolderAccess::findOrFail($id);

        if ($type == 'approve') {
            $folderaccess->is_manager_approve = 1;
            $folderaccess->final_status = 'Manager Approve';
            $folderaccess->manager_note = $request->manager_note;
            $folderaccess->manager_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $folderaccess->is_manager_approve = 0;
            $folderaccess->final_status = 'Manager Reject';
            $folderaccess->manager_note = $request->manager_note;
            $folderaccess->manager_approve_by = Auth::user()->id;
            $folderaccess->is_finish = 0;
            $folderaccess->is_confirm = 0;
            $return = "Reject Successfully";
        }
        $folderaccess->manager_approval_date = Carbon::now();
        $folderaccess->save();
        return $return;
    }

    public function manager_approved()
    {
        return view('website.pages.folder-access.manager_approved');
    }

    public function manager_approved_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = FolderAccess::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
            ->whereNotNull('is_manager_approve')
            ->join('public.users', 'form_folder_access.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_folder_access.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_folder_access.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_folder_access.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_folder_access.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_folder_access.finish_by', 'finish.id')
                        ->select('form_folder_access.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
            ->orderBy('manager_approval_date', 'DESC')
            ->with('form_folder_access_path')
            ->with('form_folder_access_user');

        return DataTables::eloquent($data)->make(true);
    }

    /// ITD APPROVE ///

    public function it_approval()
    {
        return view('website.pages.folder-access.it_approval');
    }

    public function it_approval_ajax(Request $request)
    {
        $data = FolderAccess::where('final_status', 'Manager Approve')
                        ->join('public.users', 'form_folder_access.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_folder_access.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_folder_access.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_folder_access.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_folder_access.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_folder_access.finish_by', 'finish.id')
                        ->select('form_folder_access.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'ASC')
                        ->with('form_folder_access_path')
                        ->with('form_folder_access_user');

        return DataTables::eloquent($data)->make(true);
    }

    public function it_approve(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $folderaccess = FolderAccess::findOrFail($id);
        $folderaccesspaths = FolderAccessPath::where('folder_access_id', $id)->get();
        $folderaccessusers = FolderAccessUser::where('folder_access_id', $id)->get();
        
        if ($type == 'approve') {
            $folderaccess->is_it_approve = 1;
            $folderaccess->final_status = 'IT Approve';
            $folderaccess->it_note = $request->it_note;
            $folderaccess->it_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $folderaccess->is_it_approve = 0;
            $folderaccess->final_status = 'IT Reject';
            $folderaccess->it_note = $request->it_note;
            $folderaccess->is_finish = 0;
            $folderaccess->is_confirm = 0;
            $folderaccess->it_approve_by = Auth::user()->id;
            $return = "Reject Successfully";
        }
        $folderaccess->it_approval_date = Carbon::now();
        $folderaccess->save();
        
        if ($request->notifikasi == 'Ya') {
            $isi = "FORM FOLDER ACCESS\n";
            $isi .= "*TUNGGU APPROVE IT MANAGER*";
            $isi .= "\n\nREQUESTOR";
            $isi .= "\nNama : *" . $folderaccess->createdBy->name . "*";
            $isi .= "\nDepartment : *" . $folderaccess->createdBy->departments->pluck('code')->implode(', ') . "*";
            $isi .= "\nPurpose : " . $folderaccess->purpose;
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
        return view('website.pages.folder-access.it_approved');
    }

    public function it_approved_ajax(Request $request)
    {
        $data = FolderAccess::whereNotNull('is_it_approve')
                        ->join('public.users', 'form_folder_access.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_folder_access.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_folder_access.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_folder_access.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_folder_access.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_folder_access.finish_by', 'finish.id')
                        ->select('form_folder_access.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('manager_approval_date', 'DESC')
                        ->with('form_folder_access_path')
                        ->with('form_folder_access_user');

        return DataTables::eloquent($data)->make(true);
    }

    /// ITD MGR APPROVE ///

    public function it_mgr_approval()
    {
        return view('website.pages.folder-access.it_mgr_approval');
    }

    public function it_mgr_approval_ajax(Request $request)
    {
        $data = FolderAccess::where('final_status', 'IT Approve')
                        ->join('public.users', 'form_folder_access.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_folder_access.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_folder_access.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_folder_access.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_folder_access.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_folder_access.finish_by', 'finish.id')
                        ->select('form_folder_access.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'ASC')
                        ->with('form_folder_access_path')
                        ->with('form_folder_access_user');

        return DataTables::eloquent($data)->make(true);
    }

    public function it_mgr_approve(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $folderaccess = FolderAccess::findOrFail($id);

        if ($type == 'approve') {
            $folderaccess->is_it_mgr_approve = 1;
            $folderaccess->final_status = 'IT MGR Approve';
            $folderaccess->it_mgr_note = $request->it_mgr_note;
            $folderaccess->it_mgr_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $folderaccess->is_it_mgr_approve = 0;
            $folderaccess->final_status = 'IT MGR Reject';
            $folderaccess->it_mgr_note = $request->it_mgr_note;
            $folderaccess->it_mgr_approve_by = Auth::user()->id;
            $folderaccess->is_finish = 0;
            $folderaccess->is_confirm = 0;
            $return = "Reject Successfully";
        }
        $folderaccess->it_mgr_approval_date = Carbon::now();
        $folderaccess->save();
        return $return;
    }

    public function it_mgr_approved()
    {
        return view('website.pages.folder-access.it_mgr_approved');
    }

    public function it_mgr_approved_ajax(Request $request)
    {
        $data = FolderAccess::whereNotNull('is_it_mgr_approve')
                        ->join('public.users', 'form_folder_access.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_folder_access.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_folder_access.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_folder_access.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_folder_access.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_folder_access.finish_by', 'finish.id')
                        ->select('form_folder_access.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'DESC')
                        ->with('form_folder_access_path')
                        ->with('form_folder_access_user');

        return DataTables::eloquent($data)->make(true);
    }

    /// EXECUTION ///

    public function execution()
    {
        return view('website.pages.folder-access.execution');
    }

    public function execution_ajax(Request $request)
    {
        $data = FolderAccess::whereIn('final_status', ['IT MGR Approve', 'On Progress'])
                        ->join('public.users', 'form_folder_access.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_folder_access.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_folder_access.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_folder_access.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_folder_access.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_folder_access.finish_by', 'finish.id')
                        ->select('form_folder_access.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'ASC')
                        ->with('form_folder_access_path')
                        ->with('form_folder_access_user');

        return DataTables::eloquent($data)->make(true);
    }

    public function execution_approve(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $folderaccess = FolderAccess::findOrFail($id);
        $folderaccessusers = FolderAccessUser::where('folder_access_id', $id)->get();
        $folderaccesspaths = FolderAccessPath::where('folder_access_id', $id)->get();

        $user = $folderaccess->createdBy;

        if ($type == 'approve') {
            $folderaccess->is_finish = 1;
            $folderaccess->is_confirm = 0;
            $folderaccess->finish_by = Auth::user()->id;
            $folderaccess->final_status = 'Finished';
            $folderaccess->finish_note = $request->finish_note;
            $folderaccess->finish_date = Carbon::now();
            $return = "Approve Successfully";
        } else if ($type == 'progress') {
            $folderaccess->is_on_progress = 1;
            $folderaccess->final_status = 'On Progress';
            $folderaccess->on_progress_note = $request->on_progress_note;
            $folderaccess->on_progress_by = Auth::user()->id;
            $folderaccess->on_progress_date = Carbon::now();
            $return = "Progress Successfully";
        } else {
            $folderaccess->is_finish = 0;
            $folderaccess->is_confirm = 0;
            $folderaccess->final_status = 'Rejected';
            $folderaccess->finish_note = $request->finish_note;
            $folderaccess->finish_by = Auth::user()->id;
            $folderaccess->finish_date = Carbon::now();
            $return = "Reject Successfully";
        }
        $folderaccess->save();

        if ($request->notifikasi == 'Ya') {
            $isi = "FORM FOLDER ACCESS\n\n";

            $isi .= "User : \n";
            $nouser = 1;
            foreach($folderaccessusers as $folderaccessuser)
            {
                $isi .= $nouser++ . ". " . $folderaccessuser->username . " - " . $folderaccessuser->department . "\n";
            }

            $isi .= "\nPath : \n";
            $nopath = 1;
            foreach($folderaccesspaths as $folderaccesspath)
            {
                if($folderaccesspath->subsubfolder !== null){
                    $subsubfolder = $folderaccesspath->subsubfolder . " - ";
                } else {
                    $subsubfolder = "";
                }
                $isi .= $nopath++ . ". " . $folderaccesspath->folder . " - " . $folderaccesspath->subfolder . " - " . $subsubfolder  . $folderaccesspath->permission . "\n";
            }
            
            $isi .= "\nPurpose : " . $folderaccess->purpose;
            
            $isi .= "\n\nStatus : *Finished*";
            
            $isi .= "\n\nManager Note : " . $folderaccess->manager_note;
            $isi .= "\nITD Note : " . $folderaccess->it_note;
            $isi .= "\nITD Manager Note : " . $folderaccess->it_mgr_note;
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
        return view('website.pages.folder-access.finished');
    }

    public function finished_ajax(Request $request)
    {
        $data = FolderAccess::whereNotNull('is_finish')
                        ->join('public.users', 'form_folder_access.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_folder_access.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_folder_access.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_folder_access.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_folder_access.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_folder_access.finish_by', 'finish.id')
                        ->select('form_folder_access.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'DESC')
                        ->with('form_folder_access_path')
                        ->with('form_folder_access_user');

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
