<?php
namespace App\Repositories\Eloquent;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class TaskRepository implements TaskRepositoryInterface
{
    public function all(array $filters = []): LengthAwarePaginator
    {
        return Task::query()
            ->with('user')
            ->filter($filters)
            ->latest()
            ->paginate(10);
    }

    public function find(int $id): Task
    {
        return Task::with('user')->findOrFail($id);
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(int $id, array $data): Task
    {
        $task = $this->find($id);
        $task->update($data);
        Cache::forget("task_{$id}");
        return $task->fresh('user');
    }

    public function delete(int $id): bool
    {
        return $this->find($id)->delete();
    }

    public function forUser(int $userId, array $filters = []): LengthAwarePaginator
    {
        return Task::query()
            ->with('user')
            ->where('assigned_to', $userId)
            ->filter($filters)
            ->latest()
            ->paginate(10);
    }

    public function dashboardStats(): array
    {
        return Cache::remember('dashboard_stats', 60, fn() => [
            'total'     => Task::count(),
            'completed' => Task::where('status', 'completed')->count(),
            'pending'   => Task::where('status', 'pending')->count(),
            'high'      => Task::where('priority', 'high')->count(),
        ]);
    }
}