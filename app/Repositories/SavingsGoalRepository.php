<?php

namespace App\Repositories;

use App\Models\SavingsGoal;
use App\Repositories\Contracts\SavingsGoalRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SavingsGoalRepository implements SavingsGoalRepositoryInterface
{
    public function getAllByUser(int $userId): Collection
    {
        return SavingsGoal::where('user_id', $userId)
            ->latest()
            ->get();
    }

    public function create(array $data): SavingsGoal
    {
        return SavingsGoal::create($data);
    }

    public function findByUser(string $id, int $userId): SavingsGoal
    {
        return SavingsGoal::where('user_id', $userId)
            ->with('deposits')
            ->findOrFail($id);
    }

    public function update(SavingsGoal $savingsGoal, array $data): SavingsGoal
    {
        $savingsGoal->update($data);

        return $savingsGoal->refresh();
    }

    public function delete(SavingsGoal $savingsGoal): bool
    {
        return $savingsGoal->delete();
    }
}