<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\TaskService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use AuthorizesRequests;  // ← add this

    public function __construct(
        private TaskRepositoryInterface $repo,
        private UserRepositoryInterface $userRepo,
        private TaskService $service,
    ) {}

    public function index(Request $request)
    {
        $tasks = auth()->user()->role === 'admin'
            ? $this->repo->all($request->only('status', 'priority', 'search'))
            : $this->repo->forUser(auth()->id(), $request->only('status', 'priority', 'search'));

        $stats = $this->repo->dashboardStats();
        return view('tasks.index', compact('tasks', 'stats'));
    }

    public function create()
    {
        $this->authorize('create', \App\Models\Task::class);
        $users = $this->userRepo->allForDropdown();
        return view('tasks.create', compact('users'));
    }

    public function store(StoreTaskRequest $request)
    {
        $this->authorize('create', \App\Models\Task::class);
        $this->service->store($request->validated());
        return redirect()->route('tasks.index')->with('success', 'Task created.');
    }

    public function show(int $id)
    {
        $task = $this->repo->find($id);
        $this->authorize('view', $task);
        $users = $this->userRepo->allForDropdown();
        $stats = $this->repo->dashboardStats();
        return view('tasks.show', compact('task','users','stats'));
    }

    public function edit(int $id)
    {
        $task = $this->repo->find($id);
        $this->authorize('update', $task);
        $users = $this->userRepo->allForDropdown();
        $stats = $this->repo->dashboardStats();
        return view('tasks.edit', compact('task', 'users','stats'));
    }

    public function update(UpdateTaskRequest $request, int $id)
    {
        $task = $this->repo->find($id);
        $this->authorize('update', $task);
        $this->service->update($id, $request->validated());
        return redirect()->route('tasks.show', $id)->with('success', 'Task updated.');
    }

    public function destroy(int $id)
    {
        $task = $this->repo->find($id);
        $this->authorize('delete', $task);
        $this->service->delete($id);
        return redirect()->route('tasks.index')->with('success', 'Task deleted.');
    }

    public function aiRefresh(int $task)
    {
        $task = $this->service->refreshAISummary($task);
        return redirect()->route('tasks.show', $task)->with('success', 'AI summary refreshed.');
    }
}