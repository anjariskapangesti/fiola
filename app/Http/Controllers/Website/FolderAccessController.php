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
    
    public function show_manager_approval_ajax(Request $request)
    {
        // return Auth::user()->dept_id;
        $data = FolderAccess::where('created_dept', Auth::user()->dept_id)->where('final_status','created');
        // return $data;
        return DataTables::eloquent($data)->make(true);
    }
}
