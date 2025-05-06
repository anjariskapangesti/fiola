<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Support;
use App\Models\User;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;
use Auth;

class SupportController extends Controller
{
    public function create()
    {
        $users = User::whereHas('permissions', function ($query) {
            $query->where('permissions.name', 'apps_fiola');
        })->select('users.*', DB::raw('STRING_AGG(departments.name, \', \') as department_names'))
            ->join('public.model_has_departments', 'public.users.id', 'public.model_has_departments.model_id')
            ->join('public.departments', 'public.model_has_departments.department_id', 'departments.id')
            ->groupBy('users.id')
            ->orderBy('users.name', 'ASC')
            ->get();

        return view('website.pages.support.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        
        try
        {
            Support::create([
                'shift' => $request->shift,
                'name' => $request->name,
                'email' => $request->email,
                'nohp' => $request->nohp,
            ]);
            return redirect('/support/list')->with('success', 'Create Successfully');
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function edit($id)
    {
        $support = Support::findOrFail($id);
        $users = User::whereHas('permissions', function ($query) {
            $query->where('permissions.name', 'apps_fiola');
        })->select('users.*', DB::raw('STRING_AGG(departments.name, \', \') as department_names'))
            ->join('public.model_has_departments', 'public.users.id', 'public.model_has_departments.model_id')
            ->join('public.departments', 'public.model_has_departments.department_id', 'departments.id')
            ->groupBy('users.id')
            ->orderBy('users.name', 'ASC')
            ->get();

        return view('website.pages.support.edit', compact('support', 'users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);
    
        try {
            $support = Support::findOrFail($id);
    
            $support->update([
                'shift' => $request->shift,
                'name' => $request->name,
                'email' => $request->email,
                'nohp' => $request->nohp,
            ]);
    
            return redirect('/support/list')->with('success', 'Update Successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function list()
    {
        return view('website.pages.support.list');
    }

    public function list_ajax(Request $request)
    {
        $data = Support::select('supports.*')
                        ->orderBy('shift', 'ASC');

        return DataTables::eloquent($data)->make(true);
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $support = Support::find($id);
        if (Auth::user()->can('apps_fiola')) {
            $support->delete();
            
            return 'Delete Successfully';
        } else {
            return response()->json(['error' => 'You are not authorized to delete this item.'], 403);
        }
    }

    public function status(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $support = Support::find($id);

        if ($type == 'active') {
            $support->update([
                'status' => 'active',
            ]);
            
            return 'Update Successfully';
        } else if ($type == 'not_active') {
            $support->update([
                'status' => 'not active',
            ]);
            
            return 'Update Successfully';
        } else if ($type == 'cuti') {
            $support->update([
                'status' => 'cuti',
            ]);
            
            return 'Update Successfully';
        } else {
            return response()->json(['error' => 'You are not authorized to delete this item.'], 403);
        }
    }

    public function update_status()
    {
        $now = Carbon::now();
        if ($now->isWeekday() && $now->hour == 7) {
            if ($now->dayOfWeek == Carbon::MONDAY) {
                Support::where('shift', 'Shift 2')->where('status', 'not active')->update(['status' => 'active', 'shift' => 'Non Shift']);
                Support::where('shift', 'Shift 3')->where('status', 'not active')->update(['shift' => 'Shift 2']);
                Support::where('shift', 'Non Shift')->where('status', 'not active')->update(['shift' => 'Shift 3']);
            } else {
                Support::where('shift', 'Non Shift')->where('status', 'not active')->update(['status' => 'active']);
            }
            return response()->json(['success', 'Update Successfully']);
        } else if ($now->isWeekday() && $now->hour == 14) {
            Support::where('shift', 'Shift 2')->where('status', 'not active')->update(['status' => 'active']);
            return response()->json(['success', 'Update Successfully']);
        } else if ($now->isWeekday() && $now->hour == 16) {
            Support::where('shift', 'Non Shift')->where('status', 'active')->update(['status' => 'not active']);
            return response()->json(['success', 'Update Successfully']);
        } else if ($now->isWeekday() && $now->hour == 22) {
            Support::where('shift', 'Shift 2')->where('status', 'active')->update(['status' => 'not active']);
            Support::where('shift', 'Shift 3')->where('status', 'not active')->update(['status' => 'active']);
            return response()->json(['success', 'Update Successfully']);
        } else if (in_array($now->dayOfWeek, [Carbon::TUESDAY, Carbon::WEDNESDAY, Carbon::THURSDAY, Carbon::FRIDAY, Carbon::SATURDAY]) && $now->hour == 6) {
            Support::where('shift', 'Shift 3')->where('status', 'active')->update(['status' => 'not active']);
            return response()->json(['success', 'Update Successfully']);
        } else {
            return response()->json(['error', 'Nothing to do.']);
        }
    }
}
