<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Project;
use App\Models\User;
use App\Models\Alert;
use App\Models\Device;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;
use Auth;

class ProjectController extends Controller
{
    public function create()
    {
        $devices = Device::all();

        $auth = User::where('id', Auth::user()->id)
                                    ->whereNull('nohp')
                                    ->count();        

        $data = Project::where('created_by', Auth::user()->id)->where('final_status', 'Finished')->where('is_confirm', 0)->count();
        if ($auth > 0) {
            return redirect()->route('website.user.edit');
        } else if($data > 0){
            return redirect()->route('website.project.show_data_form')->with('info', 'Please confirm!');
        }else{
            return view('website.pages.project.create', compact('devices'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_reg' => 'unique',
            'npk' => 'required' ,          
            'fullname' => 'required' ,
            'department' => 'required' ,
            'phone' => 'required' ,
            'nama_project' => 'required' ,
        ]);

        $year = date('y');
        $month = date('m');
        $lastForm = DB::table('form_project')
                      ->select('no_reg')
                      ->orderBy('no_reg', 'desc')
                      ->first();
        $lastNumber = ($lastForm) ? substr($lastForm->no_reg, -3) : '000';
        
        $lastMonth = ($lastForm) ? substr($lastForm->no_reg, 6, 2) : '00';            
        if ($lastMonth !== $month){
            $lastNumber = '000';
        }            
        $newNumber = str_pad((intval($lastNumber) + 1), strlen($lastNumber), '0', STR_PAD_LEFT);            
        $no_reg = 'PRJ/' . $year . $month . '/' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

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

        try
        {
            if ($request->hasFile('lampiran')) {
                    $photoExtension = $request->lampiran->getClientOriginalExtension();
                    $photoFileName = 'PRJ_' . $year . $month . '_' . str_pad($newNumber, 3, '0', STR_PAD_LEFT) . '.' . $photoExtension;
                    $photoPath = $request->lampiran->storeAs('lampiran', $photoFileName, 'public');
            }  

            $alatSelected = $request->input('alat');
            $qtySelected = $request->input('qty');

            $combinedDescriptions = [];

            if (is_array($alatSelected) && is_array($qtySelected)) {
                if (count($alatSelected) === count($qtySelected)) {
                    foreach ($alatSelected as $index => $alat) {
                        $qty = isset($qtySelected[$index]) ? $qtySelected[$index] : '';
                        $combinedDescriptions[] = $alat . ' | ' . $qty . ' Unit';
                    }
                }
            }

            $combinedDescriptionString = implode("\n", $combinedDescriptions);

            $form_project = Project::create([
                'no_reg' => $no_reg,
                'npk' => $request->npk ,
                'fullname' => $request->fullname ,
                'department' => $request->department ,
                'phone' => $request->phone ,
                'aplikasi' => $request->aplikasi ,
                'nama_project' => $request->nama_project ,
                'lampiran' => $photoFileName,
                'kondisi_sebelum' => $request->kondisi_sebelum ,
                'kondisi_target' => $request->kondisi_target ,
                'benefit' => $request->benefit ,
                'alat' => $combinedDescriptionString,
                'created_by' => Auth::user()->id,
                'created_dept' => Auth::user()->departments->pluck('id')->first(),
                'final_status' => $finalStatus,
                'is_manager_approve' => $isManagerApprove,
                'manager_approval_date' => $managerApprovalDate,            
            ]);
            $form_project->save();

            return redirect()->route('website.project.show_data_form')->with('success', 'Success Create Form');
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function show_data_form()
    {
        return view('website.pages.project.show_data_form');
    }

    public function show_data_form_ajax(Request $request)
    {
        $data = Project::orderBy('id', 'DESC')
                        ->where('created_by', Auth::user()->id)
                        ->join('users', 'form_project.created_by', '=', 'users.id')
                        ->select('form_project.*', 'users.name as user_name');

        return DataTables::eloquent($data)->make(true);
    }

    public function approve_form(Request $request)
    {
        $id=$request->id;
        $type=$request->type;
        $project = Project::findOrFail($id);
        if($type=='ok'){
            $project->is_confirm=1;
        }else{
            $project->is_confirm=0;
        }
        $project->save();
        return "Confirm is Saved!";
    }

    // MGR //
    public function show_manager_approval()
    {
        return view('website.pages.project.approval_manager');
    }

    public function show_manager_approval_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();
        
        $data = Project::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                            $query->where('created_dept', $firstDepartmentId)
                                ->orWhere('created_dept', $lastDepartmentId);
                        })
                        ->where('final_status', 'created')
                        ->join('users', 'form_project.created_by', '=', 'users.id')
                        ->select('form_project.*', 'users.name as user_name');

        return DataTables::eloquent($data)->make(true);
    }

