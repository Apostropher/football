<?php

namespace Football\Controller;

use Football\Exception\FootballException;
use Football\Model\JWT as JWTModel;
use Football\Service\JWTServiceInterface;
use JMS\Serializer\Exception\Exception as JMSSerializerException;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/tokens", defaults={"_format": "json"})
 */
class TokenController extends AbstractFootballController
{
    private $jwtService;

    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        JWTServiceInterface $jwtService
    ) {
        parent::__construct($serializer, $validator);

        $this->jwtService = $jwtService;
    }

    /**
     * @Route("", name="token_creation", methods={"POST"})
     */
    public function createLeague(Request $request): Response
    {
        try {
            $jwtModel = $this->serializer->deserialize($request->getContent(), JWTModel\Body::class, 'json');

            $errors = $this->validator->validate($jwtModel);
            if ($errors->count()) {
                throw new FootballException($errors->get(0)->getMessage());
            }

            $tokenModel = $this->jwtService->generateToken($jwtModel);

            return new Response(
                $this->serializer->serialize(
                    $tokenModel,
                    'json'
                ),
                Response::HTTP_OK
            );
        } catch (JMSSerializerException $e) {
            throw new BadRequestHttpException(static::INVALID_REQUEST_MSG);
        } catch (FootballException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}
