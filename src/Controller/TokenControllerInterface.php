<?php

namespace Football\Controller;

use Football\Service\JWTServiceInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/tokens", defaults={"_format": "json"})
 */
interface TokenControllerInterface
{
    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        JWTServiceInterface $jwtService
    );

    /**
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     *
     * @Route("", name="token_creation", methods={"POST"})
     */
    public function createLeague(Request $request): Response;
}
