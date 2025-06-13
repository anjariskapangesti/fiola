<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Izin;
use App\Models\IzinUser;
use App\Models\IzinBarang;
use App\Models\User;
use App\Models\Alert;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;
use Auth;

use App\Traits\HasAjaxList;

class IzinController extends Controller
{
    use HasAjaxList;

    public function create()
    {
        $auth = User::where('id', Auth::user()->id)
                                    ->whereNull('nohp')
                                    ->count(); 

        $data = Izin::where('created_by', Auth::user()->id)
                            ->where(function($query) {
                                    $query->where('final_status', 'LIKE', '%Reject%')
                                        ->orWhere('final_status', 'Finished');
                            })
                            ->where('is_confirm', 0)
                            ->count();
        
        if ($auth > 0) {
            return redirect()->route('website.user.edit');
        } else if($data > 0){
            return redirect()->route('website.izin.list')->with('info', 'Please confirm!');
        }else{
            return view('website.pages.izin.create');
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
                'lokasi' => 'required',
                'purpose' => 'required',
            ]);
    
            $year = date('y');
            $month = date('m');
            $lastForm = DB::table('form_izin')
                        ->select('no_reg')
                        ->orderBy('no_reg', 'desc')
                        ->first();
            $lastNumber = ($lastForm) ? substr($lastForm->no_reg, -3) : '000';
            
            $lastMonth = ($lastForm) ? substr($lastForm->no_reg, 6, 2) : '00';            
            if ($lastMonth !== $month){
                $lastNumber = '000';
            }            
            $newNumber = str_pad((intval($lastNumber) + 1), strlen($lastNumber), '0', STR_PAD_LEFT);            
            $no_reg = 'IMA/' . $year . $month . '/' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    
            $user = Auth::user();
    
            $izin = new Izin();
            $izin->no_reg = $no_reg;
            $izin->lokasi = $request->lokasi;
            $izin->date_access_start = $request->date_access_start;
            $izin->date_access_end = $request->date_access_end;
            $izin->purpose = $request->purpose;
            $izin->created_by = $user->id;
            $izin->created_dept = $user->departments->pluck('id')->first();
            $izin->final_status = $finalStatus;
            $izin->is_manager_approve = $isManagerApprove;
            $izin->is_it_approve = $isItApprove;
            $izin->is_it_mgr_approve = $isItManagerApprove;
            $izin->manager_approval_date = $managerApprovalDate;
            $izin->it_approval_date = $itApprovalDate;
            $izin->it_mgr_approval_date = $itManagerApprovalDate;
            $izin->save();
    
            for ($i = 0; $i < count($request->npk ); $i++) {
                IzinUser::create([
                    'izin_id' => $izin->id,
                    'npk' => $request->npk[$i],
                    'name' => $request->name[$i],
                    'asal_perusahaan' => $request->asal_perusahaan[$i],
                    'no_hp' => $request->no_hp[$i],
                ]);
            }

            for ($i = 0; $i < count($request->nama_barang ); $i++) {
                IzinBarang::create([
                    'izin_id' => $izin->id,
                    'nama_barang' => $request->nama_barang[$i],
                    'no_device' => $request->no_device[$i],
                    'merk' => $request->merk[$i],
                    'jumlah' => $request->jumlah[$i],
                    'satuan' => $request->satuan[$i],
                    'keterangan' => $request->keterangan[$i],
                ]);
            }
    
