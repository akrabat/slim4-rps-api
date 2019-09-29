<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20190929184708 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create games table';
    }

    public function up(Schema $schema) : void
    {
        $table = $schema->createTable("games");
        $table->addColumn("game_id", "guid");
        $table->addColumn("player1", "string", ["length" => 100]);
        $table->addColumn("player2", "string", ["length" => 100]);
        $table->addColumn("player1_move", "string", ["length" => 100, 'notnull' => false]);
        $table->addColumn("player2_move", "string", ["length" => 100, 'notnull' => false]);
        $table->addColumn("status", "string", ["length" => 100]);
        $table->addColumn("created", "date");

        $table->setPrimaryKey(["game_id"]);
    }

    public function down(Schema $schema) : void
    {
        $schema->dropTable('games');
    }
}
