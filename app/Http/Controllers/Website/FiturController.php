<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Fitur;
use App\Models\User;
use App\Models\Alert;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;
use Auth;

class FiturController extends Controller
{
    public function create()
    {
        $auth = User::where('id', Auth::user()->id)
                                    ->whereNull('nohp')
                                    ->count();        

        $data = Fitur::where('created_by', Auth::user()->id)->where('final_status', 'Finished')->where('is_confirm', 0)->count();
        if ($auth > 0) {
            return redirect()->route('website.user.edit');
        } else if($data > 0){
            return redirect()->route('website.fitur.show_data_form')->with('info', 'Please confirm!');
        }else{
            return view('website.pages.fitur.create');
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
            'aplikasi' => 'required' ,
            'nama_fitur' => 'required' ,
        ]);

        $year = date('y');
        $month = date('m');
        $lastForm = DB::table('form_fitur')
                      ->select('no_reg')
                      ->orderBy('no_reg', 'desc')
                      ->first();
        $lastNumber = ($lastForm) ? substr($lastForm->no_reg, -3) : '000';
        
        $lastMonth = ($lastForm) ? substr($lastForm->no_reg, 6, 2) : '00';            
        if ($lastMonth !== $month){
            $lastNumber = '000';
        }            
        $newNumber = str_pad((intval($lastNumber) + 1), strlen($lastNumber), '0', STR_PAD_LEFT);            
        $no_reg = 'FTR/' . $year . $month . '/' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        $isManagerApprove = null;
        $managerApprovalDate = null;
        $isItApprove = null;
        $itApprovalDate = null;
        $isItManagerApprove = null;
        $itManagerApprovalDate = null;

        if (Auth::user()->can('can_approve_it_mgr')) {
            $finalStatus = 'IT MGR Approve';
            $isItManagerApprove = 1;
            $itManagerApprovalDate = Carbon::now();
        } elseif (Auth::user()->can('can_approve_mgr')) {
            $finalStatus = 'Manager Approve';
            $isManagerApprove = 1;
            $managerApprovalDate = Carbon::now();
        } elseif (Auth::user()->can('can_approve_executives')) {
            $finalStatus = 'Manager Approve';
            $isManagerApprove = 1;
            $managerApprovalDate = Carbon::now();
        } elseif (Auth::user()->can('can_approve_it')) {
            $finalStatus = 'IT Approve';
            $isItApprove = 1;
            $itApprovalDate = Carbon::now();
        } else {
            $finalStatus = 'created';
            $isManagerApprove = null;
            $managerApprovalDate = null;
        }

