<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Guide;
use Carbon\Carbon;
use DataTables;
use Auth;

class GuideController extends Controller
{
    public function create()
    {
        return view('website.pages.guide.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'form_name' => 'required',
            'lampiran' => 'required',
        ]);
        
        try
        {
            if ($request->hasFile('lampiran')) {
                $lampiranExtension = $request->lampiran->getClientOriginalExtension();
                $lampiranFileName = 'guide_' . $request->form_name . '.' . $lampiranExtension;
                $lampiranPath = $request->lampiran->storeAs('guide', $lampiranFileName, 'public');
            }

            Guide::create([
                'form_name' => $request->form_name,            
                'lampiran' => 'guide/' . $lampiranFileName,            
            ]);
            return redirect('/guide/list')->with('success', 'Create Successfully');
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function list()
    {
        return view('website.pages.guide.list');
    }

    public function list_ajax(Request $request)
    {
        $data = Guide::orderBy('form_name', 'ASC');
        
        return DataTables::eloquent($data)->make(true);
    }

    public function edit(Request $request)
    {
        $id = $request->id;

        $guides = Guide::find($id);
        if (Auth::user()->can('can_master')) {
            $guides->update([
                'name' => $request->name,            
                'cost' => $request->cost,            
                'spesifikasi' => $request->spesifikasi,
            ]);
            
            return "Update Successfully";
        }

        return "Error";
    }

    public function destroy(Request $request)
    {
        $id = $request->id;

        $guides = Guide::findOrFail($id);

        if (Auth::user()->can('apps_fiola')) {
            $guides->delete();
            
            return "Delete Successfully";
        }

        return "Error";
    }
}
