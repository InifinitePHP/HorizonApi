<?php

namespace App\Api{{NAMESPACE}};

use App\Controller\ApiController;
use App\Utils\CoreApiAction;

class {{NAME}}Controller extends ApiController
{

    public static string $version = 'v1';
    public static string $route = '{{ROUTE}}';

    protected function getEntityClass(): string
    {
        return \App\Entity{{ENTITY}}::class;
    }

    public static function actions(): array
    {
        return [
            CoreApiAction::LIST->Action(),
            CoreApiAction::SHOW->Action(),
            CoreApiAction::CREATE->Action(),
            CoreApiAction::UPDATE->Action(),
            CoreApiAction::DELETE->Action()
        ];
    }
}