        try
        {
            if ($request->hasFile('lampiran')) {
                    $photoExtension = $request->lampiran->getClientOriginalExtension();
                    $photoFileName = 'FTR_' . $year . $month . '_' . str_pad($newNumber, 3, '0', STR_PAD_LEFT) . '.' . $photoExtension;
                    $photoPath = $request->lampiran->storeAs('lampiran', $photoFileName, 'public');
            }  
            
            $form_fitur = Fitur::create([
                'no_reg' => $no_reg,
                'npk' => $request->npk ,
                'fullname' => $request->fullname ,
                'department' => $request->department ,
                'phone' => $request->phone ,
                'aplikasi' => $request->aplikasi ,
                'nama_fitur' => $request->nama_fitur ,
                'lampiran' => $photoFileName,
                'kondisi_sebelum' => $request->kondisi_sebelum ,
                'kondisi_target' => $request->kondisi_target ,
                'benefit' => $request->benefit ,
                'created_by' => Auth::user()->id,
                'created_dept' => Auth::user()->departments->pluck('id')->first(),
                'final_status' => $finalStatus,
                'is_manager_approve' => $isManagerApprove,
                'is_it_approve' => $isItApprove,
                'is_it_mgr_approve' => $isItManagerApprove,
                'manager_approval_date' => $managerApprovalDate,
                'it_approval_date' => $itApprovalDate,
                'it_mgr_approval_date' => $itManagerApprovalDate,           
            ]);
            $form_fitur->save();

            return redirect()->route('website.fitur.show_data_form')->with('success', 'Success Create Form');
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function show_data_form()
    {
        return view('website.pages.fitur.show_data_form');
    }

    public function show_data_form_ajax(Request $request)
    {
        $data = Fitur::orderBy('id', 'DESC')
                        ->where('created_by', Auth::user()->id)
                        ->join('users', 'form_fitur.created_by', '=', 'users.id')
                        ->select('form_fitur.*', 'users.name as user_name');

        return DataTables::eloquent($data)->make(true);
    }

    public function approve_form(Request $request)
    {
        $id=$request->id;
        $type=$request->type;
        $fitur = Fitur::findOrFail($id);
        if($type=='ok'){
            $fitur->is_confirm=1;
        }else{
            $fitur->is_confirm=0;
        }
        $fitur->save();
        return "Confirm is Saved!";
    }

    // MGR //
    public function show_manager_approval()
    {
        return view('website.pages.fitur.approval_manager');
    }

    public function show_manager_approval_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();
        
        $data = Fitur::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                            $query->where('created_dept', $firstDepartmentId)
                                ->orWhere('created_dept', $lastDepartmentId);
                        })
                        ->where('final_status', 'created')
                        ->join('users', 'form_fitur.created_by', '=', 'users.id')
                        ->select('form_fitur.*', 'users.name as user_name');

        return DataTables::eloquent($data)->make(true);
    }

    public function show_data_manager_approval()
    {
        return view('website.pages.fitur.show_data_manager_approval');
    }

    public function show_data_manager_approval_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();
        
        $data = Fitur::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                            $query->where('created_dept', $firstDepartmentId)
                                ->orWhere('created_dept', $lastDepartmentId);
                        })
                        ->where('is_manager_approve','1')
                        ->join('users', 'form_fitur.created_by', '=', 'users.id')
                        ->select('form_fitur.*', 'users.name as user_name');

        return DataTables::eloquent($data)->make(true);
    }

    public function approve_manager(Request $request)
    {
        $id=$request->id;
        
        $type=$request->type;
        
        $fitur = Fitur::findOrFail($id);
        
        if($type=='ok'){
            $fitur->is_manager_approve=1;
            $fitur->final_status='Manager Approve';
            $fitur->manager_note=$request->manager_note;
        }else{
            $fitur->is_manager_approve=0;
            $fitur->final_status='Manager Reject';
            $fitur->manager_note=$request->manager_note;
            $fitur->is_finish=0;
        }
        $fitur->manager_approval_date= Carbon::now();
        $fitur->save();
        return "Request is Saved!";        
    }

    /// ITD APPROVE ///
    public function show_it_approval()
    {
        return view('website.pages.fitur.approval_it');
    }

    public function show_it_approval_ajax(Request $request)
    {
        $data = Fitur::where('final_status','Manager Approve')
                        ->join('users', 'form_fitur.created_by', '=', 'users.id')
                        ->select('form_fitur.*', 'users.name as user_name');

        return DataTables::eloquent($data)->make(true);
    }

    public function show_data_it_approval()
    {
        return view('website.pages.fitur.show_data_it_approval');
    }

    public function show_data_it_approval_ajax(Request $request)
    {
        $data = Fitur::where('is_it_approve','1')
                        ->join('users', 'form_fitur.created_by', '=', 'users.id')
                        ->select('form_fitur.*', 'users.name as user_name');
        
        return DataTables::eloquent($data)->make(true);
    }

    public function approve_it(Request $request)
    {
        $id=$request->id;
        $type=$request->type;
        $fitur = Fitur::findOrFail($id);
        if($type=='ok'){
            $isi = "FORM Request Fitur\n";
            $isi .= "*TUNGGU APPROVE IT MANAGER*";
            $isi .= "\n\nREQUESTOR";
            $isi .= "\nNama : *" . $fitur->fullname ."*";        
            
            $isi .= "\nDepartment : " . $fitur->department;
            $isi .= "\n\nNama Aplikasi : " . $fitur->aplikasi;
            $isi .= "\nNama Fitur : " . $fitur->nama_fitur;
            $isi .= "\nKondisi Sebelum Improvement : " . $fitur->kondisi_sebelum;
            $isi .= "\nKondisi yang diharapkan : " . $fitur->kondisi_target;
            $isi .= "\nBenefit yang didapat : " . $fitur->benefit;
            $isi .= "\nNote : Dear Pak Ferry, Mohon untuk dicek tunggu approve pada FIOLA. Terimakasih";

            $isi .= "\n\nApproved ITD by : " . Auth::user()->name;
            
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

            $fitur->is_it_approve=1;
            $fitur->final_status='IT Approve';
            $fitur->it_note=$request->it_note;
        }else{
            $fitur->is_it_approve=0;
            $fitur->final_status='IT Reject';
            $fitur->it_note=$request->it_note;
            $fitur->is_finish=0;
        }
        $fitur->it_approval_date= Carbon::now();
        $fitur->save();
        return "Request is Saved!";
    }

    /// IT MGR ///
    public function show_it_mgr_approval()
    {
        return view('website.pages.fitur.approval_it_mgr');
    }

    public function show_it_mgr_approval_ajax(Request $request)
    {
        $data = Fitur::where('final_status','IT Approve')
                        ->join('users', 'form_fitur.created_by', '=', 'users.id')
                        ->select('form_fitur.*', 'users.name as user_name');
        return DataTables::eloquent($data)->make(true);
    }

    public function approve_it_mgr(Request $request)
    {
        $id=$request->id;
        $type=$request->type;
        $fitur = Fitur::findOrFail($id);
        if($type=='ok'){
            $fitur->is_it_mgr_approve=1;
            $fitur->final_status='IT MGR Approve';
            $fitur->it_mgr_note=$request->it_mgr_note;
        }else{
            $fitur->is_it_mgr_approve=0;
            $fitur->final_status='IT MGR Reject';
            $fitur->it_mgr_note=$request->it_mgr_note;
            $fitur->is_finish=0;
        }
        $fitur->it_mgr_approval_date= Carbon::now();
        $fitur->save();
        return "Request is Saved!";
    }

    public function show_data_it_mgr_approval()
    {
        return view('website.pages.fitur.show_data_it_mgr_approval');
    }

    public function show_data_it_mgr_approval_ajax(Request $request)
    {
        // return Auth::user()->dept_id;
        $data = Fitur::where('is_it_mgr_approve','1')
                        ->join('users', 'form_fitur.created_by', '=', 'users.id')
                        ->select('form_fitur.*', 'users.name as user_name');;
        // return $data;
        return DataTables::eloquent($data)->make(true);
    }

    /// EXECUTION ///
    public function show_execution()
    {
        return view('website.pages.fitur.approval_execution');
    }

    public function show_execution_ajax(Request $request)
    {
        $data = Fitur::whereIn('final_status', ['IT MGR Approve', 'Delay'])
                        ->join('users', 'form_fitur.created_by', '=', 'users.id')
                        ->select('form_fitur.*', 'users.name as user_name');
                        
        return DataTables::eloquent($data)->make(true);
    }

    public function approve_execution(Request $request)
    {
        $id=$request->id;
        $type=$request->type;
        $fitur = Fitur::findOrFail($id);
        
        $user = $fitur->createdBy;

        if($type=='ok'){
            $isi = "FORM Request Fitur\n";
        
            $isi .= "\nNPK : *" . $fitur->npk ."*";
            $isi .= "\nName : *" . $fitur->fullname ."*";
            $isi .= "\nDepartment : " . $fitur->department;
            $isi .= "\nPhone : " . $fitur->phone;            
    
            $isi .= "\n\nStatus : Finished";
    
            $isi .= "\n\nManager Note : " . $fitur->manager_note;
            $isi .= "\nITD Note : " . $fitur->it_note;
            $isi .= "\nITD Manager Note : " . $fitur->it_mgr_note;
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
                
            $fitur->is_finish=1;
            $fitur->is_confirm=0;
            $fitur->final_status='Finished';
            $fitur->finish_note=$request->finish_note;
        } else if($type=='delay'){
            $fitur->is_delay=1;
            $fitur->final_status='Delay';
            $fitur->delay_note=$request->delay_note;            
        } else {
            $fitur->is_finish=0;
            $fitur->final_status='Rejected';
            $fitur->finish_note=$request->finish_note;
        }
        $fitur->finish_date= Carbon::now();
        $fitur->save();

        return "Request is Saved!";
    }

    public function show_data_execution()
    {
        return view('website.pages.fitur.show_data_execution');
    }

    public function show_data_execution_ajax(Request $request)
    {
        // return Auth::user()->dept_id;
        $data = Fitur::where('is_finish','1')->orWhere('is_finish','0')
                        ->join('users', 'form_fitur.created_by', '=', 'users.id')
                        ->select('form_fitur.*', 'users.name as user_name');
        // return $data;
        return DataTables::eloquent($data)->make(true);
    }
}
