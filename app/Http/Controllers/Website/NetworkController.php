<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Network;
use App\Models\User;
use App\Models\Alert;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;
use Auth;

use App\Traits\HasAjaxList;

class NetworkController extends Controller
{
    use HasAjaxList;

    public function create()
    {
        $auth = User::where('id', Auth::user()->id)
            ->whereNull('nohp')
            ->count();

        $data = Network::where('created_by', Auth::user()->id)
                        ->where(function($query) {
                                $query->where('final_status', 'LIKE', '%Reject%')
                                    ->orWhere('final_status', 'Finished');
                        })
                        ->where('is_confirm', 0)
                        ->count();

        if ($auth > 0) {
            return redirect()->route('website.user.edit');
        } else if ($data > 0) {
            return redirect()->route('website.network.list')->with('info', 'Please confirm!');
        } else {
            return view('website.pages.network.create');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_reg' => 'unique',
            'project_name' => 'required',
            'date_access_start' => 'required',
            'date_access_end' => 'required',
            'rack' => 'required',
            'device' => 'required',
            'down_time' => 'required',
            'lampiran' => 'required',
        ]);

        $year = date('y');
        $month = date('m');
        $lastForm = DB::table('form_network')
            ->select('no_reg')
            ->orderBy('no_reg', 'desc')
            ->first();
        $lastNumber = ($lastForm) ? substr($lastForm->no_reg, -3) : '000';

        $lastMonth = ($lastForm) ? substr($lastForm->no_reg, 6, 2) : '00';
        if ($lastMonth !== $month) {
            $lastNumber = '000';
        }
        $newNumber = str_pad((intval($lastNumber) + 1), strlen($lastNumber), '0', STR_PAD_LEFT);
        $no_reg = 'NCC/' . $year . $month . '/' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

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
            if ($request->hasFile('lampiran')) {
                $lampiranExtension = $request->lampiran->getClientOriginalExtension();
                $lampiranFileName = 'NCC_' . $year . $month . '_' . str_pad($newNumber, 3, '0', STR_PAD_LEFT) . '.' . $lampiranExtension;
                $lampiranPath = $request->lampiran->storeAs('lampiran', $lampiranFileName, 'public');
            }

