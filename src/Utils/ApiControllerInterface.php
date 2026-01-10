<?php

namespace App\Utils;

interface ApiControllerInterface {
    /**
     * @return array<int, ApiAction>
     */
    public static function actions(): array;
}