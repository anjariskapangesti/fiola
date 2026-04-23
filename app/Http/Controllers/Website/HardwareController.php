<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Hardware;
use App\Models\Department;
use App\Models\User;
use App\Models\Alert;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;
use Auth;

use App\Traits\HasAjaxList;

class HardwareController extends Controller
{
    use HasAjaxList;

    public function create()
    {
        $departments = Department::orderBy('name')->get();

        $auth = User::where('id', Auth::user()->id)
            ->whereNull('nohp')
            ->count();

        $data = Hardware::where('created_by', Auth::user()->id)
                        ->where(function($query) {
                                $query->where('final_status', 'LIKE', '%Reject%')
                                    ->orWhere('final_status', 'Finished');
                        })
                        ->where('is_confirm', 0)
                        ->count();

        if ($auth > 0) {
            return redirect()->route('website.user.edit');
        } else if ($data > 0) {
            return redirect()->route('website.hardware.list')->with('info', 'Please confirm!');
        } else {
            return view('website.pages.hardware.create', compact('departments'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_reg' => 'unique',
            'budget_type' => 'required',
            'type' => 'required',
            'category' => 'required',
            'npk' => 'nullable|min:6|required_if:category,Request|required_if:category,Transfer',
            'fullname' => 'required_if:category,Request|required_if:category,Transfer',
            'department' => 'required_if:category,Request|required_if:category,Transfer',
            'phone' => 'required_if:category,Request|required_if:category,Transfer',
            'purpose' => 'required_if:category,Request',
        ]);

        $year = date('y');
        $month = date('m');
        $lastForm = DB::table('form_hardware')
            ->select('no_reg')
            ->orderBy('no_reg', 'desc')
            ->first();
        $lastNumber = ($lastForm) ? substr($lastForm->no_reg, -3) : '000';

        $lastMonth = ($lastForm) ? substr($lastForm->no_reg, 6, 2) : '00';
        if ($lastMonth !== $month) {
            $lastNumber = '000';
        }
        $newNumber = str_pad((intval($lastNumber) + 1), strlen($lastNumber), '0', STR_PAD_LEFT);
        $no_reg = 'HWR/' . $year . $month . '/' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        if ($request->is_email == false) {
            $request->is_email = 0;
        } else {
            $request->is_email = 1;
        }

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
            $form_hardware = Hardware::create([
                'no_reg' => $no_reg,
                'budget_type' => $request->budget_type,
                'type' => $request->type === 'Other' ? $request->other_type : $request->type,
                'category' => $request->category,
                'npk' => $request->npk,
                'fullname' => $request->fullname,
                'department' => $request->department,
                'phone' => $request->phone,
                'device_before' => $request->device_before,
                'due_date' => Carbon::now()->addMonth()->format('Y-m-d'),
                'purpose' => $request->purpose,
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
            $form_hardware->save();

            return redirect()->route('website.hardware.list')->with('success', 'Create Successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function edit($id)
    {
        $hardware = Hardware::findOrFail($id);
        $departments = Department::orderBy('name')->get();

        return view('website.pages.hardware.edit', compact('hardware', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'budget_type' => 'required',
            'category' => 'required',
            'type' => 'required',
            'npk' => 'required',
            'fullname' => 'required',
            'department' => 'required',
            'phone' => 'required',
            'due_date' => 'required',
            'purpose' => 'required',
        ]);

        $form_hardware = Hardware::findOrFail($id);

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
            $form_hardware->update([
                'category' => $request->category,
                'type' => $request->type,
                'npk' => $request->npk,
                'fullname' => $request->fullname,
                'department' => $request->department,
                'phone' => $request->phone,
                'due_date' => $request->due_date,
                'device_before' => $request->device_before,
                'purpose' => $request->purpose,
                'created_by' => Auth::user()->id,
                'created_dept' => Auth::user()->departments->pluck('id')->first(),
                'final_status' => $finalStatus,
                'is_manager_approve' => $isManagerApprove,
                'manager_approval_date' => $managerApprovalDate,
            ]);

            $depts = Department::all();
            return redirect()->route('website.hardware.show_data_form')->with('success', 'Success Edit Form');
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function list()
    {
        return view('website.pages.hardware.list');
    }

    public function list_ajax(Request $request)
    {
        return $this->generateAjaxList(\App\Models\Hardware::class, 'form_hardware');
    }

    public function approve_form(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $hardware = Hardware::findOrFail($id);

        if ($type == 'confirm') {
            $hardware->is_confirm = 1;
        } else {
            $hardware->is_confirm = 0;
        }
        $hardware->save();

        return "Confirm Successfully";
    }

    public function delete_form(Request $request)
    {
        $id = $request->id;

        $hardware = Hardware::findOrFail($id);
        $hardware->delete();

        return "Delete Successfully";
    }

    // MGR //

    public function manager_approval()
    {
        return view('website.pages.hardware.manager_approval');
    }

    public function manager_approval_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = Hardware::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
            ->where('final_status', 'created')
            ->join('users', 'form_hardware.created_by', 'users.id')
                        ->leftJoin('users as manager', 'form_hardware.manager_approve_by', 'manager.id')
                        ->leftJoin('users as it', 'form_hardware.it_approve_by', 'it.id')
                        ->leftJoin('users as it_mgr', 'form_hardware.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('users as on_progress', 'form_hardware.on_progress_by', 'on_progress.id')
                        ->leftJoin('users as finish', 'form_hardware.finish_by', 'finish.id')
                        ->select('form_hardware.*', 'users.name as requestor',
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

        $hardware = Hardware::findOrFail($id);

        if ($type == 'approve') {
            $hardware->is_manager_approve = 1;
            $hardware->final_status = 'Manager Approve';
            $hardware->manager_note = $request->manager_note;
            $hardware->manager_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $hardware->is_manager_approve = 0;
            $hardware->final_status = 'Manager Reject';
            $hardware->manager_note = $request->manager_note;
            $hardware->manager_approve_by = Auth::user()->id;
            $hardware->is_finish = 0;
            $hardware->is_confirm = 0;
            $return = "Reject Successfully";
        }
        $hardware->manager_approval_date = Carbon::now();
        $hardware->save();
        return $return;
    }

    public function manager_approved()
    {
        return view('website.pages.hardware.manager_approved');
    }

    public function manager_approved_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = Hardware::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
            ->whereNotNull('is_manager_approve')
            ->join('users', 'form_hardware.created_by', 'users.id')
                        ->leftJoin('users as manager', 'form_hardware.manager_approve_by', 'manager.id')
                        ->leftJoin('users as it', 'form_hardware.it_approve_by', 'it.id')
                        ->leftJoin('users as it_mgr', 'form_hardware.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('users as on_progress', 'form_hardware.on_progress_by', 'on_progress.id')
                        ->leftJoin('users as finish', 'form_hardware.finish_by', 'finish.id')
                        ->select('form_hardware.*', 'users.name as requestor',
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
        return view('website.pages.hardware.it_approval');
    }

    public function it_approval_ajax(Request $request)
    {
        $data = Hardware::where('final_status', 'Manager Approve')
                        ->join('users', 'form_hardware.created_by', 'users.id')
                        ->leftJoin('users as manager', 'form_hardware.manager_approve_by', 'manager.id')
                        ->leftJoin('users as it', 'form_hardware.it_approve_by', 'it.id')
                        ->leftJoin('users as it_mgr', 'form_hardware.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('users as on_progress', 'form_hardware.on_progress_by', 'on_progress.id')
                        ->leftJoin('users as finish', 'form_hardware.finish_by', 'finish.id')
                        ->select('form_hardware.*', 'users.name as requestor',
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

        $hardware = Hardware::findOrFail($id);

        if ($type == 'approve') {
            $hardware->is_it_approve = 1;
            $hardware->final_status = 'IT Approve';
            $hardware->it_note = $request->it_note;
            $hardware->it_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $hardware->is_it_approve = 0;
            $hardware->is_confirm = 0;
            $hardware->final_status = 'IT Reject';
            $hardware->it_note = $request->it_note;
            $hardware->is_finish = 0;
            $hardware->it_approve_by = Auth::user()->id;
            $return = "Reject Successfully";
        }
        $hardware->it_approval_date = Carbon::now();
        $hardware->save();

        if ($request->notifikasi == 'Ya') {
            $isi = "FORM HARDWARE\n";
            $isi .= "*TUNGGU APPROVE IT MANAGER*";
            $isi .= "\n\nType : " . $hardware->type;
            $isi .= "\n\nREQUESTOR";
            $isi .= "\nNama : *" . $hardware->createdBy->name . "*";
            $isi .= "\nDepartment : *" . $hardware->createdBy->departments->pluck('code')->implode(', ') . "*";
            $isi .= "\nPurpose : " . $hardware->purpose;
            $isi .= "\n\nNote : Dear Pak Ferry, Mohon untuk dicek tunggu approve pada FIOLA. Terimakasih";

            $isi .= "\n\nApproved ITD by : " . Auth::user()->name;

            $nomors = Alert::where('role', 'IT Manager')->get();

            foreach ($nomors as $nomor) {
                $token = "793D30579A77D4A0E12648872BFBB085";
                $message = "----------FIOLA----------\n"
                    . $isi
                    . "\n-------------------------";
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://app.fastwa.com/api/v1/4D9AF7CE224B91C9CE14FFDDB55D248D/send_text',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => 'api_key='.$token.'&phone='.$nomor.'&message='.$message,
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                sleep(10);
                echo $response;
            }
        }
        return $return;
    }

    public function it_approved()
    {
        return view('website.pages.hardware.it_approved');
    }

    public function it_approved_ajax(Request $request)
    {
        $data = Hardware::whereNotNull('is_it_approve')
                        ->join('users', 'form_hardware.created_by', 'users.id')
                        ->leftJoin('users as manager', 'form_hardware.manager_approve_by', 'manager.id')
                        ->leftJoin('users as it', 'form_hardware.it_approve_by', 'it.id')
                        ->leftJoin('users as it_mgr', 'form_hardware.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('users as on_progress', 'form_hardware.on_progress_by', 'on_progress.id')
                        ->leftJoin('users as finish', 'form_hardware.finish_by', 'finish.id')
                        ->select('form_hardware.*', 'users.name as requestor',
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
        return view('website.pages.hardware.it_mgr_approval');
    }

    public function it_mgr_approval_ajax(Request $request)
    {
        $data = Hardware::where('final_status', 'IT Approve')
                        ->join('users', 'form_hardware.created_by', 'users.id')
                        ->leftJoin('users as manager', 'form_hardware.manager_approve_by', 'manager.id')
                        ->leftJoin('users as it', 'form_hardware.it_approve_by', 'it.id')
                        ->leftJoin('users as it_mgr', 'form_hardware.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('users as on_progress', 'form_hardware.on_progress_by', 'on_progress.id')
                        ->leftJoin('users as finish', 'form_hardware.finish_by', 'finish.id')
                        ->select('form_hardware.*', 'users.name as requestor',
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

        $hardware = Hardware::findOrFail($id);

        if ($type == 'approve') {
            $hardware->is_it_mgr_approve = 1;
            $hardware->final_status = 'IT MGR Approve';
            $hardware->it_mgr_note = $request->it_mgr_note;
            $hardware->it_mgr_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $hardware->is_it_mgr_approve = 0;
            $hardware->final_status = 'IT MGR Reject';
            $hardware->it_mgr_note = $request->it_mgr_note;
            $hardware->it_mgr_approve_by = Auth::user()->id;
            $hardware->is_finish = 0;
            $hardware->is_confirm = 0;
            $return = "Reject Successfully";
        }
        $hardware->it_mgr_approval_date = Carbon::now();
        $hardware->save();
        return $return;
    }

    public function it_mgr_approved()
    {
        return view('website.pages.hardware.it_mgr_approved');
    }

    public function it_mgr_approved_ajax(Request $request)
    {
        $data = Hardware::whereNotNull('is_it_mgr_approve')
                        ->join('users', 'form_hardware.created_by', 'users.id')
                        ->leftJoin('users as manager', 'form_hardware.manager_approve_by', 'manager.id')
                        ->leftJoin('users as it', 'form_hardware.it_approve_by', 'it.id')
                        ->leftJoin('users as it_mgr', 'form_hardware.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('users as on_progress', 'form_hardware.on_progress_by', 'on_progress.id')
                        ->leftJoin('users as finish', 'form_hardware.finish_by', 'finish.id')
                        ->select('form_hardware.*', 'users.name as requestor',
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
        return view('website.pages.hardware.execution');
    }

    public function execution_ajax(Request $request)
    {
        $data = Hardware::whereIn('final_status', ['IT MGR Approve', 'On Progress'])
                        ->join('users', 'form_hardware.created_by', 'users.id')
                        ->leftJoin('users as manager', 'form_hardware.manager_approve_by', 'manager.id')
                        ->leftJoin('users as it', 'form_hardware.it_approve_by', 'it.id')
                        ->leftJoin('users as it_mgr', 'form_hardware.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('users as on_progress', 'form_hardware.on_progress_by', 'on_progress.id')
                        ->leftJoin('users as finish', 'form_hardware.finish_by', 'finish.id')
                        ->select('form_hardware.*', 'users.name as requestor',
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

        $hardware = Hardware::findOrFail($id);

        $user = $hardware->createdBy;

        if ($type == 'approve') {
            $hardware->device_after = $request->device_after;
            $hardware->is_finish = 1;
            $hardware->is_confirm = 0;
            $hardware->finish_by = Auth::user()->id;
            $hardware->final_status = 'Finished';
            $hardware->finish_note = $request->finish_note;
            $hardware->finish_date = Carbon::now();
            $return = "Approve Successfully";
        } else if ($type == 'progress') {
            $hardware->is_on_progress = 1;
            $hardware->final_status = 'On Progress';
            $hardware->on_progress_note = $request->on_progress_note;
            $hardware->on_progress_by = Auth::user()->id;
            $hardware->on_progress_date = Carbon::now();
            $return = "Progress Successfully";
        } else {
            $hardware->is_finish = 0;
            $hardware->is_confirm = 0;
            $hardware->final_status = 'Rejected';
            $hardware->finish_note = $request->finish_note;
            $hardware->finish_by = Auth::user()->id;
            $hardware->finish_date = Carbon::now();
            $return = "Reject Successfully";
        }
        $hardware->save();

        if ($request->notifikasi == 'Ya') {
            $isi = "FORM HARDWARE\n\n";

            $isi .= "Budget Type : " . $hardware->budget_type;
            $isi .= "\nType : " . $hardware->type;
            $isi .= "\nCategory : " . $hardware->category;

            $isi .= "\n\nNPK : *" . $hardware->npk . "*";
            $isi .= "\nName : *" . $hardware->fullname . "*";
            $isi .= "\nDepartment : " . $hardware->department;
            $isi .= "\nPhone : " . $hardware->phone;
            $isi .= "\nID Device : " . $hardware->device_after;
            $isi .= "\nPurpose : " . $hardware->purpose;

            $isi .= "\n\nStatus : *Finished*";

            $isi .= "\n\nManager Note : " . $hardware->manager_note;
            $isi .= "\nITD Note : " . $hardware->it_note;
            $isi .= "\nITD Manager Note : " . $hardware->it_mgr_note;
            $isi .= "\n\nFinish Note : " . $request->finish_note;

            $isi .= "\n\nExecution by : " . Auth::user()->name;

            $nomor = $user->nohp;

            $token = "793D30579A77D4A0E12648872BFBB085";
            $message = "----------FIOLA----------\n"
                . $isi
                . "\n-------------------------";
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://app.fastwa.com/api/v1/4D9AF7CE224B91C9CE14FFDDB55D248D/send_text',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'api_key='.$token.'&phone='.$nomor.'&message='.$message,
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            sleep(10);
            echo $response;
        }

        return $return;
    }

    public function finished()
    {
        return view('website.pages.hardware.finished');
    }

    public function finished_ajax(Request $request)
    {
        $data = Hardware::whereNotNull('is_finish')
                        ->join('users', 'form_hardware.created_by', 'users.id')
                        ->leftJoin('users as manager', 'form_hardware.manager_approve_by', 'manager.id')
                        ->leftJoin('users as it', 'form_hardware.it_approve_by', 'it.id')
                        ->leftJoin('users as it_mgr', 'form_hardware.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('users as on_progress', 'form_hardware.on_progress_by', 'on_progress.id')
                        ->leftJoin('users as finish', 'form_hardware.finish_by', 'finish.id')
                        ->select('form_hardware.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'DESC');

        return DataTables::eloquent($data)->make(true);
    }
}