    public function show_data_manager_approval()
    {
        return view('website.pages.project.show_data_manager_approval');
    }

    public function show_data_manager_approval_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();
        
        $data = Project::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                            $query->where('created_dept', $firstDepartmentId)
                                ->orWhere('created_dept', $lastDepartmentId);
                        })
                        ->where('is_manager_approve','1')
                        ->join('users', 'form_project.created_by', '=', 'users.id')
                        ->select('form_project.*', 'users.name as user_name');

        return DataTables::eloquent($data)->make(true);
    }

    public function approve_manager(Request $request)
    {
        $id=$request->id;
        
        $type=$request->type;
        
        $project = Project::findOrFail($id);
        
        if($type=='ok'){
            $project->is_manager_approve=1;
            $project->final_status='Manager Approve';
            $project->manager_note=$request->manager_note;
        }else{
            $project->is_manager_approve=0;
            $project->final_status='Manager Reject';
            $project->manager_note=$request->manager_note;
            $project->is_finish=0;
        }
        $project->manager_approval_date= Carbon::now();
        $project->save();
        return "Request is Saved!";        
    }

    /// ITD APPROVE ///
    public function show_it_approval()
    {
        return view('website.pages.project.approval_it');
    }

    public function show_it_approval_ajax(Request $request)
    {
        $data = Project::where('final_status','Manager Approve')
                        ->join('users', 'form_project.created_by', '=', 'users.id')
                        ->select('form_project.*', 'users.name as user_name');

        return DataTables::eloquent($data)->make(true);
    }

    public function show_data_it_approval()
    {
        return view('website.pages.project.show_data_it_approval');
    }

    public function show_data_it_approval_ajax(Request $request)
    {
        $data = Project::where('is_it_approve','1')
                        ->join('users', 'form_project.created_by', '=', 'users.id')
                        ->select('form_project.*', 'users.name as user_name');
        
        return DataTables::eloquent($data)->make(true);
    }

    public function approve_it(Request $request)
    {
        $id=$request->id;
        $type=$request->type;
        $project = Project::findOrFail($id);
        if($type=='ok'){
            $isi = "FORM Request Project\n";
            $isi .= "*TUNGGU APPROVE IT MANAGER*";
            $isi .= "\n\nREQUESTOR";
            $isi .= "\n*Nama* : " . $project->fullname;        
            
            $isi .= "\n*Department* : " . $project->department;
            $isi .= "\n\n*Nama Project* : " . $project->nama_project;
            $isi .= "\n*Kondisi Sebelum Improvement* : " . $project->kondisi_sebelum;
            $isi .= "\n*Kondisi yang diharapkan* : " . $project->kondisi_target;
            $isi .= "\n*Benefit yang didapat* : " . $project->benefit;
            $isi .= "\n*Additional Support Device* : " . $project->alat;
            $isi .= "\n\n*Note* : Dear Pak Ferry, Mohon untuk dicek tunggu approve pada FIOLA. Terimakasih";

            $isi .= "\n\n*Approved ITD by* : " . Auth::user()->name;
            
            $nomorhpModel = new Alert();
            $nomorhp = $nomorhpModel->getNoHpItMgr();

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
                CURLOPT_POSTFIELDS => 'token='.$token.'&number='.$nomorhp.'&message='.$message,
                ));
                $response = curl_exec($curl);
                curl_close($curl);

            $project->is_it_approve=1;
            $project->final_status='IT Approve';
            $project->it_note=$request->it_note;
            $project->it_approve_by=Auth::user()->id;
        }else{
            $project->is_it_approve=0;
            $project->final_status='IT Reject';
            $project->it_note=$request->it_note;
            $project->is_finish=0;
            $project->it_approve_by=Auth::user()->id;
        }
        $project->it_approval_date= Carbon::now();
        $project->save();
        return "Request is Saved!";
    }

    /// IT MGR ///
    public function show_it_mgr_approval()
    {
        return view('website.pages.project.approval_it_mgr');
    }

    public function show_it_mgr_approval_ajax(Request $request)
    {
        $data = Project::where('final_status','IT Approve')
                        ->join('users', 'form_project.created_by', '=', 'users.id')
                        ->select('form_project.*', 'users.name as user_name');
        return DataTables::eloquent($data)->make(true);
    }

    public function approve_it_mgr(Request $request)
    {
        $id=$request->id;
        $type=$request->type;
        $project = Project::findOrFail($id);
        if($type=='ok'){
            $project->is_it_mgr_approve=1;
            $project->final_status='IT MGR Approve';
            $project->it_mgr_note=$request->it_mgr_note;
        }else{
            $project->is_it_mgr_approve=0;
            $project->final_status='IT MGR Reject';
            $project->it_mgr_note=$request->it_mgr_note;
            $project->is_finish=0;
        }
        $project->it_mgr_approval_date= Carbon::now();
        $project->save();
        return "Request is Saved!";
    }

    public function show_data_it_mgr_approval()
    {
        return view('website.pages.project.show_data_it_mgr_approval');
    }

    public function show_data_it_mgr_approval_ajax(Request $request)
    {
        // return Auth::user()->dept_id;
        $data = Project::where('is_it_mgr_approve','1')
                        ->join('users', 'form_project.created_by', '=', 'users.id')
                        ->select('form_project.*', 'users.name as user_name');;
        // return $data;
        return DataTables::eloquent($data)->make(true);
    }

    /// EXECUTION ///
    public function show_execution()
    {
        return view('website.pages.project.approval_execution');
    }

    public function show_execution_ajax(Request $request)
    {
        $data = Project::whereIn('final_status', ['IT MGR Approve', 'Delay'])
                        ->join('users', 'form_project.created_by', '=', 'users.id')
                        ->select('form_project.*', 'users.name as user_name');
                        
        return DataTables::eloquent($data)->make(true);
    }

    public function approve_execution(Request $request)
    {
        $id=$request->id;
        $type=$request->type;
        $project = Project::findOrFail($id);
        
        $user = $project->createdBy;
        
        if($type=='ok'){
            $isi = "FORM Request Project\n";
            $isi .= "\n\nREQUESTOR";
            $isi .= "\n*Nama* : " . $project->fullname;        
            
            $isi .= "\n*Department* : " . $project->department;
            $isi .= "\n\n*Nama Project* : " . $project->nama_project;
            $isi .= "\n*Kondisi Sebelum Improvement* : " . $project->kondisi_sebelum;
            $isi .= "\n*Kondisi yang diharapkan* : " . $project->kondisi_target;
            $isi .= "\n*Benefit yang didapat* : " . $project->benefit;
            $isi .= "\n*Additional Support Device* : " . $project->alat;
    
            $isi .= "\n\nStatus : Finished";
    
            $isi .= "\n\nManager Note : " . $project->manager_note;
            $isi .= "\nITD Note : " . $project->it_note;
            $isi .= "\nITD Manager Note : " . $project->it_mgr_note;
            $isi .= "\n\nNote : " . $request->finish_note;
    
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
                CURLOPT_POSTFIELDS => 'token='.$token.'&number='.$nomor.'&message='.$message,
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                
            $project->is_finish=1;
            $project->is_confirm=0;
            $project->final_status='Finished';
            $project->finish_note=$request->finish_note;
            $project->finish_by=Auth::user()->id;
        } else if($type=='delay'){
            $project->is_delay=1;
            $project->final_status='Delay';
            $project->delay_note=$request->delay_note;            
        } else {
            $project->is_finish=0;
            $project->final_status='Rejected';
            $project->finish_note=$request->finish_note;
            $project->finish_by=Auth::user()->id;
        }
        $project->finish_date= Carbon::now();
        $project->save();

        return "Request is Saved!";
    }

    public function show_data_execution()
    {
        return view('website.pages.project.show_data_execution');
    }

    public function show_data_execution_ajax(Request $request)
    {
        // return Auth::user()->dept_id;
        $data = Project::where('is_finish','1')->orWhere('is_finish','0')
                        ->join('users', 'form_project.created_by', '=', 'users.id')
                        ->select('form_project.*', 'users.name as user_name');
        // return $data;
        return DataTables::eloquent($data)->make(true);
    }
}
