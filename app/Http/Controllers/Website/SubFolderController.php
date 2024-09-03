<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SubFolder;
use App\Models\Folder;
use Carbon\Carbon;
use DataTables;
use Auth;

use App\Exports\SubFolderExport;
use Maatwebsite\Excel\Facades\Excel;

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
            'name' => 'required',
            'folder_id' => 'required',
        ], [
            'folder_id.required' => 'The folder name field is required.',
        ]);
        
        try
        {
            SubFolder::create([
                'name' => $request->name , 
                'folder_id' => $request->folder_id,               
            ]);
            return redirect()->route('website.subfolder.list')->with('success', 'Create Successfully');
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function edit($id)
    {
        $subfolder = SubFolder::findOrFail($id);
        $folders = Folder::orderBy('name', 'ASC')->get();
        
        return view('website.pages.subfolder.edit', compact('subfolder', 'folders'));
    }

    public function update(Request $request, $id)
    {
        $subfolder = SubFolder::findOrFail($id);
        
        $request->validate([
            'name' => 'required|regex:/^[^\s]+$/',
            'folder_id' => 'required',
        ], [
            'name.regex' => 'The name may not contain spaces.',
            'folder_id.required' => 'The folder name field is required.',
        ]);
        
        try
        {
            $subfolder->update([
                'name' => $request->name , 
                'folder_id' => $request->folder_id,               
            ]);
            return redirect()->route('website.subfolder.list')->with('success', 'Edit Successfully');
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function list()
    {
        return view('website.pages.subfolder.list');
    }

    public function list_ajax(Request $request)
    {
        // return Auth::user()->dept_id;
        $data = SubFolder::orderBy('name')
                        ->join('folders', 'subfolders.folder_id', '=', 'folders.id')
                        ->select('subfolders.*', 'folders.name as folder_name');;
        // return $data;
        return DataTables::eloquent($data)->make(true);
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $subfolder = SubFolder::find($id);
        if (Auth::user()->can('apps_fiola')) {
            $subfolder->delete();
            
            return 'Delete Successfully';
        } else {
            return response()->json(['error' => 'You are not authorized to delete this item.'], 403);
        }
    }

    public function export()
    {
        return Excel::download(new SubFolderExport, 'subfolders.xlsx');
    }
}
