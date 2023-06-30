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
    /// Form Folder Access ///
    public function create()
    {
        $depts = Department::all();
        return view('website.pages.folder-access.create', compact(['depts']));
    }

    public function store(Request $request)
    {
        // dd($request);
        try {
            $request->validate([
                'username' => 'required',
                'folder' => 'required',
                'subfolder' => 'required',
                'permission' => 'required',
                'purpose' => 'required',
            ]);


            DB::transaction(function () use ($request) {
                // $spv_app = null;
                // $mgr_app = null;
                // $spv_app_by = null;
                // $mgr_app_by = null;
                $final_status = 'Created';
                $user = Auth::user();

                // if ($user->hasPermissionTo('spv_app')) {
                //     $spv_app = Carbon::now();
                //     $final_status = 'SPV Approved';
                //     $spv_app_by = $user->id;
                // }
                // if ($user->hasPermissionTo('mgr_app')) {
                //     $spv_app = Carbon::now();
                //     $mgr_app = Carbon::now();
                //     $spv_app_by = $user->id;
                //     $mgr_app_by = $user->id;
                //     $final_status = 'MGR Approved';
                // }
                $folderaccess = FolderAccess::create([
                    'username' => $request->username,
                    'purpose' => $request->purpose,
                    'created_by' => $user->id,
                    'created_dept' => $user->dept_id,
                    'final_status' => $final_status,
                ]);
                
                // $date = Carbon::now();
                // $reg_no = $izin->id . '/' . 'GA/FIMBKA/' . $date->format('m') . '/' . $date->format('Y');
                // $izin->reg_no = $reg_no;
                $folderaccess->save();

                for ($i = 0; $i < count($request->folder); $i++) {
                    FolderAccessPath::create([
                        'folder_access_id' => 1,
                        'folder' => $request->folder[$i],
                        'subfolder' => $request->subfolder[$i],
                        'permission' => $request->permission[$i],
                    ]);
                }
            });
            return redirect()->back()->with('success', 'Sukses Menyimpan Data');
        } catch (Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    // public function store(Request $request)
    // {
    //     // Validasi data input
    //     $request->validate([
    //         'username' => 'required|string',
    //         'folder' => 'required|array',
    //         'subfolder' => 'required|array',
    //         'permission' => 'required',
    //         'purpose' =>'required',
    //         // 'paths.*' => 'string',
    //     ]);

    //     // Simpan entitas induk "FolderAccess"
    //     $folderAccess = FolderAccess::create([
    //         'username' => $request->username,
    //         'purpose' => $request->purpose,
    //         'final_status' => 'created',
    //     ]);

    //     // Simpan entitas anak "FolderAccessPath"
    //     foreach ($request->input('folder') as $path) {
    //         FolderAccessPath::create([
    //             'folder_access_id' => $folderAccess->id,
    //             'folder' => $path,
    //             'subfolder' => $request->subfolder
    //         ]);
    //     }
        

    //     // Redirect atau berikan respon sukses
    //     return redirect()->back()->with('success', 'Sukses Menyimpan Data');
    // }

    // END //
}
