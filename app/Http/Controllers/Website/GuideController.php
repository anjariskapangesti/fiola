<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Guide;
use Carbon\Carbon;
use DataTables;
use Auth;

use Illuminate\Support\Facades\Storage;

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
            'lampiran' => 'required|image|max:10240', // max size in kilobytes (10MB = 10240KB)
        ]);
        
        try
        {
            $lampiranFileName = null;
    
            if ($request->hasFile('lampiran')) {
                $lampiranExtension = $request->lampiran->getClientOriginalExtension();
                $lampiranFileName = 'guide_' . $request->form_name . '.' . $lampiranExtension;
                $lampiranPath = $request->lampiran->storeAs('guide', $lampiranFileName, 'public');
            }
    
            Guide::create([
                'form_name' => $request->form_name,            
                'lampiran' => $lampiranPath,  // Store the path returned by storeAs method            
            ]);
    
            return redirect('/guide/list')->with('success', 'Create Successfully');
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
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

    public function edit($uuid)
    {
        $guide = Guide::where('uuid', $uuid)->firstOrFail();
        return view('website.pages.guide.edit', compact('guide'));
    }

    public function update(Request $request, $uuid)
    {
        $request->validate([
            'form_name' => 'required',
            'lampiran' => 'nullable|image|max:10240',
        ]);

        try {
            $guide = Guide::where('uuid', $uuid)->firstOrFail();
            if (Auth::user()->can('can_master')) {
                $data = [
                    'form_name' => $request->form_name,
                ];

                if ($request->hasFile('lampiran')) {
                    // Delete old file
                    if ($guide->lampiran) {
                        Storage::delete('public/' . $guide->lampiran);
                    }

                    $lampiranExtension = $request->lampiran->getClientOriginalExtension();
                    $lampiranFileName = 'guide_' . $request->form_name . '_' . time() . '.' . $lampiranExtension;
                    $lampiranPath = $request->lampiran->storeAs('guide', $lampiranFileName, 'public');
                    $data['lampiran'] = $lampiranPath;
                }

                $guide->update($data);

                return redirect('/guide/list')->with('success', 'Update Successfully');
            }

            return redirect()->back()->with('error', 'You do not have permission to update this item.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        
        $guide = Guide::findOrFail($id);
    
        if (Auth::user()->can('apps_fiola')) {
            $filePath = $guide->lampiran;
    
            if (!is_null($filePath)) {
                Storage::delete('public/' . $filePath);
            }
            
            $guide->delete();
            
            return "Delete Successfully";
        }
    
        return "Error";
    }
}
