<?php

namespace App\Repositories\Contracts;

use App\Models\Transaction;

interface TransactionRepositoryInterface
{
    public function create(array $data): Transaction;
    public function paginateByUser(int $userId, int $perPage = 20);
    /**
     * Filter transactions for a user by optional date range and category.
     *
     * @param int $userId
     * @param string|null $startDate   format Y-m-d (inclusive)
     * @param string|null $endDate     format Y-m-d (inclusive)
     * @param int|null $categoryId
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function filterByUser(int $userId, ?string $startDate, ?string $endDate, ?int $categoryId, int $perPage = 20);
}
