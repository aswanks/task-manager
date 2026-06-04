<?php
namespace App\Services;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Support\Facades\DB;

class TaskService
{
    public function __construct(
        private TaskRepositoryInterface $repo,
        private AIService $aiService,
    ) {}

    public function store(array $data): Task
    {
        return DB::transaction(function () use ($data) {
            $task  = $this->repo->create($data);
            $aiData = $this->aiService->generateSummary($task);
            return $this->repo->update($task->id, $aiData);
        });
    }

    public function update(int $id, array $data): Task
    {
        return DB::transaction(function () use ($id, $data) {
            $task   = $this->repo->update($id, $data);
            $aiData = $this->aiService->generateSummary($task);
            return $this->repo->update($task->id, $aiData);
        });
    }

    public function delete(int $id): bool
    {
        return $this->repo->delete($id);
    }

    public function refreshAISummary(int $id): Task
    {
        $task   = $this->repo->find($id);
        $aiData = $this->aiService->generateSummary($task);
        return $this->repo->update($id, $aiData);
    }
}