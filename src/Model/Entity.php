<?php

declare(strict_types=1);

namespace App\Model;

interface Entity
{
    /**
     * Return an array representing the state of this entity. The keys of this array are the exact names of the
     * database columns. Sample implementation:
     *
     *     return [
     *         'order_id' => 21,
     *         'order_date' => '2018-10-03'
     *     ];
     */
    public function state(): array;
}
