<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Todo extends Model
{

    protected $fillable = [
        'task_name',
        'due_by',
        'task_priority',
        'task_status',
        'todo_list_id',
        'user_id',
    ];

    protected $casts = [
        'task_status' => 'boolean',
    ];

    public function todolist(): BelongsTo
    {
        return $this->belongsTo(TodoList::class);
    }
}
