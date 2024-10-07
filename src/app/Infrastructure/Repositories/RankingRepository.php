<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\RankingRepositoryInterface;
use App\Models\Movement;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RankingRepository implements RankingRepositoryInterface
{
    /**
     * Obtém o ranking para uma movimentação específica pelo seu ID.
     *
     * @param int $movementId
     * @return Movement
     *
     * @throws ModelNotFoundException
     */
    public function getRankingByMovement(int $movementId): Movement
    {
        return Movement::with(['personalRecords' => function($query) {
            $query->select('user_id', 'movement_id', \DB::raw('MAX(value) as max_value'), \DB::raw('MAX(date) as max_date'))
                ->groupBy('user_id', 'movement_id')
                ->orderByDesc('max_value');
        }, 'personalRecords.user'])
        ->findOrFail($movementId);
    }
}
