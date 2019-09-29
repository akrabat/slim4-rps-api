<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\Exception\NotFoundException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;

final class GameRepository
{
    private $tableName = 'games';

    /** @var Connection  */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws DBALException
     */
    public function add(Game $game)
    {
        $data = $game->state();
        $this->connection->insert($this->tableName, $data);
    }

    /**
     * @return Game[]
     */
    public function fetch(): array
    {
        $sql = 'SELECT * from ' . $this->tableName . ' ORDER BY created';
        $rows = $this->connection->fetchAll($sql);

        $results = array_map(function ($row) {
            return Game::fromState($row);
        }, $rows);

        return $results;
    }

    public function loadById($id): Game
    {
        $gameId = GameId::fromString($id);

        $sql = 'SELECT * from ' . $this->tableName . ' WHERE game_id = :id';
        $row = $this->connection->fetchAssoc($sql, ['id' => $gameId->toString()]);

        if ($row === false) {
            throw new NotFoundException("Game not found");
        }

        return Game::fromState($row);
    }
}
