<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Repositories\Contracts\TransactionRepositoryInterface;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function create(array $data): Transaction
    {
        return Transaction::create($data);
    }

    public function paginateByUser(int $userId, int $perPage = 20)
    {
        return Transaction::where('user_id', $userId)
            ->orderByDesc('transaction_date')
            ->paginate($perPage);
    }

    public function filterByUser(int $userId, ?string $startDate, ?string $endDate, ?int $categoryId, int $perPage = 20)
    {
        $query = Transaction::where('user_id', $userId);
        if ($startDate) {
            $query->whereDate('transaction_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('transaction_date', '<=', $endDate);
        }
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        return $query->orderByDesc('transaction_date')->paginate($perPage);
    }
}