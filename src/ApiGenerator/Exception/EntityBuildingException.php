<?php

namespace App\ApiGenerator\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class EntityBuildingException extends BadRequestHttpException {

    public function __construct(
        string $message,
        array $trace,
    ) {
        $output = $message . ' ' . join('->', $trace);
        parent::__construct($output);
    }
}