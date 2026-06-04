<?php
namespace App\Models;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Task extends Model
{
    protected $fillable = [
        'title', 'description', 'priority', 'status',
        'due_date', 'assigned_to', 'ai_summary', 'ai_priority',
    ];

    protected $casts = [
        'priority'    => TaskPriority::class,
        'status'      => TaskStatus::class,
        'ai_priority' => TaskPriority::class,
        'due_date'    => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['status'] ?? null, fn($q, $v) => $q->where('status', $v))
            ->when($filters['priority'] ?? null, fn($q, $v) => $q->where('priority', $v))
            ->when($filters['assigned_to'] ?? null, fn($q, $v) => $q->where('assigned_to', $v))
            ->when($filters['search'] ?? null, fn($q, $v) => $q->where('title', 'like', "%$v%"));
    }
}