<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Folder;
use Carbon\Carbon;
use DataTables;
use Auth;

class FolderController extends Controller
{
    public function create()
    {
        $folders = Folder::orderBy('name', 'ASC')->get();
        
        return view('website.pages.folder.create', compact('folders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required' ,
        ]);
        
        try
        {
            Folder::create([
                'name' => $request->name ,                
            ]);
            // return redirect()->back()->with('success', 'Success Add folder');
            return redirect('/folder/show_data_folder')->with('success', 'Success Add Folder');
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function show_data_folder()
    {
        return view('website.pages.folder.show_data_folder');
    }

    public function show_data_folder_ajax(Request $request)
    {
        // return Auth::user()->dept_id;
        $data = Folder::orderBy('name');
        // return $data;
        return DataTables::eloquent($data)->make(true);
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $folder = Folder::find($id);
        if (Auth::user()->can('can_master')) {
            $folder->delete();
            
            return "Folder deleted successfully";
        }

        return "Error";
    }
}
