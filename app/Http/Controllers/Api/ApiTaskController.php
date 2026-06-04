<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Services\TaskService;
use Illuminate\Http\Request;

class ApiTaskController extends Controller
{
    public function __construct(
        private TaskRepositoryInterface $repo,
        private TaskService $service,
    ) {}

    public function index(Request $request)
    {
        $tasks = $this->repo->all($request->only('status', 'priority', 'search'));
        return TaskResource::collection($tasks);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority'    => 'required|in:low,medium,high',
            'status'      => 'required|in:pending,in_progress,completed',
            'due_date'    => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        return new TaskResource($this->service->store($data));
    }

    public function updateStatus(Request $request, int $id)
    {
        $data = $request->validate(['status' => 'required|in:pending,in_progress,completed']);
        $task = $this->repo->update($id, $data);
        return new TaskResource($task);
    }

    public function aiSummary(int $id)
    {
        $task = $this->service->refreshAISummary($id);
        return new TaskResource($task);
    }
}