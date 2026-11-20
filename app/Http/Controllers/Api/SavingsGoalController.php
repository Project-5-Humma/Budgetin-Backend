<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSavingsGoalRequest;
use App\Http\Resources\SavingsGoalResource;
use App\Services\SavingsGoalService;

class SavingsGoalController extends Controller
{
    protected SavingsGoalService $savingsGoalService;

    public function __construct(SavingsGoalService $savingsGoalService)
    {
        $this->savingsGoalService = $savingsGoalService;
    }

    public function index()
    {
        $savingsGoals = $this->savingsGoalService->getAll();

        return SavingsGoalResource::collection($savingsGoals)
            ->additional([
                'message' => 'Data tabungan berhasil diambil.',
            ]);
    }

    public function store(StoreSavingsGoalRequest $request)
    {
        $savingsGoal = $this->savingsGoalService->create(
            $request->validated()
        );

        return (new SavingsGoalResource($savingsGoal))
            ->additional([
                'message' => 'Tabungan berhasil dibuat.',
            ])
            ->response()
            ->setStatusCode(201);
    }

    public function show(string $id)
    {
        $savingsGoal = $this->savingsGoalService->getById($id);

        return (new SavingsGoalResource($savingsGoal))
            ->additional([
                'message' => 'Detail tabungan berhasil diambil.',
            ]);
    }

    public function update(StoreSavingsGoalRequest $request, string $id)
    {
        $savingsGoal = $this->savingsGoalService->update(
            $id,
            $request->validated()
        );

        return (new SavingsGoalResource($savingsGoal))
            ->additional([
                'message' => 'Tabungan berhasil diperbarui.',
            ]);
    }

    public function destroy(string $id)
    {
        $this->savingsGoalService->delete($id);

        return response()->noContent();
    }
}