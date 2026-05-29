<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $fillable = [
        'user_id',
        'assigned_by',
        'task',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
