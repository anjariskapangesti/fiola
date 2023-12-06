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
        $devices = Device::orderBy('name', 'ASC')->get();
        
        return view('website.pages.device.create', compact('devices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required' ,            
        ]);
        
        try
        {
            Device::create([
                'name' => $request->name,            
                'cost' => $request->cost1 . ' - ' . $request->cost2,            
                'spesifikasi' => $request->spesifikasi,
            ]);
            return redirect('/device/show_data_device')->with('success', 'Success Add device');
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function show_data_device()
    {
        return view('website.pages.device.show_data_device');
    }

    public function show_data_device_ajax(Request $request)
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
            
            return "Device updated successfully";
        }

        return "Error";
    }

    public function destroy(Request $request)
    {
        $id = $request->id;

        $devices = Device::find($id);
        if (Auth::user()->can('can_master')) {
            $devices->delete();
            
            return "device deleted successfully";
        }

        return "Error";
    }
}
