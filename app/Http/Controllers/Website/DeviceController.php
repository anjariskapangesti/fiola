<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Device;
use Carbon\Carbon;
use DataTables;
use Auth;

class DeviceController extends Controller
{
    public function create()
    {
        return view('website.pages.device.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'cost' => 'required',
            'spesifikasi' => 'required',         
        ]);
        
        try
        {
            Device::create([
                'name' => $request->name,            
                'cost' => $request->cost,            
                'spesifikasi' => $request->spesifikasi,
            ]);
            return redirect('/device/list')->with('success', 'Create Successfully');
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function list()
    {
        return view('website.pages.device.list');
    }

    public function list_ajax(Request $request)
    {
        $data = Device::orderBy('name', 'ASC');
        
        return DataTables::eloquent($data)->make(true);
    }

    public function edit(Request $request)
    {
        $id = $request->id;

        $devices = Device::find($id);
        if (Auth::user()->can('can_master')) {
            $devices->update([
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

        $devices = Device::find($id);
        if (Auth::user()->can('can_master')) {
            $devices->delete();
            
            return "Delete Successfully";
        }

        return "Error";
    }
}
