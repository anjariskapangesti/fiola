<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SubFolder;
use App\Models\Folder;
use Carbon\Carbon;
use DataTables;
use Auth;

class SubFolderController extends Controller
{
    public function create()
    {
        $folders = Folder::orderBy('name', 'ASC')->get();
        
        return view('website.pages.subfolder.create', compact('folders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required' ,
        ]);
        
        try
        {
            SubFolder::create([
                'name' => $request->name , 
                'folder_id' => $request->folder_id,               
            ]);
            // return redirect()->back()->with('success', 'Success Add subfolder');
            return redirect('/subfolder/show_data_subfolder')->with('success', 'Success Add subfolder');
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function show_data_subfolder()
    {
        return view('website.pages.subfolder.show_data_subfolder');
    }

    public function show_data_subfolder_ajax(Request $request)
    {
        // return Auth::user()->dept_id;
        $data = SubFolder::orderBy('name')
                        ->join('folders', 'subfolders.folder_id', '=', 'folders.id')
                        ->select('subfolders.*', 'folders.name as folder_name');;
        // return $data;
        return DataTables::eloquent($data)->make(true);
    }

    public function destroy($id)
    {
        SubFolder::findOrFail($id)->delete();

        return redirect()->back()->with('error', 'Delete Item');
    }
}
