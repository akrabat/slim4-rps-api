<?php

declare(strict_types=1);

namespace App\Model;

use Assert\Assertion;
use Assert\AssertionFailedException;
use DateTimeImmutable;
use RuntimeException;

final class Game implements Entity
{
    /** @var GameId */
    private $gameId;

    /** @var string */
    private $player1;

    /** @var string */
    private $player2;

    /** @var GameMove */
    private $player1Move;

    /** @var GameMove */
    private $player2Move;

    /** @var GameStatus */
    private $status;

    /** @var DateTimeImmutable */
    private $created;

    /**
     * @throws \Assert\AssertionFailedException
     * @throws \Exception
     */
    private function __construct()
    {
        $this->created = new DateTimeImmutable('now', new \DateTimeZone('UTC'));
        $this->player1Move = new GameMove(GameMove::NOT_PLAYED);
        $this->player2Move = new GameMove(GameMove::NOT_PLAYED);
        $this->status = new GameStatus(GameStatus::CREATED);
    }

    public static function newGame(GameId $gameId, array $data): Game
    {
        self::validateNewGameData($data);

        $game = new Game();
        $game->gameId = $gameId;
        $game->player1 = $data['player1'];
        $game->player2 = $data['player2'];
        return $game;
    }

    public function getGameId(): GameId
    {
        return $this->gameId;
    }

    public function getPlayer1(): string
    {
        return $this->player1;
    }

    public function getPlayer2(): string
    {
        return $this->player2;
    }

    public function getPlayer1Move(): GameMove
    {
        return $this->player1Move;
    }

    public function getPlayer2Move(): GameMove
    {
        return $this->player2Move;
    }

    public function getStatus(): GameStatus
    {
        return $this->status;
    }

    public function getCreated(): DateTimeImmutable
    {
        return $this->created;
    }

    public function result(): MatchResult
    {
        if (! $this->status->is(GameStatus::COMPLETE)) {
            throw new RuntimeException('Game not complete');
        }

        return $this->playMatch($this->player1Move, $this->player2Move);
    }

    /*
     * Play the match
     */
    public function playMatch(GameMove $move1, GameMove $move2): MatchResult
    {
        $moves = [
            GameMove::ROCK => 1,
            GameMove::PAPER => 2,
            GameMove::SCISSORS => 3,
        ];

        // convert moves to integers, so we can do maths to get the result
        $player1 = $moves[$move1->toString()];
        $player2 = $moves[$move2->toString()];

        $result = ($player1 - $player2 + 3) % 3;

        return new MatchResult($result);
    }

    /**
     * Make a move
     */
    public function makeMove(array $data)
    {
        $this->validateMakeMoveData($data);

        $move = new GameMove($data['move']);
        $player = $data['player'];

        if ($player === $this->player1) {
            if ($this->player1Move->toString() !== GameMove::NOT_PLAYED) {
                throw ValidationException::withMessages(['player' => 'player 1 has already made their move']);
            }
            $this->player1Move = $move;
        } else {
            if ($this->player2Move->toString() !== GameMove::NOT_PLAYED) {
                throw ValidationException::withMessages(['player' => 'player 2 has already made their move']);
            }
            $this->player2Move = $move;
        }
        $this->status = new GameStatus(GameStatus::IN_PROGRESS);

        if (
            $this->player1Move->toString() !== GameMove::NOT_PLAYED
            && $this->player2Move->toString() !== GameMove::NOT_PLAYED
        ) {
            $this->status = new GameStatus(GameStatus::COMPLETE);
        }
    }

    /**
     * Return an array representing the state of this entity. The keys match the database columns.
     */
    public function state(): array
    {
        return [
            'game_id' => $this->gameId->toString(),
            'player1' => $this->player1,
            'player2' => $this->player2,
            'player1_move' => $this->player1Move->toString(),
            'player2_move' => $this->player2Move->toString(),
            'status' => $this->status->toString(),
            'created' => $this->created->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Create a Game from a database row
     * @throws \Assert\AssertionFailedException
     */
    public static function fromState(array $state): Game
    {
        Assertion::keyExists($state, 'game_id');
        Assertion::keyExists($state, 'player1');
        Assertion::keyExists($state, 'player2');
        Assertion::keyExists($state, 'player1_move');
        Assertion::keyExists($state, 'player2_move');
        Assertion::keyExists($state, 'status');
        Assertion::keyExists($state, 'created');

        $game = new Game();
        $game->gameId = GameId::fromString($state['game_id']);
        $game->player1 = $state['player1'];
        $game->player2 = $state['player2'];
        $game->player1Move = new GameMove($state['player1_move']);
        $game->player2Move = new GameMove($state['player2_move']);
        $game->status = new GameStatus($state['status']);
        $game->created = new DateTimeImmutable($state['created']);

        return $game;
    }

    /**
     * Validate data from user to create a new game
     */
    private static function validateNewGameData(array $data): void
    {
        $messages = [];
        if (!array_key_exists('player1', $data)) {
            $messages['player1'] = 'player1 is missing';
        }
        if (!array_key_exists('player2', $data)) {
            $messages['player2'] = 'player2 is missing';
        }

        if (!empty($messages)) {
            throw ValidationException::withMessages($messages);
        }
    }

    /**
     * Validate data from user to make a move
     */
    private function validateMakeMoveData(array $data): void
    {
        $messages = [];
        if (!array_key_exists('move', $data)) {
            $messages['move'] = 'move is missing';
        } elseif (!in_array(strtoupper($data['move']), GameMove::$validNextMoves, true)) {
            $messages['move'] = "move is not one of '"
                . implode("', '", array_map('strtolower', GameMove::$validNextMoves)) . "'";
        }

        if (!array_key_exists('player', $data)) {
            $messages['player'] = 'player is missing';
        } elseif (!in_array($data['player'], [$this->player1, $this->player2], true)) {
            $messages['player'] = 'player is not part of this game';
        }

        if (!empty($messages)) {
            throw ValidationException::withMessages($messages);
        }
    }
}
