<?php

namespace App\Services;

use App\Models\Transaction;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class TransactionService
{
    protected TransactionRepositoryInterface $transactionRepository;

    public function __construct(TransactionRepositoryInterface $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function createTransaction(array $data): Transaction
    {
        $data['user_id'] = Auth::guard('api')->id();
        return $this->transactionRepository->create($data);
    }

    public function getUserTransactions(int $userId, int $perPage = 20)
    {
        return $this->transactionRepository->paginateByUser($userId, $perPage);
    }

    public function getFilteredTransactions(
        int $userId,
        ?string $startDate,
        ?string $endDate,
        ?int $categoryId,
        int $perPage = 20
    ) {
        return $this->transactionRepository->filterByUser(
            $userId,
            $startDate,
            $endDate,
            $categoryId,
            $perPage
        );
    }
}