            $form_network = Network::create([
                'no_reg' => $no_reg,
                'project_name' => $request->project_name,
                'date_access_start' => $request->date_access_start,
                'date_access_end' => $request->date_access_end,
                'rack' => $request->rack,
                'device' => $request->device === 'Other' ? $request->other_device : $request->device,
                'down_time' => $request->down_time === 'Yes' ? 'Yes, down time = ' . $request->other_down_time . ' Minutes' : $request->down_time,
                'detail' => $request->detail,
                'purpose' => $request->purpose,
                'lampiran' => $lampiranFileName,
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
            $form_network->save();

            return redirect()->route('website.network.list')->with('success', 'Create Successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function edit($id)
    {
        $network = Network::findOrFail($id);

        return view('website.pages.network.edit', compact('network'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'budget_type' => 'required',
            'form_type' => 'required',
            'npk' => 'required|min:6',
            'fullname' => 'required',
            'department' => 'required',
            'phone' => 'required',
            'purpose' => 'required',
            'ad_name' => 'required',
        ]);

        $form_network = Network::findOrFail($id);

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
            $form_network->update([
                'budget_type' => $request->budget_type,
                'form_type' => $request->form_type,
                'npk' => $request->npk,
                'fullname' => $request->fullname,
                'department' => $request->department,
                'phone' => $request->phone,
                'company' => $request->company,
                'expired_date' => $request->expired_date,
                'purpose' => $request->purpose,
                'ad_name' => $request->ad_name,
                'created_by' => Auth::user()->id,
                'created_dept' => Auth::user()->departments->pluck('id')->first(),
                'final_status' => $finalStatus,
                'is_manager_approve' => $isManagerApprove,
                'manager_approval_date' => $managerApprovalDate,
            ]);

            return redirect()->route('website.network.list')->with('success', 'Success Edit Form');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function list()
    {
        return view('website.pages.network.list');
    }

    public function list_ajax(Request $request)
    {
        return $this->generateAjaxList(\App\Models\Network::class, 'form_network');
    }

    public function approve_form(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $network = Network::findOrFail($id);

        if ($type == 'confirm') {
            $network->is_confirm = 1;
        } else {
            $network->is_confirm = 0;
        }
        $network->save();

        return "Confirm Successfully";
    }

    public function delete_form(Request $request)
    {
        $id = $request->id;

        $network = Network::findOrFail($id);
        $network->delete();

        return "Delete Successfully";
    }

    // MGR //

    public function manager_approval()
    {
        return view('website.pages.network.manager_approval');
    }

    public function manager_approval_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = Network::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
            ->where('final_status', 'created')
            ->join('public.users', 'form_network.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_network.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_network.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_network.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_network.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_network.finish_by', 'finish.id')
                        ->select('form_network.*', 'users.name as requestor',
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

        $network = Network::findOrFail($id);

        if ($type == 'approve') {
            $network->is_manager_approve = 1;
            $network->final_status = 'Manager Approve';
            $network->manager_note = $request->manager_note;
            $network->manager_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $network->is_manager_approve = 0;
            $network->final_status = 'Manager Reject';
            $network->manager_note = $request->manager_note;
            $network->manager_approve_by = Auth::user()->id;
            $network->is_finish = 0;
            $network->is_confirm = 0;
            $return = "Reject Successfully";
        }
        $network->manager_approval_date = Carbon::now();
        $network->save();
        return $return;
    }

    public function manager_approved()
    {
        return view('website.pages.network.manager_approved');
    }

    public function manager_approved_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = Network::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
            ->whereNotNull('is_manager_approve')
            ->join('public.users', 'form_network.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_network.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_network.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_network.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_network.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_network.finish_by', 'finish.id')
                        ->select('form_network.*', 'users.name as requestor',
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
        return view('website.pages.network.it_approval');
    }

    public function it_approval_ajax(Request $request)
    {
        $data = Network::where('final_status', 'Manager Approve')
                        ->join('public.users', 'form_network.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_network.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_network.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_network.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_network.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_network.finish_by', 'finish.id')
                        ->select('form_network.*', 'users.name as requestor',
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

        $network = Network::findOrFail($id);
        
        if ($type == 'approve') {
            $network->is_it_approve = 1;
            $network->final_status = 'IT Approve';
            $network->it_note = $request->it_note;
            $network->it_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $network->is_it_approve = 0;
            $network->is_confirm = 0;
            $network->final_status = 'IT Reject';
            $network->it_note = $request->it_note;
            $network->is_finish = 0;
            $network->it_approve_by = Auth::user()->id;
            $return = "Reject Successfully";
        }
        $network->it_approval_date = Carbon::now();
        $network->save();
        
        if ($request->notifikasi == 'Ya') {
            $isi = "FORM NETWORK\n";
            $isi .= "*TUNGGU APPROVE IT MANAGER*";
            $isi .= "\n\nType : " . $network->form_type;
            $isi .= "\n\nREQUESTOR";
            $isi .= "\nNama : *" . $network->createdBy->name . "*";
            $isi .= "\nDepartment : *" . $network->createdBy->departments->pluck('code')->implode(', ') . "*";
            $isi .= "\nPurpose : " . $network->purpose;
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
        return view('website.pages.network.it_approved');
    }

    public function it_approved_ajax(Request $request)
    {
        $data = Network::whereNotNull('is_it_approve')
                        ->join('public.users', 'form_network.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_network.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_network.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_network.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_network.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_network.finish_by', 'finish.id')
                        ->select('form_network.*', 'users.name as requestor',
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
        return view('website.pages.network.it_mgr_approval');
    }

    public function it_mgr_approval_ajax(Request $request)
    {
        $data = Network::where('final_status', 'IT Approve')
                        ->join('public.users', 'form_network.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_network.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_network.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_network.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_network.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_network.finish_by', 'finish.id')
                        ->select('form_network.*', 'users.name as requestor',
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

        $network = Network::findOrFail($id);

        if ($type == 'approve') {
            $network->is_it_mgr_approve = 1;
            $network->final_status = 'IT MGR Approve';
            $network->it_mgr_note = $request->it_mgr_note;
            $network->it_mgr_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $network->is_it_mgr_approve = 0;
            $network->final_status = 'IT MGR Reject';
            $network->it_mgr_note = $request->it_mgr_note;
            $network->it_mgr_approve_by = Auth::user()->id;
            $network->is_finish = 0;
            $network->is_confirm = 0;
            $return = "Reject Successfully";
        }
        $network->it_mgr_approval_date = Carbon::now();
        $network->save();
        return $return;
    }

    public function it_mgr_approved()
    {
        return view('website.pages.network.it_mgr_approved');
    }

    public function it_mgr_approved_ajax(Request $request)
    {
        $data = Network::whereNotNull('is_it_mgr_approve')
                        ->join('public.users', 'form_network.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_network.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_network.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_network.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_network.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_network.finish_by', 'finish.id')
                        ->select('form_network.*', 'users.name as requestor',
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
        return view('website.pages.network.execution');
    }

    public function execution_ajax(Request $request)
    {
        $data = Network::whereIn('final_status', ['IT MGR Approve', 'On Progress'])
                        ->join('public.users', 'form_network.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_network.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_network.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_network.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_network.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_network.finish_by', 'finish.id')
                        ->select('form_network.*', 'users.name as requestor',
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

        $network = Network::findOrFail($id);

        $user = $network->createdBy;

        if ($type == 'approve') {
            $network->is_finish = 1;
            $network->is_confirm = 0;
            $network->finish_by = Auth::user()->id;
            $network->final_status = 'Finished';
            $network->finish_note = $request->finish_note;
            $network->finish_date = Carbon::now();
            $return = "Approve Successfully";
        } else if ($type == 'progress') {
            $network->is_on_progress = 1;
            $network->final_status = 'On Progress';
            $network->on_progress_note = $request->on_progress_note;
            $network->on_progress_by = Auth::user()->id;
            $network->on_progress_date = Carbon::now();
            $return = "Progress Successfully";
        } else {
            $network->is_finish = 0;
            $network->is_confirm = 0;
            $network->final_status = 'Rejected';
            $network->finish_note = $request->finish_note;
            $network->finish_by = Auth::user()->id;
            $network->finish_date = Carbon::now();
            $return = "Reject Successfully";
        }
        $network->save();

        if ($request->notifikasi == 'Ya') {
            $isi = "FORM NETWORK\n\n";
            
            $isi .= "Project Name : *" . $network->project_name . "*";
            $isi .= "\nDate Access : " . $network->date_access_start . " - " . $network->date_access_end;
            $isi .= "\nRack that is accessed : " . $network->rack;
            $isi .= "\nDevice that is accessed : " . $network->device;
            $isi .= "\nNeed Down Time : " . $network->down_time;
            $isi .= "\nPurpose : " . $network->purpose;
            
            $isi .= "\n\nStatus : *Finished*";
            
            $isi .= "\n\nManager Note : " . $network->manager_note;
            $isi .= "\nITD Note : " . $network->it_note;
            $isi .= "\nITD Manager Note : " . $network->it_mgr_note;
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
        return view('website.pages.network.finished');
    }

    public function finished_ajax(Request $request)
    {
        $data = Network::whereNotNull('is_finish')
                        ->join('public.users', 'form_network.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_network.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_network.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_network.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_network.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_network.finish_by', 'finish.id')
                        ->select('form_network.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'DESC');

        return DataTables::eloquent($data)->make(true);
    }
}
