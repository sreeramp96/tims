<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimesheetEntry extends Model
{
    protected $fillable = [
        'user_id',
        'project_id',
        'issue',
        'comment',
        'duration',
        'date',
    ];

    protected $table = 'time_sheet_entries';

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
