<?php

declare(strict_types=1);

namespace App\Transformer;

use App\Model\Entity;
use Nocarrier\Hal;

Interface Transformer
{
    public function transformItem(Entity $object): Hal;

    /**
     * @param Entity[] $objects
     * @return Hal
     */
    public function transformCollection(array $objects): Hal;
}
