<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Sistem;
use App\Models\SistemUser;
use App\Models\SistemApp;
use App\Models\User;
use App\Models\App;
use App\Models\Department;
use App\Models\Alert;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;
use Auth;

use App\Traits\HasAjaxList;

class AksesSistemController extends Controller
{
    use HasAjaxList;

    public function create()
    {
        $auth = User::where('id', Auth::user()->id)
                                    ->whereNull('nohp')
                                    ->count();

        $apps = App::orderBy('name', 'ASC')->get();
        $departments = Department::orderBy('name')->get();

        $data = Sistem::where('created_by', Auth::user()->id)
                            ->where(function($query) {
                                    $query->where('final_status', 'LIKE', '%Reject%')
                                        ->orWhere('final_status', 'Finished');
                            })
                            ->where('is_confirm', 0)
                            ->count();

        if ($auth > 0) {
            return redirect()->route('website.user.edit');
        } else if($data > 0){
            return redirect()->route('website.akses_sistem.list')->with('info', 'Please confirm!');
        }else{
            return view('website.pages.akses_sistem.create', compact('apps', 'departments'));
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
                'name' => 'required',
                'app_name' => 'required',
                'purpose' => 'required',
            ]);

            $year = date('y');
            $month = date('m');
            $lastForm = DB::table('form_sistem')
                        ->select('no_reg')
                        ->orderBy('no_reg', 'desc')
                        ->first();
            $lastNumber = ($lastForm) ? substr($lastForm->no_reg, -3) : '000';

            $lastMonth = ($lastForm) ? substr($lastForm->no_reg, 6, 2) : '00';
            if ($lastMonth !== $month){
                $lastNumber = '000';
            }
            $newNumber = str_pad((intval($lastNumber) + 1), strlen($lastNumber), '0', STR_PAD_LEFT);
            $no_reg = 'SAC/' . $year . $month . '/' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

            $user = Auth::user();

            $akses_sistem = new Sistem();
            $akses_sistem->no_reg = $no_reg;
            $akses_sistem->purpose = $request->purpose;
            $akses_sistem->created_by = $user->id;
            $akses_sistem->created_dept = $user->departments->pluck('id')->first();
            $akses_sistem->final_status = $finalStatus;
            $akses_sistem->is_manager_approve = $isManagerApprove;
            $akses_sistem->is_it_approve = $isItApprove;
            $akses_sistem->is_it_mgr_approve = $isItManagerApprove;
            $akses_sistem->manager_approval_date = $managerApprovalDate;
            $akses_sistem->it_approval_date = $itApprovalDate;
            $akses_sistem->it_mgr_approval_date = $itManagerApprovalDate;
            $akses_sistem->save();

            for ($i = 0; $i < count($request->npk ); $i++) {
                SistemUser::create([
                    'sistem_id' => $akses_sistem->id,
                    'npk' => $request->npk[$i],
                    'name' => $request->name[$i],
                    'email' => $request->email[$i],
                    'department' => $request->department[$i],
                ]);
            }

            for ($i = 0; $i < count($request->app_name ); $i++) {
                SistemApp::create([
                    'sistem_id' => $akses_sistem->id,
                    'app_name' => $request->app_name[$i],
                ]);
            }

