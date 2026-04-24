<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\App;
use Carbon\Carbon;
use DataTables;
use Auth;

class AppController extends Controller
{
    public function list()
    {
        return view('website.pages.app.list');
    }

    public function list_ajax(Request $request)
    {
        $data = App::orderBy('name', 'ASC');
        
        return DataTables::eloquent($data)->make(true);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
    
        try
        {
            App::create([
                'name' => $request->name,            
                'url' => $request->url,            
                'description' => $request->description,
            ]);
            return response()->json(['success' => 'Create Successfully'], 200);
        }
        catch(\Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $id = $request->id;

        try
        {
            // Find the existing record by ID
            $app = App::findOrFail($id);
            $app->name = $request->name;
            $app->url = $request->url;
            $app->description = $request->description;
            $app->save();
    
            // // Update the record with the new data
            // $app->update([
            //     'name' => $request->name,            
            //     'url' => $request->url,            
            //     'description' => $request->description,
            // ]);
    
            return response()->json(['success' => 'Update Successfully'], 200);
        }
        catch(\Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->id;

        $apps = App::find($id);
        if (Auth::user()->can('apps_fiola')) {
            $apps->delete();
            
            return "Delete Successfully";
        }

        return "Error";
    }
}
