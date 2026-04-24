<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Reminder;
use App\Models\User;
use App\Models\Department;
use Carbon\Carbon;
use DataTables;
use Auth;

use App\Models\Account;
use App\Models\FolderAccess;
use App\Models\NewFolder;
use App\Models\Software;
use App\Models\Hardware;
use App\Models\Vpn;
use App\Models\Project;
use App\Models\Fitur;

use App\Mail\AlertMail;
use Illuminate\Support\Facades\Mail;

class ReminderController extends Controller
{
    public function list()
    {
        $users = User::orderBy('name', 'ASC')->get();
        $departments = Department::orderBy('name', 'ASC')->get();

        return view('website.pages.reminder.list', compact('users', 'departments'));
    }

    public function list_ajax(Request $request)
    {
        $data = Reminder::select('users.name as user_name', 'users.nohp as user_nohp', 'users.email as user_email', 'departments.name as department_name',
                                 'users.id as user_id', 'departments.id as department_id', 'reminders.id')
                        ->join('users', 'reminders.user_id', 'users.id')
                        ->join('departments', 'reminders.department_id', 'departments.id')
                        ->orderBy('users.name', 'asc');
        
        return DataTables::eloquent($data)->make(true);
    }

    public function create()
    {
        $users = User::orderBy('name', 'ASC')->get();
        $departments = Department::orderBy('name', 'ASC')->get();
        
        return view('website.pages.reminder.create', compact('users', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required' ,            
            'user_id' => 'required' ,            
        ]);
        
        try
        {
            Reminder::create([
                'department_id' => $request->department_id,            
                'user_id' => $request->user_id,            
            ]);
            return redirect('/reminder/list')->with('success', 'Success Add Reminder');
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function edit(Request $request)
    {
        $id = $request->id;

        $reminders = Reminder::find($id);

        if (Auth::user()->can('can_master')) {
            $reminders->update([
                'user_id' => $request->user_id,            
                'department_id' => $request->department_id,            
            ]);
            
            return "Reminder updated successfully";
        }

        return "Error";
    }

    public function destroy(Request $request)
    {
        $id = $request->id;

        $reminders = Reminder::find($id);

        if (Auth::user()->can('can_master')) {
            $reminders->delete();
            
            return "Reminder deleted successfully";
        }

        return "Error";
    }

    public function email_manager()
    {
        $reminders = Reminder::all();

        foreach ($reminders as $reminder) {
            $departmentId = $reminder->department_id;
            $manager_department = $reminder->manager;

            $accounts = Account::where('created_dept', $departmentId)->where('final_status', 'created')->get();
            $folderAccess = FolderAccess::where('created_dept', $departmentId)->where('final_status', 'created')->get();
            $newFolders = NewFolder::where('created_dept', $departmentId)->where('final_status', 'created')->get();
            $software = Software::where('created_dept', $departmentId)->where('final_status', 'created')->get();
            $hardware = Hardware::where('created_dept', $departmentId)->where('final_status', 'created')->get();
            $vpn = Vpn::where('created_dept', $departmentId)->where('final_status', 'created')->get();
            $projects = Project::where('created_dept', $departmentId)->where('final_status', 'created')->get();
            $fiturs = Fitur::where('created_dept', $departmentId)->where('final_status', 'created')->get();

            $allData = [
                'accounts' => $accounts,
                'folderAccess' => $folderAccess,
                'newFolders' => $newFolders,
                'software' => $software,
                'hardware' => $hardware,
                'vpn' => $vpn,
                'projects' => $projects,
                'fiturs' => $fiturs,
                'departmentId' => $departmentId,
            ];
            dd($manager_department);
            if ($allData->count() > 0) {
                $email = $manager_department->email;
                $managerEmails = $reminders->where('department_id', $departmentId)->pluck('manager_email')->toArray();
                dd($managerEmails);
                $to = $email;
                $subject = 'FIOLA (Form ITD Online Application)';
                $data = ['message' => 'Ada tunggu approve'];
                $url = 'https://fiola.aiia.co.id';
                    
                Mail::to($to)->send(new AlertMail($data, $subject, $url));
            }
        }
    }
}