            return redirect()->route('website.akses_sistem.list')->with('success', 'Create Successfully');
        } catch (Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function list()
    {
        return view('website.pages.akses_sistem.list');
    }

    public function list_ajax(Request $request)
    {
        return $this->generateAjaxList(
            \App\Models\Sistem::class,
            'form_sistem',
            ['form_sistem_app', 'form_sistem_user']
        );
    }

    public function approve_form(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $akses_sistem = Sistem::findOrFail($id);

        if ($type == 'confirm') {
            $akses_sistem->is_confirm = 1;
        } else {
            $akses_sistem->is_confirm = 0;
        }
        $akses_sistem->save();

        return "Confirm Successfully";
    }

    public function delete_form(Request $request)
    {
        $id = $request->id;

        $akses_sistem = Sistem::findOrFail($id);
        $akses_sistem->delete();

        return "Delete Successfully";
    }

    // MGR //
    public function manager_approval()
    {
        return view('website.pages.akses_sistem.manager_approval');
    }

    public function manager_approval_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = Sistem::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
            ->where('final_status', 'created')
            ->join('users', 'form_sistem.created_by', 'users.id')
                        ->leftJoin('users as manager', 'form_sistem.manager_approve_by', 'manager.id')
                        ->leftJoin('users as it', 'form_sistem.it_approve_by', 'it.id')
                        ->leftJoin('users as it_mgr', 'form_sistem.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('users as on_progress', 'form_sistem.on_progress_by', 'on_progress.id')
                        ->leftJoin('users as finish', 'form_sistem.finish_by', 'finish.id')
                        ->select('form_sistem.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
            ->orderBy('created_at', 'ASC')
            ->with('form_sistem_app')
            ->with('form_sistem_user');

        return DataTables::eloquent($data)->make(true);
    }

    public function manager_approve(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $akses_sistem = Sistem::findOrFail($id);

        if ($type == 'approve') {
            $akses_sistem->is_manager_approve = 1;
            $akses_sistem->final_status = 'Manager Approve';
            $akses_sistem->manager_note = $request->manager_note;
            $akses_sistem->manager_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $akses_sistem->is_manager_approve = 0;
            $akses_sistem->final_status = 'Manager Reject';
            $akses_sistem->manager_note = $request->manager_note;
            $akses_sistem->manager_approve_by = Auth::user()->id;
            $akses_sistem->is_finish = 0;
            $akses_sistem->is_confirm = 0;
            $return = "Reject Successfully";
        }
        $akses_sistem->manager_approval_date = Carbon::now();
        $akses_sistem->save();
        return $return;
    }

    public function manager_approved()
    {
        return view('website.pages.akses_sistem.manager_approved');
    }

    public function manager_approved_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = Sistem::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
            ->whereNotNull('is_manager_approve')
            ->join('users', 'form_sistem.created_by', 'users.id')
                        ->leftJoin('users as manager', 'form_sistem.manager_approve_by', 'manager.id')
                        ->leftJoin('users as it', 'form_sistem.it_approve_by', 'it.id')
                        ->leftJoin('users as it_mgr', 'form_sistem.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('users as on_progress', 'form_sistem.on_progress_by', 'on_progress.id')
                        ->leftJoin('users as finish', 'form_sistem.finish_by', 'finish.id')
                        ->select('form_sistem.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
            ->orderBy('manager_approval_date', 'DESC')
            ->with('form_sistem_app')
            ->with('form_sistem_user');

        return DataTables::eloquent($data)->make(true);
    }

    /// ITD APPROVE ///

    public function it_approval()
    {
        return view('website.pages.akses_sistem.it_approval');
    }

    public function it_approval_ajax(Request $request)
    {
        $data = Sistem::where('final_status', 'Manager Approve')
                        ->join('users', 'form_sistem.created_by', 'users.id')
                        ->leftJoin('users as manager', 'form_sistem.manager_approve_by', 'manager.id')
                        ->leftJoin('users as it', 'form_sistem.it_approve_by', 'it.id')
                        ->leftJoin('users as it_mgr', 'form_sistem.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('users as on_progress', 'form_sistem.on_progress_by', 'on_progress.id')
                        ->leftJoin('users as finish', 'form_sistem.finish_by', 'finish.id')
                        ->select('form_sistem.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'ASC')
                        ->with('form_sistem_app')
                        ->with('form_sistem_user');

        return DataTables::eloquent($data)->make(true);
    }

    public function it_approve(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $akses_sistem = Sistem::findOrFail($id);

        if ($type == 'approve') {
            $akses_sistem->is_it_approve = 1;
            $akses_sistem->final_status = 'IT Approve';
            $akses_sistem->it_note = $request->it_note;
            $akses_sistem->it_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $akses_sistem->is_it_approve = 0;
            $akses_sistem->final_status = 'IT Reject';
            $akses_sistem->it_note = $request->it_note;
            $akses_sistem->is_finish = 0;
            $akses_sistem->is_confirm = 0;
            $akses_sistem->it_approve_by = Auth::user()->id;
            $return = "Reject Successfully";
        }
        $akses_sistem->it_approval_date = Carbon::now();
        $akses_sistem->save();

        if ($request->notifikasi == 'Ya') {
            $isi = "FORM AKSES SISTEM\n";
            $isi .= "*TUNGGU APPROVE IT MANAGER*";
            $isi .= "\n\nREQUESTOR";
            $isi .= "\nNama : *" . $akses_sistem->createdBy->name . "*";
            $isi .= "\nDepartment : *" . $akses_sistem->createdBy->departments->pluck('code')->implode(', ') . "*";
            $isi .= "\nPurpose : " . $akses_sistem->purpose;
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
        return view('website.pages.akses_sistem.it_approved');
    }

    public function it_approved_ajax(Request $request)
    {
        $data = Sistem::whereNotNull('is_it_approve')
                        ->join('users', 'form_sistem.created_by', 'users.id')
                        ->leftJoin('users as manager', 'form_sistem.manager_approve_by', 'manager.id')
                        ->leftJoin('users as it', 'form_sistem.it_approve_by', 'it.id')
                        ->leftJoin('users as it_mgr', 'form_sistem.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('users as on_progress', 'form_sistem.on_progress_by', 'on_progress.id')
                        ->leftJoin('users as finish', 'form_sistem.finish_by', 'finish.id')
                        ->select('form_sistem.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('manager_approval_date', 'DESC')
                         ->with('form_sistem_app')
                        ->with('form_sistem_user');

        return DataTables::eloquent($data)->make(true);
    }

    /// ITD MGR APPROVE ///

    public function it_mgr_approval()
    {
        return view('website.pages.akses_sistem.it_mgr_approval');
    }

    public function it_mgr_approval_ajax(Request $request)
    {
        $data = Sistem::where('final_status', 'IT Approve')
                        ->join('users', 'form_sistem.created_by', 'users.id')
                        ->leftJoin('users as manager', 'form_sistem.manager_approve_by', 'manager.id')
                        ->leftJoin('users as it', 'form_sistem.it_approve_by', 'it.id')
                        ->leftJoin('users as it_mgr', 'form_sistem.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('users as on_progress', 'form_sistem.on_progress_by', 'on_progress.id')
                        ->leftJoin('users as finish', 'form_sistem.finish_by', 'finish.id')
                        ->select('form_sistem.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'ASC')
                         ->with('form_sistem_app')
                        ->with('form_sistem_user');

        return DataTables::eloquent($data)->make(true);
    }

    public function it_mgr_approve(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $akses_sistem = Sistem::findOrFail($id);

        if ($type == 'approve') {
            $akses_sistem->is_it_mgr_approve = 1;
            $akses_sistem->final_status = 'IT MGR Approve';
            $akses_sistem->it_mgr_note = $request->it_mgr_note;
            $akses_sistem->it_mgr_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $akses_sistem->is_it_mgr_approve = 0;
            $akses_sistem->final_status = 'IT MGR Reject';
            $akses_sistem->it_mgr_note = $request->it_mgr_note;
            $akses_sistem->it_mgr_approve_by = Auth::user()->id;
            $akses_sistem->is_finish = 0;
            $akses_sistem->is_confirm = 0;
            $return = "Reject Successfully";
        }
        $akses_sistem->it_mgr_approval_date = Carbon::now();
        $akses_sistem->save();
        return $return;
    }

    public function it_mgr_approved()
    {
        return view('website.pages.akses_sistem.it_mgr_approved');
    }

    public function it_mgr_approved_ajax(Request $request)
    {
        $data = Sistem::whereNotNull('is_it_mgr_approve')
                        ->join('users', 'form_sistem.created_by', 'users.id')
                        ->leftJoin('users as manager', 'form_sistem.manager_approve_by', 'manager.id')
                        ->leftJoin('users as it', 'form_sistem.it_approve_by', 'it.id')
                        ->leftJoin('users as it_mgr', 'form_sistem.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('users as on_progress', 'form_sistem.on_progress_by', 'on_progress.id')
                        ->leftJoin('users as finish', 'form_sistem.finish_by', 'finish.id')
                        ->select('form_sistem.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'DESC')
                         ->with('form_sistem_app')
                        ->with('form_sistem_user');

        return DataTables::eloquent($data)->make(true);
    }

    /// EXECUTION ///

    public function execution()
    {
        return view('website.pages.akses_sistem.execution');
    }

    public function execution_ajax(Request $request)
    {
        $data = Sistem::whereIn('final_status', ['IT MGR Approve', 'On Progress'])
                        ->join('users', 'form_sistem.created_by', 'users.id')
                        ->leftJoin('users as manager', 'form_sistem.manager_approve_by', 'manager.id')
                        ->leftJoin('users as it', 'form_sistem.it_approve_by', 'it.id')
                        ->leftJoin('users as it_mgr', 'form_sistem.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('users as on_progress', 'form_sistem.on_progress_by', 'on_progress.id')
                        ->leftJoin('users as finish', 'form_sistem.finish_by', 'finish.id')
                        ->select('form_sistem.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'ASC')
                        ->with('form_sistem_app')
                        ->with('form_sistem_user');

        return DataTables::eloquent($data)->make(true);
    }

    public function execution_approve(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $akses_sistem = Sistem::findOrFail($id);
        $akses_sistemusers = SistemUser::where('sistem_id', $id)->get();
        $akses_sistemapps = SistemApp::where('sistem_id', $id)->get();

        $user = $akses_sistem->createdBy;

        if ($type == 'approve') {
            $akses_sistem->is_finish = 1;
            $akses_sistem->is_confirm = 0;
            $akses_sistem->finish_by = Auth::user()->id;
            $akses_sistem->final_status = 'Finished';
            $akses_sistem->finish_note = $request->finish_note;
            $akses_sistem->finish_date = Carbon::now();
            $return = "Approve Successfully";
        } else if ($type == 'progress') {
            $akses_sistem->is_on_progress = 1;
            $akses_sistem->final_status = 'On Progress';
            $akses_sistem->on_progress_note = $request->on_progress_note;
            $akses_sistem->on_progress_by = Auth::user()->id;
            $akses_sistem->on_progress_date = Carbon::now();
            $return = "Progress Successfully";
        } else {
            $akses_sistem->is_finish = 0;
            $akses_sistem->is_confirm = 0;
            $akses_sistem->final_status = 'Rejected';
            $akses_sistem->finish_note = $request->finish_note;
            $akses_sistem->finish_by = Auth::user()->id;
            $akses_sistem->finish_date = Carbon::now();
            $return = "Reject Successfully";
        }
        $akses_sistem->save();

        if ($request->notifikasi == 'Ya') {
            $isi = "FORM AKSES SISTEM\n\n";

            $isi .= "User : \n";
            $nouser = 1;
            foreach($akses_sistemusers as $akses_sistemuser)
            {
                $isi .= $nouser++ . ". " . $akses_sistemuser->npk . " - " . $akses_sistemuser->name . "\n";
            }

            $isi .= "\nLokasi : " . $akses_sistem->lokasi;
            $isi .= "\nWaktu Akses : " . $akses_sistem->date_access_start . " - " . $akses_sistem->date_access_end;
            $isi .= "\nPurpose : " . $akses_sistem->purpose;

            $isi .= "\n\nStatus : *Finished*";

            $isi .= "\n\nManager Note : " . $akses_sistem->manager_note;
            $isi .= "\nITD Note : " . $akses_sistem->it_note;
            $isi .= "\nITD Manager Note : " . $akses_sistem->it_mgr_note;
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
        return view('website.pages.akses_sistem.finished');
    }

    public function finished_ajax(Request $request)
    {
        $data = Sistem::whereNotNull('is_finish')
                        ->join('users', 'form_sistem.created_by', 'users.id')
                        ->leftJoin('users as manager', 'form_sistem.manager_approve_by', 'manager.id')
                        ->leftJoin('users as it', 'form_sistem.it_approve_by', 'it.id')
                        ->leftJoin('users as it_mgr', 'form_sistem.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('users as on_progress', 'form_sistem.on_progress_by', 'on_progress.id')
                        ->leftJoin('users as finish', 'form_sistem.finish_by', 'finish.id')
                        ->select('form_sistem.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'DESC')
                        ->with('form_sistem_app')
                        ->with('form_sistem_user');

        return DataTables::eloquent($data)->make(true);
    }
}
