<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\RankingRepositoryInterface;
use App\Models\Movement;
use App\Models\User;
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
        $movement = Movement::findOrFail($movementId);

        $personalRecords = \DB::table('personal_records as pr')
            ->select('pr.user_id', 'pr.movement_id', 'pr.value as max_value', 'pr.date as max_date')
            ->where('pr.movement_id', $movementId)
            ->whereRaw('pr.id = (
                SELECT pr2.id
                FROM personal_records pr2
                WHERE pr2.user_id = pr.user_id AND pr2.movement_id = pr.movement_id
                ORDER BY pr2.value DESC, pr2.date ASC
                LIMIT 1
            )')
            ->orderByDesc('max_value')
            ->orderBy('max_date')
            ->get();

        // Obter os usuários e associá-los aos registros
        $userIds = $personalRecords->pluck('user_id')->unique();
        $users = User::whereIn('id', $userIds)->get()->keyBy('id');

        // Associar os usuários aos registros
        foreach ($personalRecords as $record) {
            $record->user = $users->get($record->user_id);
        }

        $movement->setRelation('personalRecords', $personalRecords);

        return $movement;
    }
}
