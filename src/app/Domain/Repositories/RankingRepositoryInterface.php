<?php

namespace App\Domain\Repositories;

use App\Models\Movement;
use Illuminate\Database\Eloquent\ModelNotFoundException;

interface RankingRepositoryInterface
{
    /**
     * Obtém o ranking para uma movimentação específica pelo seu ID.
     *
     * @param int $movementId
     * @return Movement
     *
     * @throws ModelNotFoundException
     */
    public function getRankingByMovement(int $movementId): Movement;
}
