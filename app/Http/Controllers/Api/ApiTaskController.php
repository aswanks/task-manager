<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\TaskService;
use Illuminate\Http\Request;

class ApiTaskController extends Controller
{
    public function __construct(
        private TaskRepositoryInterface $repo,
        private UserRepositoryInterface $userRepo,
        private TaskService $service,
    ) {}

    public function index(Request $request)
    {
        $tasks = $this->repo->all(
            $request->only('status', 'priority', 'search')
        );

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

        $task = $this->service->store($data);

        return new TaskResource($task);
    }

    public function show(int $id)
    {
        $task = $this->repo->find($id);

        return new TaskResource($task);
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'title'       => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'priority'    => 'sometimes|in:low,medium,high',
            'status'      => 'sometimes|in:pending,in_progress,completed',
            'due_date'    => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $task = $this->service->update($id, $data);

        return new TaskResource($task);
    }

    public function updateStatus(Request $request, int $id)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task = $this->repo->update($id, $data);

        return new TaskResource($task);
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully.',
        ]);
    }

    public function aiSummary(int $id)
    {
        $task = $this->service->refreshAISummary($id);

        return new TaskResource($task);
    }

    public function users()
    {
        return response()->json(
            $this->userRepo->allForDropdown()
        );
    }
}