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

use App\Traits\HasAjaxList;

class ProjectController extends Controller
{
    use HasAjaxList;

    public function create()
    {
        $devices = Device::all();

        $auth = User::where('id', Auth::user()->id)
                                    ->whereNull('nohp')
                                    ->count();

        $data = Project::where('created_by', Auth::user()->id)
                            ->where(function($query) {
                                    $query->where('final_status', 'LIKE', '%Reject%')
                                        ->orWhere('final_status', 'Finished');
                            })
                            ->where('is_confirm', 0)
                            ->count();

        if ($auth > 0) {
            return redirect()->route('website.user.edit');
        } else if($data > 0){
            return redirect()->route('website.project.list')->with('info', 'Please confirm!');
        }else{
            return view('website.pages.project.create', compact(['devices']));
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
                $photoFileName = 'PRJ_' . $year . $month . '_' . str_pad($newNumber, 3, '0', STR_PAD_LEFT) . '.' . $photoExtension;
                $photoPath = $request->lampiran->storeAs('lampiran', $photoFileName, 'public');
            }

            $alatSelected = $request->input('device');
            $qtySelected = $request->input('qty');

            if ($alatSelected == [null]) {
                $combinedDescriptionString = null;
            } else {
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
            }

            $form_project = Project::create([
                'no_reg' => $no_reg,
                'npk' => $request->npk_pic ,
                'fullname' => $request->fullname_pic ,
                'department' => $request->department_pic ,
                'phone' => $request->phone_pic ,
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
                'is_it_approve' => $isItApprove,
                'is_it_mgr_approve' => $isItManagerApprove,
                'manager_approval_date' => $managerApprovalDate,
                'it_approval_date' => $itApprovalDate,
                'it_mgr_approval_date' => $itManagerApprovalDate,
            ]);
            $form_project->save();

            return redirect()->route('website.project.list')->with('success', 'Create Successfully');
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function list()
    {
        return view('website.pages.project.list');
    }

    public function list_ajax(Request $request)
    {
        return $this->generateAjaxList(\App\Models\Project::class, 'form_project');
    }

    public function approve_form(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $project = Project::findOrFail($id);

        if ($type == 'confirm') {
            $project->is_confirm = 1;
        } else {
            $project->is_confirm = 0;
        }
        $project->save();

        return "Confirm Successfully";
    }

    // MGR //
    public function manager_approval()
    {
        return view('website.pages.project.manager_approval');
    }

    public function manager_approval_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = Project::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
            ->where('final_status', 'created')
            ->join('public.users', 'form_project.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_project.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_project.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_project.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_project.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_project.finish_by', 'finish.id')
                        ->select('form_project.*', 'users.name as requestor',
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

        $project = Project::findOrFail($id);

        if ($type == 'approve') {
            $project->is_manager_approve = 1;
            $project->final_status = 'Manager Approve';
            $project->manager_note = $request->manager_note;
            $project->manager_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $project->is_manager_approve = 0;
            $project->final_status = 'Manager Reject';
            $project->manager_note = $request->manager_note;
            $project->manager_approve_by = Auth::user()->id;
            $project->is_finish = 0;
            $project->is_confirm = 0;
            $return = "Reject Successfully";
        }
        $project->manager_approval_date = Carbon::now();
        $project->save();
        return $return;
    }

    public function manager_approved()
    {
        return view('website.pages.project.manager_approved');
    }

    public function manager_approved_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = Project::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
            ->whereNotNull('is_manager_approve')
            ->join('public.users', 'form_project.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_project.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_project.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_project.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_project.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_project.finish_by', 'finish.id')
                        ->select('form_project.*', 'users.name as requestor',
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
        return view('website.pages.project.it_approval');
    }

    public function it_approval_ajax(Request $request)
    {
        $data = Project::where('final_status', 'Manager Approve')
                        ->join('public.users', 'form_project.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_project.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_project.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_project.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_project.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_project.finish_by', 'finish.id')
                        ->select('form_project.*', 'users.name as requestor',
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

        $project = Project::findOrFail($id);

        if ($type == 'approve') {
            $project->is_it_approve = 1;
            $project->final_status = 'IT Approve';
            $project->it_note = $request->it_note;
            $project->it_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $project->is_it_approve = 0;
            $project->is_confirm = 0;
            $project->final_status = 'IT Reject';
            $project->it_note = $request->it_note;
            $project->is_finish = 0;
            $project->it_approve_by = Auth::user()->id;
            $return = "Reject Successfully";
        }
        $project->it_approval_date = Carbon::now();
        $project->save();

        if ($request->notifikasi == 'Ya') {
            $isi = "FORM PROJECT\n";
            $isi .= "*TUNGGU APPROVE IT MANAGER*";
            $isi .= "\n\nNama Project : *" . $project->nama_project . "*";
            $isi .= "\n\nREQUESTOR";
            $isi .= "\nNama : *" . $project->createdBy->name . "*";
            $isi .= "\nDepartment : *" . $project->createdBy->departments->pluck('code')->implode(', ') . "*";
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
        return view('website.pages.project.it_approved');
    }

    public function it_approved_ajax(Request $request)
    {
        $data = Project::whereNotNull('is_it_approve')
                        ->join('public.users', 'form_project.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_project.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_project.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_project.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_project.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_project.finish_by', 'finish.id')
                        ->select('form_project.*', 'users.name as requestor',
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
        return view('website.pages.project.it_mgr_approval');
    }

    public function it_mgr_approval_ajax(Request $request)
    {
        $data = Project::where('final_status', 'IT Approve')
                        ->join('public.users', 'form_project.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_project.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_project.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_project.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_project.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_project.finish_by', 'finish.id')
                        ->select('form_project.*', 'users.name as requestor',
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

        $project = Project::findOrFail($id);

        if ($type == 'approve') {
            $project->is_it_mgr_approve = 1;
            $project->final_status = 'IT MGR Approve';
            $project->it_mgr_note = $request->it_mgr_note;
            $project->it_mgr_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $project->is_it_mgr_approve = 0;
            $project->final_status = 'IT MGR Reject';
            $project->it_mgr_note = $request->it_mgr_note;
            $project->it_mgr_approve_by = Auth::user()->id;
            $project->is_finish = 0;
            $project->is_confirm = 0;
            $return = "Reject Successfully";
        }
        $project->it_mgr_approval_date = Carbon::now();
        $project->save();
        return $return;
    }

    public function it_mgr_approved()
    {
        return view('website.pages.project.it_mgr_approved');
    }

    public function it_mgr_approved_ajax(Request $request)
    {
        $data = Project::whereNotNull('is_it_mgr_approve')
                        ->join('public.users', 'form_project.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_project.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_project.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_project.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_project.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_project.finish_by', 'finish.id')
                        ->select('form_project.*', 'users.name as requestor',
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
        return view('website.pages.project.execution');
    }

    public function execution_ajax(Request $request)
    {
        $data = Project::whereIn('final_status', ['IT MGR Approve', 'On Progress'])
                        ->join('public.users', 'form_project.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_project.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_project.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_project.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_project.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_project.finish_by', 'finish.id')
                        ->select('form_project.*', 'users.name as requestor',
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

        $project = Project::findOrFail($id);

        $user = $project->createdBy;

        if ($type == 'approve') {
            $project->is_finish = 1;
            $project->is_confirm = 0;
            $project->finish_by = Auth::user()->id;
            $project->final_status = 'Finished';
            $project->finish_note = $request->finish_note;
            $project->finish_date = Carbon::now();
            $return = "Approve Successfully";
        } else if ($type == 'progress') {
            $project->is_on_progress = 1;
            $project->final_status = 'On Progress';
            $project->on_progress_note = $request->on_progress_note;
            $project->on_progress_by = Auth::user()->id;
            $project->on_progress_date = Carbon::now();
            $return = "Progress Successfully";
        } else {
            $project->is_finish = 0;
            $project->is_confirm = 0;
            $project->final_status = 'Rejected';
            $project->finish_note = $request->finish_note;
            $project->finish_by = Auth::user()->id;
            $project->finish_date = Carbon::now();
            $return = "Reject Successfully";
        }
        $project->save();

        if ($request->notifikasi == 'Ya') {
            $isi = "FORM PROJECT\n\n";

            $isi .= "Project Name : *" . $project->nama_project . "*";

            $isi .= "\n\nStatus : *Finished*";

            $isi .= "\n\nManager Note : " . $project->manager_note;
            $isi .= "\nITD Note : " . $project->it_note;
            $isi .= "\nITD Manager Note : " . $project->it_mgr_note;
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
        return view('website.pages.project.finished');
    }

    public function finished_ajax(Request $request)
    {
        $data = Project::whereNotNull('is_finish')
                        ->join('public.users', 'form_project.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_project.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_project.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_project.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_project.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_project.finish_by', 'finish.id')
                        ->select('form_project.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'DESC');

        return DataTables::eloquent($data)->make(true);
    }
}
