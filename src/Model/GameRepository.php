<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\Exception\NotFoundException;
use Assert\AssertionFailedException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DBALException;

final class GameRepository
{
    private const TABLE_NAME = 'games';

    public function __construct(private Connection $connection)
    {
    }

    /**
     * @throws DBALException
     */
    public function add(Game $game): void
    {
        $data = $game->state();
        $this->connection->insert(self::TABLE_NAME, $data);
    }

    /**
     * @throws DBALException
     */
    public function update(Game $game): void
    {
        $data = $game->state();
        $this->connection->update(
            self::TABLE_NAME,
            $data,
            [
                'game_id' => $data['game_id']
            ]
        );
    }

    /**
     * @return Game[]
     * @throws AssertionFailedException
     * @throws DBALException
     */
    public function fetch(): array
    {
        $sql = 'SELECT * from ' . self::TABLE_NAME . ' ORDER BY created';
        /** @var array<int,array<string, string>> $rows */
        $rows = $this->connection->fetchAllAssociative($sql);

        $results = array_map(static function ($row) {
            return Game::fromState($row);
        }, $rows);

        return $results;
    }

    /**
     * @throws DBALException
     * @throws AssertionFailedException
     */
    public function loadById(string $id): Game
    {
        $gameId = GameId::fromString($id);

        $sql = 'SELECT * from ' . self::TABLE_NAME . ' WHERE game_id = :id';
        /** @var false|array<string, string> $row */
        $row = $this->connection->fetchAssociative($sql, ['id' => $gameId->toString()]);

        if ($row === false) {
            throw new NotFoundException("Game not found");
        }

        return Game::fromState($row);
    }
}
