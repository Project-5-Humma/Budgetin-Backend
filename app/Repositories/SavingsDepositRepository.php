<?php

namespace App\Repositories;

use App\Models\Wallet;
use App\Models\SavingsGoal;
use App\Models\SavingsDeposit;
use App\Repositories\Contracts\SavingsDepositRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SavingsDepositRepository implements SavingsDepositRepositoryInterface
{
    public function getAllBySavingsGoal(SavingsGoal $savingsGoal): Collection
    {
        return $savingsGoal->deposits()
            ->latest('deposit_date')
            ->get();
    }

    public function findSavingsGoalForUpdate(
        string $id,
        int $userId
    ): SavingsGoal {
        return SavingsGoal::where('user_id', $userId)
            ->lockForUpdate()
            ->findOrFail($id);
    }

    public function findWalletForUpdate(
        string $walletId,
        int $userId
    ): Wallet {
        return Wallet::where('user_id', $userId)
            ->lockForUpdate()
            ->findOrFail($walletId);
    }

    public function create(
        SavingsGoal $savingsGoal,
        array $data
    ): SavingsDeposit {
        return $savingsGoal->deposits()->create($data);
    }
    public function deductWalletBalance(
    Wallet $wallet,
    float $amount
): void {
    $wallet->decrement('balance', $amount);
}

public function increaseSavingsGoalAmount(
    SavingsGoal $savingsGoal,
    float $amount
): SavingsGoal {
    $savingsGoal->increment('current_amount', $amount);

    return $savingsGoal->refresh();
}

public function completeSavingsGoal(
    SavingsGoal $savingsGoal
): SavingsGoal {
    $savingsGoal->update([
        'status' => 'completed',
    ]);

    return $savingsGoal->refresh();
}
}