<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Alert;
use App\Models\User;
use App\Models\Department;
use App\Models\Account;
use App\Models\FolderAccess;
use App\Models\NewFolder;
use App\Models\Software;
use App\Models\Hardware;
use App\Models\Vpn;
use App\Models\Project;
use App\Models\Fitur;
use App\Models\Relayout;
use Illuminate\Support\Facades\DB;
use Auth;
use Carbon\Carbon;
use DataTables;

use App\Mail\AlertMail;

use Illuminate\Support\Facades\Mail;

class AlertController extends Controller
{
    public function alert()
    {
        // Daftar model yang ingin diambil departemennya
        $models = ['Account', 'FolderAccess', 'NewFolder', 'Software', 'Hardware', 'Vpn', 'Project', 'Fitur', 'Relayout', 'Network'];
        $waitingManagers = collect([]);
        $waitingIts = collect([]);
        $waitingItMgrs = collect([]);
    
        // Ambil semua departemen yang memiliki data 'created' pada tiap model
        foreach ($models as $model) {
            $modelClass = 'App\\Models\\' . $model;
            $waitingManager = $modelClass::where('final_status', 'created')->pluck('created_dept')->unique();
            $waitingManagers = $waitingManagers->merge($waitingManager);
        }

        foreach ($models as $model) {
            $modelClass = 'App\\Models\\' . $model;
            $waitingIt = $modelClass::where('final_status', 'Manager Approve')->pluck('final_status')->unique();

            if ($waitingIt->isEmpty()) {
                $waitingIt = $modelClass::where('final_status', 'IT MGR Approve')->pluck('final_status')->unique();
            }

            $waitingIts = $waitingIts->merge($waitingIt);
        }
        foreach ($models as $model) {
            $modelClass = 'App\\Models\\' . $model;
            $waitingItMgr = $modelClass::where('final_status', 'IT Approve')->pluck('final_status')->unique();
            $waitingItMgrs = $waitingItMgrs->merge($waitingItMgr);
        }
        // Ambil nilai unik dari koleksi departemen
        $waitingManagers = $waitingManagers->unique();
        $waitingIts = $waitingIts->unique();
        $waitingItMgrs = $waitingItMgrs->unique();

        foreach ($waitingManagers as $waitingManager) {
            $alertManager = Alert::where('department', $waitingManager)->where('role', 'Manager')->first();

            if ($alertManager) {
                $to = $alertManager->email;
                $role = 'Manager';
                $subject = 'FIOLA (Form ITD Online Application)';
                $data = 'tunggu approve Manager';
                $url = 'https://fiola.aiia.co.id';

                Mail::to($to)->send(new AlertMail($data, $subject, $url, $role));
            }
        }

        foreach ($waitingIts as $waitingIt) {
            $alertIts = Alert::where('role', 'IT')->get();

            foreach ($alertIts as $alertIt) {
                if ($waitingIt == 'Manager Approve') {
                    $text = 'approve IT';
                } elseif ($waitingIt == 'IT MGR Approve') {
                    $text = 'execution';
                }
        
                if ($alertIt) {
                    $to = $alertIt->email;
                    $role = 'ITD';
                    $subject = 'FIOLA (Form ITD Online Application)';
                    $data = 'tunggu ' . $text;
                    $url = 'https://fiola.aiia.co.id';
    
                    Mail::to($to)->send(new AlertMail($data, $subject, $url, $role));
                }
            } 
        }

        foreach ($waitingItMgrs as $waitingItMgr) {
            $alertItMgr = Alert::where('role', 'IT Manager')->first();
    
            if ($alertItMgr) {
                $to = $alertItMgr->email;
                $role = 'Manager ITD';
                $subject = 'FIOLA (Form ITD Online Application)';
                $data = 'tunggu approve IT Manager';
                $url = 'https://fiola.aiia.co.id';
    
                Mail::to($to)->send(new AlertMail($data, $subject, $url, $role));
            }
        }
    
        return response()->json(['success', 'Email terkirim']);
    }

    public function alert_view()
    {
        return view('website.pages.emails.alert_view');
    }

    public function create()
    {
        $users = User::whereHas('permissions', function ($query) {
            $query->where('permissions.name', 'apps_fiola');
        })->select('users.*', DB::raw('GROUP_CONCAT(departments.name SEPARATOR ", ") as department_names'))
            ->join('model_has_departments', 'users.id', 'model_has_departments.model_id')
            ->join('departments', 'model_has_departments.department_id', 'departments.id')
            ->groupBy('users.id')
            ->orderBy('users.name', 'ASC')
            ->get();

        $departments = Department::orderBy('name')->get();

        return view('website.pages.alert.create', compact('users', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        
        try
        {
            Alert::create([
                'department' => $request->department,
                'role' => $request->role,
                'name' => $request->name,
                'email' => $request->email,
                'nohp' => $request->nohp,
            ]);
            return redirect('/alert/list')->with('success', 'Create Successfully');
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function edit($id)
    {
        $alert = Alert::findOrFail($id);

        return view('website.pages.alert.edit', compact('alert'));
    }

    public function update(Request $request, $id)
    {
        $alert = Alert::findOrFail($id);

        $request->validate([
            'name' => 'required|regex:/^[^\s]+$/',
        ], [
            'name.regex' => 'The name may not contain spaces.',
        ]);

        try
        {
            $alert->update([
                'name' => $request->name ,                
            ]);
            return redirect('/alert/list')->with('success', 'Edit Successfully');
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function list()
    {
        return view('website.pages.alert.list');
    }

    public function list_ajax(Request $request)
    {
        $data = Alert::select('alerts.*', 'departments.name as department_name')
                    ->join('departments', 'alerts.department', 'departments.id')
                    ->orderBy('name');

        return DataTables::eloquent($data)->make(true);
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $alert = Alert::find($id);
        if (Auth::user()->can('apps_fiola')) {
            $alert->delete();
            
            return 'Delete Successfully';
        } else {
            return response()->json(['error' => 'You are not authorized to delete this item.'], 403);
        }
    }
}
