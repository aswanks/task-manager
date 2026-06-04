<?php
namespace App\Repositories\Contracts;

use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;

interface TaskRepositoryInterface
{
    public function all(array $filters = []): LengthAwarePaginator;
    public function find(int $id): Task;
    public function create(array $data): Task;
    public function update(int $id, array $data): Task;
    public function delete(int $id): bool;
    public function forUser(int $userId, array $filters = []): LengthAwarePaginator;
    public function dashboardStats(): array;
}