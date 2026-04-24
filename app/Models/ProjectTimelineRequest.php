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
    ];

    protected $dates = [
        'responded_at',
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
}