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

use App\Traits\HasAjaxList;

class FiturController extends Controller
{
    use HasAjaxList;

    public function create()
    {
        $auth = User::where('id', Auth::user()->id)
                                    ->whereNull('nohp')
                                    ->count(); 

        $data = Fitur::where('created_by', Auth::user()->id)
                            ->where(function($query) {
                                    $query->where('final_status', 'LIKE', '%Reject%')
                                        ->orWhere('final_status', 'Finished');
                            })
                            ->where('is_confirm', 0)
                            ->count();
        
        if ($auth > 0) {
            return redirect()->route('website.user.edit');
        } else if($data > 0){
            return redirect()->route('website.fitur.list')->with('info', 'Please confirm!');
        }else{
            return view('website.pages.fitur.create');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_reg' => 'unique',
            'npk_pic' => 'required' ,          
            'fullname_pic' => 'required' ,
            'department_pic' => 'required' ,
            'phone_pic' => 'required' ,
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

        try
        {
            if ($request->hasFile('lampiran')) {
                $photoExtension = $request->lampiran->getClientOriginalExtension();
                $photoFileName = 'FTR_' . $year . $month . '_' . str_pad($newNumber, 3, '0', STR_PAD_LEFT) . '.' . $photoExtension;
                $photoPath = $request->lampiran->storeAs('lampiran', $photoFileName, 'public');
            }  
            
            $form_fitur = Fitur::create([
                'no_reg' => $no_reg,
                'npk' => $request->npk_pic ,
                'fullname' => $request->fullname_pic ,
                'department' => $request->department_pic ,
                'phone' => $request->phone_pic ,
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

            return redirect()->route('website.fitur.list')->with('success', 'Create Successfully');
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function list()
    {
        return view('website.pages.fitur.list');
    }

    public function list_ajax(Request $request)
    {
        return $this->generateAjaxList(\App\Models\Fitur::class, 'form_fitur');
    }

    public function approve_form(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $fitur = Fitur::findOrFail($id);

        if ($type == 'confirm') {
            $fitur->is_confirm = 1;
        } else {
            $fitur->is_confirm = 0;
        }
        $fitur->save();

        return "Confirm Successfully";
    }

    // MGR //
    public function manager_approval()
    {
        return view('website.pages.fitur.manager_approval');
    }

    public function manager_approval_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = Fitur::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
            ->where('final_status', 'created')
            ->join('public.users', 'form_fitur.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_fitur.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_fitur.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_fitur.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_fitur.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_fitur.finish_by', 'finish.id')
                        ->select('form_fitur.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
            ->orderBy('created_at', 'ASC');

        return DataTables::eloquent($data)->make(true);
    }

    public function manager_approve(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $fitur = Fitur::findOrFail($id);

        if ($type == 'approve') {
            $fitur->is_manager_approve = 1;
            $fitur->final_status = 'Manager Approve';
            $fitur->manager_note = $request->manager_note;
            $fitur->manager_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $fitur->is_manager_approve = 0;
            $fitur->final_status = 'Manager Reject';
            $fitur->manager_note = $request->manager_note;
            $fitur->manager_approve_by = Auth::user()->id;
            $fitur->is_finish = 0;
            $fitur->is_confirm = 0;
            $return = "Reject Successfully";
        }
        $fitur->manager_approval_date = Carbon::now();
        $fitur->save();
        return $return;
    }

    public function manager_approved()
    {
        return view('website.pages.fitur.manager_approved');
    }

    public function manager_approved_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = Fitur::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
            ->whereNotNull('is_manager_approve')
            ->join('public.users', 'form_fitur.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_fitur.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_fitur.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_fitur.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_fitur.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_fitur.finish_by', 'finish.id')
                        ->select('form_fitur.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
            ->orderBy('manager_approval_date', 'DESC');

        return DataTables::eloquent($data)->make(true);
    }

    /// ITD APPROVE ///

    public function it_approval()
    {
        return view('website.pages.fitur.it_approval');
    }

    public function it_approval_ajax(Request $request)
    {
        $data = Fitur::where('final_status', 'Manager Approve')
                        ->join('public.users', 'form_fitur.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_fitur.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_fitur.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_fitur.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_fitur.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_fitur.finish_by', 'finish.id')
                        ->select('form_fitur.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'ASC');

        return DataTables::eloquent($data)->make(true);
    }

    public function it_approve(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $fitur = Fitur::findOrFail($id);
        
        if ($type == 'approve') {
            $fitur->is_it_approve = 1;
            $fitur->final_status = 'IT Approve';
            $fitur->it_note = $request->it_note;
            $fitur->it_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $fitur->is_it_approve = 0;
            $fitur->is_confirm = 0;
            $fitur->final_status = 'IT Reject';
            $fitur->it_note = $request->it_note;
            $fitur->is_finish = 0;
            $fitur->it_approve_by = Auth::user()->id;
            $return = "Reject Successfully";
        }
        $fitur->it_approval_date = Carbon::now();
        $fitur->save();
        
        if ($request->notifikasi == 'Ya') {
            $isi = "FORM FITUR\n";
            $isi .= "*TUNGGU APPROVE IT MANAGER*";
            $isi .= "\n\nNama Aplikasi : *" . $fitur->aplikasi . "*";
            $isi .= "\nNama Fitur : *" . $fitur->nama_fitur . "*";
            $isi .= "\n\nREQUESTOR";
            $isi .= "\nNama : *" . $fitur->createdBy->name . "*";
            $isi .= "\nDepartment : *" . $fitur->createdBy->departments->pluck('code')->implode(', ') . "*";
            $isi .= "\n\nNote : Dear Pak Ferry, Mohon untuk dicek tunggu approve pada FIOLA. Terimakasih";

            $isi .= "\n\nApproved ITD by : " . Auth::user()->name;

            $nomors = Alert::where('role', 'IT Manager')->get();

            foreach ($nomors as $nomor) {
                $token = config('services.wa.token');
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
        return view('website.pages.fitur.it_approved');
    }

    public function it_approved_ajax(Request $request)
    {
        $data = Fitur::whereNotNull('is_it_approve')
                        ->join('public.users', 'form_fitur.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_fitur.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_fitur.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_fitur.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_fitur.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_fitur.finish_by', 'finish.id')
                        ->select('form_fitur.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('manager_approval_date', 'DESC');

        return DataTables::eloquent($data)->make(true);
    }

    /// IT MGR ///
    public function it_mgr_approval()
    {
        return view('website.pages.fitur.it_mgr_approval');
    }

    public function it_mgr_approval_ajax(Request $request)
    {
        $data = Fitur::where('final_status', 'IT Approve')
                        ->join('public.users', 'form_fitur.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_fitur.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_fitur.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_fitur.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_fitur.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_fitur.finish_by', 'finish.id')
                        ->select('form_fitur.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'ASC');

        return DataTables::eloquent($data)->make(true);
    }

    public function it_mgr_approve(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $fitur = Fitur::findOrFail($id);

        if ($type == 'approve') {
            $fitur->is_it_mgr_approve = 1;
            $fitur->final_status = 'IT MGR Approve';
            $fitur->it_mgr_note = $request->it_mgr_note;
            $fitur->it_mgr_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $fitur->is_it_mgr_approve = 0;
            $fitur->final_status = 'IT MGR Reject';
            $fitur->it_mgr_note = $request->it_mgr_note;
            $fitur->it_mgr_approve_by = Auth::user()->id;
            $fitur->is_finish = 0;
            $fitur->is_confirm = 0;
            $return = "Reject Successfully";
        }
        $fitur->it_mgr_approval_date = Carbon::now();
        $fitur->save();
        return $return;
    }

    public function it_mgr_approved()
    {
        return view('website.pages.fitur.it_mgr_approved');
    }

    public function it_mgr_approved_ajax(Request $request)
    {
        $data = Fitur::whereNotNull('is_it_mgr_approve')
                        ->join('public.users', 'form_fitur.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_fitur.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_fitur.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_fitur.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_fitur.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_fitur.finish_by', 'finish.id')
                        ->select('form_fitur.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'DESC');

        return DataTables::eloquent($data)->make(true);
    }

    /// EXECUTION ///
    public function execution()
    {
        return view('website.pages.fitur.execution');
    }

    public function execution_ajax(Request $request)
    {
        $data = Fitur::whereIn('final_status', ['IT MGR Approve', 'On Progress'])
                        ->join('public.users', 'form_fitur.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_fitur.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_fitur.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_fitur.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_fitur.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_fitur.finish_by', 'finish.id')
                        ->select('form_fitur.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'ASC');

        return DataTables::eloquent($data)->make(true);
    }

    public function execution_approve(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $fitur = Fitur::findOrFail($id);

        $user = $fitur->createdBy;

        if ($type == 'approve') {
            $fitur->is_finish = 1;
            $fitur->is_confirm = 0;
            $fitur->finish_by = Auth::user()->id;
            $fitur->final_status = 'Finished';
            $fitur->finish_note = $request->finish_note;
            $fitur->finish_date = Carbon::now();
            $return = "Approve Successfully";
        } else if ($type == 'progress') {
            $fitur->is_on_progress = 1;
            $fitur->final_status = 'On Progress';
            $fitur->on_progress_note = $request->on_progress_note;
            $fitur->on_progress_by = Auth::user()->id;
            $fitur->on_progress_date = Carbon::now();
            $return = "Progress Successfully";
        } else {
            $fitur->is_finish = 0;
            $fitur->is_confirm = 0;
            $fitur->final_status = 'Rejected';
            $fitur->finish_note = $request->finish_note;
            $fitur->finish_by = Auth::user()->id;
            $fitur->finish_date = Carbon::now();
            $return = "Reject Successfully";
        }
        $fitur->save();

        if ($request->notifikasi == 'Ya') {
            $isi = "FORM FITUR\n\n";

            $isi .= "Nama Aplikasi : *" . $fitur->aplikasi . "*";
            $isi .= "\nFitur Name : *" . $fitur->nama_fitur . "*";
            
            $isi .= "\n\nStatus : *Finished*";
            
            $isi .= "\n\nManager Note : " . $fitur->manager_note;
            $isi .= "\nITD Note : " . $fitur->it_note;
            $isi .= "\nITD Manager Note : " . $fitur->it_mgr_note;
            $isi .= "\n\nFinish Note : " . $request->finish_note;
            
            $isi .= "\n\nExecution by : " . Auth::user()->name;
            
            $nomor = $user->nohp;
            
            $token = config('services.wa.token');
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
        return view('website.pages.fitur.finished');
    }

    public function finished_ajax(Request $request)
    {
        $data = Fitur::whereNotNull('is_finish')
                        ->join('public.users', 'form_fitur.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_fitur.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_fitur.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_fitur.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_fitur.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_fitur.finish_by', 'finish.id')
                        ->select('form_fitur.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'DESC');

        return DataTables::eloquent($data)->make(true);
    }
}
