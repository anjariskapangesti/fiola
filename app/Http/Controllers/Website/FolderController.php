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
        return view('website.pages.folder.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|regex:/^[^\s]+$/',
        ], [
            'name.regex' => 'The name may not contain spaces.',
        ]);
        
        try
        {
            Folder::create([
                'name' => $request->name ,                
            ]);
            return redirect('/folder/list')->with('success', 'Create Successfully');
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function edit($id)
    {
        $folder = Folder::findOrFail($id);

        return view('website.pages.folder.edit', compact('folder'));
    }

    public function update(Request $request, $id)
    {
        $folder = Folder::findOrFail($id);

        $request->validate([
            'name' => 'required|regex:/^[^\s]+$/',
        ], [
            'name.regex' => 'The name may not contain spaces.',
        ]);

        try
        {
            $folder->update([
                'name' => $request->name ,                
            ]);
            return redirect('/folder/list')->with('success', 'Edit Successfully');
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function list()
    {
        return view('website.pages.folder.list');
    }

    public function list_ajax(Request $request)
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
        if (Auth::user()->can('apps_fiola')) {
            $folder->delete();
            
            return 'Delete Successfully';
        } else {
            return response()->json(['error' => 'You are not authorized to delete this subfolder.'], 403);
        }
    }
}
