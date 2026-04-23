<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

trait HasAjaxList
{
    protected function generateAjaxList($modelClass, $tableAlias, array $withRelations = [])
    {
        $user = Auth::user();
        $npk = $user->npk;

        $query = $modelClass::with($withRelations)
            ->join('users', "{$tableAlias}.created_by", '=', 'users.id')
            ->leftJoin('users as manager', "{$tableAlias}.manager_approve_by", '=', 'manager.id')
            ->leftJoin('users as it', "{$tableAlias}.it_approve_by", '=', 'it.id')
            ->leftJoin('users as it_mgr', "{$tableAlias}.it_mgr_approve_by", '=', 'it_mgr.id')
            ->leftJoin('users as on_progress', "{$tableAlias}.on_progress_by", '=', 'on_progress.id')
            ->leftJoin('users as finish', "{$tableAlias}.finish_by", '=', 'finish.id')
            ->select([
                "{$tableAlias}.*",
                'users.name as requestor',
                'manager.name as manager_name',
                'it.name as it_name',
                'it_mgr.name as it_mgr_name',
                'on_progress.name as on_progress_name',
                'finish.name as finish_name',
            ])
            ->orderBy("{$tableAlias}.created_at", 'DESC');

        if (!in_array($npk, ['002327', '002372'])) {
            $query->where("{$tableAlias}.created_by", $user->id);
        }

        return DataTables::eloquent($query)->make(true);
    }
}
