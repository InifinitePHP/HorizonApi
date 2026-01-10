<?php

namespace App\Controller;

use App\Utils\ApiControllerInterface;
use App\Utils\EntityBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;


abstract class ApiController extends AbstractController implements ApiControllerInterface
{  

    public static abstract function actions(): array;
    protected abstract function getEntityClass(): string;

    public function __construct(
        protected EntityManagerInterface $em,
        protected RequestStack $request
    ) {}

    protected function getRequestBody(): mixed {
        return json_decode($this->request->getCurrentRequest()->getContent());
    }

    public function list() : JsonResponse {

        $class = $this->getEntityClass();

        /** @phpstan-ignore-next-line */
        $repo = $this->em->getRepository($class);
        $entities = $repo->findAll();

        return new JsonResponse($entities);
    }

    public function show() : JsonResponse {
        return new JsonResponse(1);
    }

    public function create() : JsonResponse {

        $entity = (new EntityBuilder())->build(
            $this->getEntityClass(),
            $this->getRequestBody()
        );

        try {
            $this->em->persist($entity);
            // $this->em->flush();
        } catch (Exception $e) {
            throw new Exception(
                "persitante exception not implemented yet ! \n"
                . $e->getMessage()
            );
        }

        return new JsonResponse($entity);
    }

    public function update() : JsonResponse {
        return new JsonResponse(1);
    }

    Public function delete() : JsonResponse {
        return new JsonResponse(1);
    }
}
