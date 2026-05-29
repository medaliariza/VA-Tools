<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'file',
        'requested_by',
        'requested_for',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function requestedFor()
    {
        return $this->belongsTo(User::class, 'requested_for');
    }
}
