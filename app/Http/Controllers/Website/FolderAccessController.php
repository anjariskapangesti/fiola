<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\FolderAccess;
use App\Models\FolderAccessPath;
use App\Models\Department;
use App\Models\User;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;
use Auth;

class FolderAccessController extends Controller
{
    public function create()
    {
        $depts = Department::all();
        return view('website.pages.folder-access.create', compact(['depts']));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required',
                'folder' => 'required',
                'subfolder' => 'required',
                'permission' => 'required',
                'purpose' => 'required',
            ]);


            DB::transaction(function () use ($request) {
                $final_status = 'created';
                $user = Auth::user();

                $folderaccess = FolderAccess::create([
                    'username' => $request->username,
                    'purpose' => $request->purpose,
                    'created_by' => $user->id,
                    'created_dept' => $user->dept_id,
                    'final_status' => $final_status,
                ]);
                $folderaccess->save();

                for ($i = 0; $i < count($request->folder ); $i++) {
                    FolderAccessPath::create([
                        'folder_access_id' => $folderaccess->id,
                        'folder' => $request->folder[$i],
                        'subfolder' => $request->subfolder[$i],
                        'permission' => $request->permission[$i],
                    ]);
                }
            });
            return redirect()->back()->with('success', 'Success Create Form');
        } catch (Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function show_manager_approval()
    {
        return view('website.pages.folder-access.approval_manager');
    }
    
    // public function show_manager_approval_ajax(Request $request)
    // {
    //     // return Auth::user()->dept_id;
    //     $data = FolderAccess::where('created_dept', Auth::user()->dept_id)->where('final_status','created');
    //     // $path = FolderAccessPath::where('folder_access_id', $request->id);
    //     // return $data;
    //     return DataTables::eloquent($data)->make(true);
    // }

    public function show_manager_approval_ajax()
    {
        $data = FolderAccess::select('form_folder_access.id', 'username', DB::Raw('form_folder_access.username as creator_username'), ('form_folder_access.purpose as creator_purpose'), 'form_folder_access.created_at', 'final_status')
            ->join('users', 'form_folder_access.created_by', 'users.id')
            ->where('form_folder_access.created_dept', Auth::user()->department->id)
            ->where('final_status','created')
            ->orderBy('form_folder_access.id', 'desc')->with('form_folder_access_path')->get();
        return DataTables::of($data)->make(true);
    }

    // public function show_manager_approval_ajax(Request $request)
    // {
    //     $folderAccesses = FolderAccess::with('FolderAccessPath')->get();

    //     return DataTables::of($folderAccesses)
    //         ->addColumn('nama_folder', function ($folderAccess) {
    //             return $folderAccess->nama_folder;
    //         })
    //         ->addColumn('folder_access_paths', function ($folderAccess) {
    //             $paths = '<ul>';
    //             foreach ($folderAccess->folderAccessPaths as $folderAccessPath) {
    //                 $paths .= '<li>' . $folderAccessPath->nama_path . '</li>';
    //             }
    //             $paths .= '</ul>';
    //             return $paths;
    //         })
    //         ->rawColumns(['folder_access_paths'])
    //         ->toJson();
    // }

    public function approve_manager(Request $request)
    {
        $id=$request->id;
        
        $type=$request->type;
        
        $folderaccess = FolderAccess::findOrFail($id);
        
        if($type=='ok'){
            $folderaccess->is_manager_approve=1;
            $folderaccess->final_status='Manager Approve';
        }else{
            $folderaccess->is_manager_approve=0;
            $folderaccess->final_status='Manager Reject';
            $folderaccess->manager_note=$request->manager_note;
        }
        $folderaccess->manager_approval_date= Carbon::now();
        $folderaccess->save();
        return "Request is Saved!";        
    }

    /// ITD APPROVE ///

    public function show_it_approval()
    {
        return view('website.pages.folder-access.approval_it');
    }

    public function show_it_approval_ajax(Request $request)
    {
        $data = FolderAccess::where('final_status','Manager Approve');
        return DataTables::eloquent($data)->make(true);
    }

    public function show_data_it_approval()
    {
        $depts = Department::all();
        return view('website.pages.folder-access.show_data_it_approval', compact(['depts']));
    }

    public function show_data_it_approval_ajax(Request $request)
    {
        // return Auth::user()->dept_id;
        $data = FolderAccess::where('created_dept', Auth::user()->dept_id)->where('is_it_approve','1');
        // return $data;
        return DataTables::eloquent($data)->make(true);
    }

    public function approve_it(Request $request)
    {
        $id=$request->id;
        $type=$request->type;
        $folderaccess = FolderAccess::findOrFail($id);
        if($type=='ok'){
            $folderaccess->is_it_approve=1;
            $folderaccess->final_status='IT Approve';
        }else{
            $folderaccess->is_it_approve=0;
            $folderaccess->final_status='IT Reject';
            $folderaccess->it_note=$request->it_note;
        }
        $folderaccess->it_approval_date= Carbon::now();
        $folderaccess->save();
        return "Request is Saved!";
    }
}