            return redirect()->route('website.izin.list')->with('success', 'Create Successfully');
        } catch (Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function list()
    {
        return view('website.pages.izin.list');
    }

    public function list_ajax(Request $request)
    {
        return $this->generateAjaxList(
            \App\Models\Izin::class,
            'form_izin',
            ['form_izin_barang', 'form_izin_user']
        );
    }
    
    public function approve_form(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $izin = Izin::findOrFail($id);

        if ($type == 'confirm') {
            $izin->is_confirm = 1;
        } else {
            $izin->is_confirm = 0;
        }
        $izin->save();

        return "Confirm Successfully";
    }

    public function delete_form(Request $request)
    {
        $id = $request->id;

        $izin = Izin::findOrFail($id);
        $izin->delete();

        return "Delete Successfully";
    }
    
    // MGR //
    public function manager_approval()
    {
        return view('website.pages.izin.manager_approval');
    }

    public function manager_approval_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = Izin::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
            ->where('final_status', 'created')
            ->join('public.users', 'form_izin.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_izin.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_izin.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_izin.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_izin.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_izin.finish_by', 'finish.id')
                        ->select('form_izin.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
            ->orderBy('created_at', 'ASC')
            ->with('form_izin_barang')
            ->with('form_izin_user');

        return DataTables::eloquent($data)->make(true);
    }

    public function manager_approve(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $izin = Izin::findOrFail($id);

        if ($type == 'approve') {
            $izin->is_manager_approve = 1;
            $izin->final_status = 'Manager Approve';
            $izin->manager_note = $request->manager_note;
            $izin->manager_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $izin->is_manager_approve = 0;
            $izin->final_status = 'Manager Reject';
            $izin->manager_note = $request->manager_note;
            $izin->manager_approve_by = Auth::user()->id;
            $izin->is_finish = 0;
            $izin->is_confirm = 0;
            $return = "Reject Successfully";
        }
        $izin->manager_approval_date = Carbon::now();
        $izin->save();
        return $return;
    }

    public function manager_approved()
    {
        return view('website.pages.izin.manager_approved');
    }

    public function manager_approved_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = Izin::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
            ->whereNotNull('is_manager_approve')
            ->join('public.users', 'form_izin.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_izin.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_izin.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_izin.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_izin.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_izin.finish_by', 'finish.id')
                        ->select('form_izin.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
            ->orderBy('manager_approval_date', 'DESC')
            ->with('form_izin_barang')
            ->with('form_izin_user');

        return DataTables::eloquent($data)->make(true);
    }

    /// ITD APPROVE ///

    public function it_approval()
    {
        return view('website.pages.izin.it_approval');
    }

    public function it_approval_ajax(Request $request)
    {
        $data = Izin::where('final_status', 'Manager Approve')
                        ->join('public.users', 'form_izin.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_izin.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_izin.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_izin.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_izin.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_izin.finish_by', 'finish.id')
                        ->select('form_izin.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'ASC')
                        ->with('form_izin_barang')
                        ->with('form_izin_user');

        return DataTables::eloquent($data)->make(true);
    }

    public function it_approve(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $izin = Izin::findOrFail($id);
        
        if ($type == 'approve') {
            $izin->is_it_approve = 1;
            $izin->final_status = 'IT Approve';
            $izin->it_note = $request->it_note;
            $izin->it_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $izin->is_it_approve = 0;
            $izin->final_status = 'IT Reject';
            $izin->it_note = $request->it_note;
            $izin->is_finish = 0;
            $izin->is_confirm = 0;
            $izin->it_approve_by = Auth::user()->id;
            $return = "Reject Successfully";
        }
        $izin->it_approval_date = Carbon::now();
        $izin->save();
        
        if ($request->notifikasi == 'Ya') {
            $isi = "FORM IZIN MEMASUKI AREA LEVEL 3\n";
            $isi .= "*TUNGGU APPROVE IT MANAGER*";
            $isi .= "\n\nREQUESTOR";
            $isi .= "\nNama : *" . $izin->createdBy->name . "*";
            $isi .= "\nDepartment : *" . $izin->createdBy->departments->pluck('code')->implode(', ') . "*";
            $isi .= "\nPurpose : " . $izin->purpose;
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
        return view('website.pages.izin.it_approved');
    }

    public function it_approved_ajax(Request $request)
    {
        $data = Izin::whereNotNull('is_it_approve')
                        ->join('public.users', 'form_izin.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_izin.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_izin.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_izin.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_izin.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_izin.finish_by', 'finish.id')
                        ->select('form_izin.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('manager_approval_date', 'DESC')
                         ->with('form_izin_barang')
                        ->with('form_izin_user');

        return DataTables::eloquent($data)->make(true);
    }

    /// ITD MGR APPROVE ///

    public function it_mgr_approval()
    {
        return view('website.pages.izin.it_mgr_approval');
    }

    public function it_mgr_approval_ajax(Request $request)
    {
        $data = Izin::where('final_status', 'IT Approve')
                        ->join('public.users', 'form_izin.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_izin.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_izin.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_izin.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_izin.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_izin.finish_by', 'finish.id')
                        ->select('form_izin.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'ASC')
                         ->with('form_izin_barang')
                        ->with('form_izin_user');

        return DataTables::eloquent($data)->make(true);
    }

    public function it_mgr_approve(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $izin = Izin::findOrFail($id);

        if ($type == 'approve') {
            $izin->is_it_mgr_approve = 1;
            $izin->final_status = 'IT MGR Approve';
            $izin->it_mgr_note = $request->it_mgr_note;
            $izin->it_mgr_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $izin->is_it_mgr_approve = 0;
            $izin->final_status = 'IT MGR Reject';
            $izin->it_mgr_note = $request->it_mgr_note;
            $izin->it_mgr_approve_by = Auth::user()->id;
            $izin->is_finish = 0;
            $izin->is_confirm = 0;
            $return = "Reject Successfully";
        }
        $izin->it_mgr_approval_date = Carbon::now();
        $izin->save();
        return $return;
    }

    public function it_mgr_approved()
    {
        return view('website.pages.izin.it_mgr_approved');
    }

    public function it_mgr_approved_ajax(Request $request)
    {
        $data = Izin::whereNotNull('is_it_mgr_approve')
                        ->join('public.users', 'form_izin.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_izin.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_izin.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_izin.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_izin.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_izin.finish_by', 'finish.id')
                        ->select('form_izin.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'DESC')
                         ->with('form_izin_barang')
                        ->with('form_izin_user');

        return DataTables::eloquent($data)->make(true);
    }

    /// EXECUTION ///

    public function execution()
    {
        return view('website.pages.izin.execution');
    }

    public function execution_ajax(Request $request)
    {
        $data = Izin::whereIn('final_status', ['IT MGR Approve', 'On Progress'])
                        ->join('public.users', 'form_izin.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_izin.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_izin.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_izin.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_izin.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_izin.finish_by', 'finish.id')
                        ->select('form_izin.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'ASC')
                        ->with('form_izin_barang')
                        ->with('form_izin_user');

        return DataTables::eloquent($data)->make(true);
    }

    public function execution_approve(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $izin = Izin::findOrFail($id);
        $izinusers = IzinUser::where('izin_id', $id)->get();
        $izinbarangs = IzinBarang::where('izin_id', $id)->get();

        $user = $izin->createdBy;

        if ($type == 'approve') {
            $izin->is_finish = 1;
            $izin->is_confirm = 0;
            $izin->finish_by = Auth::user()->id;
            $izin->final_status = 'Finished';
            $izin->finish_note = $request->finish_note;
            $izin->finish_date = Carbon::now();
            $return = "Approve Successfully";
        } else if ($type == 'progress') {
            $izin->is_on_progress = 1;
            $izin->final_status = 'On Progress';
            $izin->on_progress_note = $request->on_progress_note;
            $izin->on_progress_by = Auth::user()->id;
            $izin->on_progress_date = Carbon::now();
            $return = "Progress Successfully";
        } else {
            $izin->is_finish = 0;
            $izin->is_confirm = 0;
            $izin->final_status = 'Rejected';
            $izin->finish_note = $request->finish_note;
            $izin->finish_by = Auth::user()->id;
            $izin->finish_date = Carbon::now();
            $return = "Reject Successfully";
        }
        $izin->save();

        if ($request->notifikasi == 'Ya') {
            $isi = "FORM IZIN MEMASUKI AREA LEVEL 3\n\n";

            $isi .= "User : \n";
            $nouser = 1;
            foreach($izinusers as $izinuser)
            {
                $isi .= $nouser++ . ". " . $izinuser->npk . " - " . $izinuser->name . "\n";
            }
            
            $isi .= "\nLokasi : " . $izin->lokasi;
            $isi .= "\nWaktu Akses : " . $izin->date_access_start . " - " . $izin->date_access_end;
            $isi .= "\nPurpose : " . $izin->purpose;
            
            $isi .= "\n\nStatus : *Finished*";
            
            $isi .= "\n\nManager Note : " . $izin->manager_note;
            $isi .= "\nITD Note : " . $izin->it_note;
            $isi .= "\nITD Manager Note : " . $izin->it_mgr_note;
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
        return view('website.pages.izin.finished');
    }

    public function finished_ajax(Request $request)
    {
        $data = Izin::whereNotNull('is_finish')
                        ->join('public.users', 'form_izin.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_izin.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_izin.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_izin.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_izin.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_izin.finish_by', 'finish.id')
                        ->select('form_izin.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'DESC')
                        ->with('form_izin_barang')
                        ->with('form_izin_user');

        return DataTables::eloquent($data)->make(true);
    }
}
