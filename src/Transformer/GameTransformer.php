<?php

declare(strict_types=1);

namespace App\Transformer;

use App\Model\Entity;
use App\Model\Game;
use Nocarrier\Hal;

final class GameTransformer implements Transformer
{
    public function transformItem(Entity $object): Hal
    {
        $data = $object->state();

        $resource = new Hal('/games/' . $data['game_id'], $data);
        return $resource;
    }

    /**
     * @param Entity[] $objects
     * @return Hal
     */
    public function transformCollection(array $objects): Hal
    {
        $hal = new Hal('/authors');

        $count = 0;
        foreach ($objects as $object) {
            $count++;
            $hal->addResource('game', $this->transform($object));
        }

        $hal->setData(['count' => $count]);

        return $hal;
    }
}
