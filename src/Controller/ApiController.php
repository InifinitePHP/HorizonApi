<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends AbstractController
{  
    public function list() : JsonResponse {
        return new JsonResponse(['1', '2', '3']);
    }

    public function show() : JsonResponse {
        return new JsonResponse(1);
    }

    public function create() : JsonResponse {
        return new JsonResponse(1);
    }

    public function update() : JsonResponse {
        return new JsonResponse(1);
    }

    Public function delete() : JsonResponse {
        return new JsonResponse(1);
    }
}
