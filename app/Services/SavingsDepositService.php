<?php

namespace App\Services;

use App\Models\SavingsGoal;
use App\Models\SavingsDeposit;
use App\Repositories\Contracts\SavingsDepositRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SavingsDepositService
{
    protected SavingsDepositRepositoryInterface $savingsDepositRepository;

    public function __construct(
        SavingsDepositRepositoryInterface $savingsDepositRepository
    ) {
        $this->savingsDepositRepository = $savingsDepositRepository;
    }

    /**
     * Mengambil seluruh deposit berdasarkan target tabungan.
     */
    public function getAllBySavingsGoal(string $savingsGoalId): Collection
    {
        $userId = (int) Auth::guard('api')->id();

        $savingsGoal = $this->savingsDepositRepository
            ->findSavingsGoalForUpdate($savingsGoalId, $userId);

        return $this->savingsDepositRepository
            ->getAllBySavingsGoal($savingsGoal);
    }

    /**
     * Membuat deposit tabungan.
     */
    public function create(
        string $savingsGoalId,
        array $data
    ): SavingsDeposit {
        $userId = (int) Auth::guard('api')->id();

        return DB::transaction(function () use (
            $savingsGoalId,
            $data,
            $userId
        ) {
            // Mengambil dan mengunci target tabungan.
            $savingsGoal = $this->savingsDepositRepository
                ->findSavingsGoalForUpdate(
                    $savingsGoalId,
                    $userId
                );

            // Memastikan target masih aktif.
            if ($savingsGoal->status === 'completed') {
                throw ValidationException::withMessages([
                    'savings_goal' => 'Target tabungan sudah selesai.',
                ]);
            }

            // Mengambil informasi wallet.
            $walletId = (string) ($data['wallet_id'] ?? '');

            if ($walletId === '') {
                throw ValidationException::withMessages([
                    'wallet_id' => 'Wallet wajib dipilih.',
                ]);
            }

            $wallet = $this->savingsDepositRepository
                ->findWalletForUpdate(
                    $walletId,
                    $userId
                );

            // Memastikan nominal deposit valid.
            $amount = (float) ($data['amount'] ?? 0);

            if ($amount <= 0) {
                throw ValidationException::withMessages([
                    'amount' => 'Nominal deposit harus lebih dari 0.',
                ]);
            }

            // Memastikan saldo wallet mencukupi.
            if ((float) $wallet->balance < $amount) {
                throw ValidationException::withMessages([
                    'amount' => 'Saldo wallet tidak mencukupi.',
                ]);
            }

            // Memastikan deposit tidak melebihi target.
            $remainingAmount =
                (float) $savingsGoal->target_amount
                - (float) $savingsGoal->current_amount;

            if ($amount > $remainingAmount) {
                throw ValidationException::withMessages([
                    'amount' => 'Nominal deposit melebihi sisa target tabungan.',
                ]);
            }

            // Menyiapkan data deposit.
            unset($data['wallet_id']);

            $data['amount'] = $amount;

            // Membuat data deposit.
            $deposit = $this->savingsDepositRepository
                ->create($savingsGoal, $data);

            // Mengurangi saldo wallet.
            $this->savingsDepositRepository
                ->deductWalletBalance($wallet, $amount);

            // Menambahkan nominal tabungan.
            $updatedSavingsGoal = $this->savingsDepositRepository
                ->increaseSavingsGoalAmount(
                    $savingsGoal,
                    $amount
                );

            // Menyelesaikan target jika sudah tercapai.
            if (
                (float) $updatedSavingsGoal->current_amount
                >= (float) $updatedSavingsGoal->target_amount
            ) {
                $this->savingsDepositRepository
                    ->completeSavingsGoal($updatedSavingsGoal);
            }

            return $deposit;
        });
    }
}
