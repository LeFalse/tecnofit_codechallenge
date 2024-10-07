<?php

namespace App\Http\Controllers\Api;

use App\Domain\UseCases\GetMovementRanking;
use App\Http\Controllers\Controller;
use App\Http\Requests\RankingRequest;
use Illuminate\Http\JsonResponse;

class RankingController extends Controller
{
    public function __construct(private readonly GetMovementRanking $getMovementRanking)
    {
    }

    public function show(RankingRequest $request): JsonResponse
    {
        try {
            $movementId = $request->validated()['movementId'];
            $data = $this->getMovementRanking->execute($movementId);
            return response()->json($data);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => "Erro inesperado"], 500);
        }
    }
}
