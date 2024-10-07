<?php

namespace Tests\Unit\UseCases;

use App\Models\Movement;
use Tests\TestCase;
use App\Domain\UseCases\GetMovementRanking;
use App\Domain\Repositories\RankingRepositoryInterface;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;

class GetMovementRankingTest extends TestCase
{
    private RankingRepositoryInterface $rankingRepositoryMock;
    private GetMovementRanking $getMovementRanking;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rankingRepositoryMock = Mockery::mock(RankingRepositoryInterface::class);
        $this->getMovementRanking = new GetMovementRanking($this->rankingRepositoryMock);
    }

    #[DataProvider('rankingDataProvider')]
    public function test_processRanking_returns_correct_ranking(array $personalRecords, array $expectedRanking)
    {
        // Usar uma instância real de Movement
        $movement = new Movement();
        $movement->name = 'Deadlift';
        $movement->setRelation('personalRecords', collect($personalRecords));

        // Configurar o repositório para retornar o Movement com personalRecords simulados
        $this->rankingRepositoryMock->shouldReceive('getRankingByMovement')->andReturn($movement);

        // Executar o método de processamento
        $returnProcessRanking = $this->getMovementRanking->processRanking($movement);

        // Verificar o resultado
        $this->assertEquals($expectedRanking, $returnProcessRanking['ranking']);
    }

    public function test_execute_throws_exception_when_movement_not_found()
    {
        $movementId = 9999;

        $this->rankingRepositoryMock->shouldReceive('getRankingByMovement')
            ->with($movementId)
            ->andThrow(new \InvalidArgumentException('Movement not found'));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Movement not found');

        $this->getMovementRanking->execute($movementId);
    }

    public static function rankingDataProvider(): array
    {
        return [
            'unique values' => [
                [
                    (object)[
                        'user' => (object)['name' => 'Joao'],
                        'max_value' => 180.0,
                        'max_date' => '2021-01-03 00:00:00',
                    ],
                    (object)[
                        'user' => (object)['name' => 'Jose'],
                        'max_value' => 190.0,
                        'max_date' => '2021-01-06 00:00:00',
                    ],
                    (object)[
                        'user' => (object)['name' => 'Paulo'],
                        'max_value' => 170.0,
                        'max_date' => '2021-01-01 00:00:00',
                    ],
                ],
                [
                    [
                        'position' => 1,
                        'user' => 'Jose',
                        'record' => 190.0,
                        'date' => '2021-01-06 00:00:00',
                    ],
                    [
                        'position' => 2,
                        'user' => 'Joao',
                        'record' => 180.0,
                        'date' => '2021-01-03 00:00:00',
                    ],
                    [
                        'position' => 3,
                        'user' => 'Paulo',
                        'record' => 170.0,
                        'date' => '2021-01-01 00:00:00',
                    ],
                ],
            ],
            'tie values' => [
                [
                    (object)[
                        'user' => (object)['name' => 'Joao'],
                        'max_value' => 180.0,
                        'max_date' => '2021-01-03 00:00:00',
                    ],
                    (object)[
                        'user' => (object)['name' => 'Jose'],
                        'max_value' => 180.0,
                        'max_date' => '2021-01-06 00:00:00',
                    ],
                    (object)[
                        'user' => (object)['name' => 'Paulo'],
                        'max_value' => 150.0,
                        'max_date' => '2021-01-09 00:00:00',
                    ],
                    (object)[
                        'user' => (object)['name' => 'Pedro'],
                        'max_value' => 160.0,
                        'max_date' => '2021-01-12 00:00:00',
                    ],
                    (object)[
                        'user' => (object)['name' => 'Henrique'],
                        'max_value' => 160.0,
                        'max_date' => '2021-01-15 00:00:00',
                    ],
                ],
                [
                    [
                        'position' => 1,
                        'user' => 'Joao',
                        'record' => 180.0,
                        'date' => '2021-01-03 00:00:00',
                    ],
                    [
                        'position' => 1,
                        'user' => 'Jose',
                        'record' => 180.0,
                        'date' => '2021-01-06 00:00:00',
                    ],
                    [
                        'position' => 2,
                        'user' => 'Pedro',
                        'record' => 160.0,
                        'date' => '2021-01-12 00:00:00',
                    ],
                    [
                        'position' => 2,
                        'user' => 'Henrique',
                        'record' => 160.0,
                        'date' => '2021-01-15 00:00:00',
                    ],
                    [
                        'position' => 3,
                        'user' => 'Paulo',
                        'record' => 150.0,
                        'date' => '2021-01-09 00:00:00',
                    ],
                ],
            ],
        ];
    }
}
