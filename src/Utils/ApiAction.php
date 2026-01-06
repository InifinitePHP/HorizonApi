<?php

namespace App\Utils;

class ApiAction
{
    public function __construct(
        public string $name,
        public array $methods = ['GET'],
        public string $path = '',
    ) {}
}
