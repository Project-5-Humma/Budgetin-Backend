<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * @OA\Get(
     *     path="/api/transactions",
     *     operationId="listTransactions",
     *     tags={"Transactions"},
     *     summary="Riwayat transaksi dengan filter",
     *     description="Mengembalikan daftar transaksi milik user yang ter‑autentikasi. Dapat difilter berdasarkan rentang tanggal (start_date, end_date) dan kategori (category_id).",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Jumlah item per halaman (default 20)",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, default=20)
     *     ),
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Tanggal mulai (format YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="Tanggal akhir (format YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="ID kategori transaksi",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Daftar transaksi",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Transaction")),
     *             @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"),
     *             @OA\Property(property="message", type="string", example="Riwayat transaksi berhasil diambil.")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Tidak ter‑autentikasi")
     * )
     */

    
    /**
     * @OA\Post(
     *     path="/api/transactions",
     *     operationId="storeTransaction",
     *     tags={"Transactions"},
     *     summary="Simpan transaksi baru",
     *     description="Menyimpan transaksi baru untuk user yang ter‑autentikasi. wallet_id di‑set secara otomatis jika tidak diberikan.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreTransactionRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Transaksi berhasil disimpan",
     *         @OA\JsonContent(ref="#/components/schemas/Transaction")
     *     ),
     *     @OA\Response(response=401, description="Tidak ter‑autentikasi"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function store(StoreTransactionRequest $request)
    {
        $transaction = $this->transactionService->createTransaction(
            $request->validated()
        );

        return (new TransactionResource($transaction))
            ->additional([
                'message' => 'Transaksi berhasil disimpan.',
            ])
            ->response()
            ->setStatusCode(201);
    }

    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $categoryId = $request->query('category_id');

        $userId = auth()->guard('api')->id();

        if ($startDate || $endDate || $categoryId) {
            $transactions = $this->transactionService->getFilteredTransactions(
                $userId,
                $startDate,
                $endDate,
                $categoryId ? (int) $categoryId : null,
                (int) $perPage
            );
        } else {
            $transactions = $this->transactionService->getUserTransactions(
                $userId,
                (int) $perPage
            );
        }

        return TransactionResource::collection($transactions)
            ->additional(['message' => 'Riwayat transaksi berhasil diambil.'])
            ->response()
            ->setStatusCode(200);
    }

}
