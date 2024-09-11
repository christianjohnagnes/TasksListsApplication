<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'priority',
        'time_started',
        'time_ended',
        'due_date'
    ];

    public function scopePreventActionIfStatusInOrComplete($query, $id)
    {
        return $query->where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->whereIn('status', ['CP', 'INC']);
    }
}
