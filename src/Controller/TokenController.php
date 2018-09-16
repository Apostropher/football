<?php

namespace Football\Controller;

use Football\Exception\FootballException;
use Football\Exception\NotFoundException;
use Football\Model\League as LeagueModel;
use Football\Model\Search\Filter as FilterModel;
use Football\Model\Token as TokenModel;
use Football\Model\JWT as JWTModel;
use Football\Service\LeagueServiceInterface;
use JMS\Serializer\Exception\Exception as JMSSerializerException;
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
class TokenController extends AbstractFootballController
{
    /**
     * @Route("", name="token_creation", methods={"POST"})
     */
    public function createLeague(Request $request): Response
    {
        try {
            $jwtModel = $this->serializer->deserialize($request->getContent(), JWTModel::class, 'json');

            $errors = $this->validator->validate($jwtModel);
            if ($errors->count()) {
                throw new FootballException($errors->get(0)->getMessage());
            }

            // $result = $this->leagueService->createLeague($leagueModel);
            $tokenModel = new TokenModel();
            $tokenModel->token = 'A-TOKEN';

            return new Response(
                $this->serializer->serialize(
                    $tokenModel,
                    'json'
                ),
                Response::HTTP_CREATED
            );
        } catch (JMSSerializerException $e) {
            throw new BadRequestHttpException(static::INVALID_REQUEST_MSG);
        } catch (FootballException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}
