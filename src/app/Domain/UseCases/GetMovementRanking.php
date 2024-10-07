<?php

namespace App\Domain\UseCases;

use App\Domain\Repositories\RankingRepositoryInterface;
use App\Models\Movement;
use Illuminate\Database\Eloquent\ModelNotFoundException;

readonly class GetMovementRanking
{
    public function __construct(private RankingRepositoryInterface $rankingRepository)
    {
    }

    public function execute(int $movementId): array
    {
        try {
            $movement = $this->rankingRepository->getRankingByMovement($movementId);
        } catch (ModelNotFoundException $e) {
            throw new \InvalidArgumentException('Movement not found');
        }

        return $this->processRanking($movement);
    }

    /**
     * Processa o ranking de um movimento específico utilizando o método de ranking denso "1223".
     *
     * @param Movement $movement O movimento que contém os recordes pessoais.
     * @return array Um array contendo o nome do movimento e seu ranking processado.
     */
    public function processRanking(Movement $movement): array
    {
        $ranking = collect($movement->personalRecords)
            ->groupBy('max_value')
            ->sortKeysDesc()
            ->values()
            ->flatMap(function ($recordsGroup, $index) {
                $position = $index + 1;
                return $recordsGroup->map(function ($record) use ($position) {
                    return [
                        'position' => $position,
                        'user' => $record->user->name,
                        'record' => $record->max_value,
                        'date' => $record->max_date
                    ];
                });
            })
            ->values()
            ->toArray();

        return [
            'movement' => $movement->name,
            'ranking' => $ranking
        ];
    }
}
