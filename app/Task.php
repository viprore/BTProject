<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Task extends Model
{
    //

    protected $fillable = ['name', 'description', 'due_date', 'priority', 'status'];

    protected $dates = ['due_date'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function scopeDueInDays($query, $days)
    {
        $now = \Carbon\Carbon::now();
        return $query->where('due_date', '>', $now)
            ->where('due_date', '<', $now->copy()->addDays($days));

    }

    public function scopeDueDateBetween($query, Carbon $start_date, Carbon $end_date)
    {
        return $query->whereBetween('due_date', [
            $start_date->startOfDay(),
            $end_date->endOfDay()
        ]);
    }

    public function scopeOtherParam($query, Request $request)
    {
        $priority = $request->get('priority');
        if (!empty($priority) && $priority != 'all') {
            $query = $query->where('priority', $priority);
        }

        $status = $request->get('status');
        if (!empty($status) && $status != 'all') {
            $query = $query->where('status', $status);
        }

        return $query;
    }
}
