<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectTimelineRequest extends Model
{
    protected $table = 'project_timeline_requests';

    protected $fillable = [
        'request_project_id',
        'replace_project_id',
        'requested_by',
        'requested_to',
        'status',
        'message',
        'responded_at',

        'owner_approved_by',
        'owner_approved_at',
        'manager_approved_by',
        'manager_approved_at',
        'director_approved_by',
        'director_approved_at',
    ];

    protected $dates = [
        'responded_at',
        'owner_approved_at',
        'manager_approved_at',
        'director_approved_at',
    ];

    public function requestProject()
    {
        return $this->belongsTo(Project::class, 'request_project_id');
    }

    public function replaceProject()
    {
        return $this->belongsTo(Project::class, 'replace_project_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'requested_to');
    }

    public function ownerApprover()
    {
        return $this->belongsTo(User::class, 'owner_approved_by');
    }

    public function managerApprover()
    {
        return $this->belongsTo(User::class, 'manager_approved_by');
    }

    public function directorApprover()
    {
        return $this->belongsTo(User::class, 'director_approved_by');
    }
}