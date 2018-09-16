<?php

namespace Football\Controller;

use Football\Exception\FootballException;
use Football\Exception\NotFoundException;
use Football\Model\League as LeagueModel;
use Football\Model\Search\Filter as FilterModel;
use Football\Model\Team as TeamModel;
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
 * @Route("/leagues", defaults={"_format": "json"})
 */
class LeagueController extends AbstractFootballController implements LeagueControllerInterface
{
    private $leagueService;

    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        LeagueServiceInterface $leagueService
    ) {
        parent::__construct($serializer, $validator);

        $this->leagueService = $leagueService;
    }

    /**
     * @Route("", name="league_creation", methods={"POST"})
     */
    public function createLeague(Request $request): Response
    {
        try {
            $leagueModel = $this->serializer->deserialize($request->getContent(), LeagueModel::class, 'json');

            $errors = $this->validator->validate($leagueModel);
            if ($errors->count()) {
                throw new FootballException($errors->get(0)->getMessage());
            }

            $result = $this->leagueService->createLeague($leagueModel);

            return new Response(
                $this->serializer->serialize(
                    $result,
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

    /**
     * @Route("", name="league_list", methods={"GET"})
     */
    public function listLeagues(Request $request): Response
    {
        $filter = new FilterModel();

        $filter->page = $request->get('page', FilterModel::DEFAULT_PAGE);
        $filter->limit = $request->get('limit', FilterModel::DEFAULT_LIMIT);

        $result = $this->leagueService->listLeagues($filter);

        return new Response(
            $this->serializer->serialize(
                $result,
                'json'
            ),
            Response::HTTP_OK
        );
    }

    /**
     * @Route(
     *  "/{leagueId}/teams",
     *  name="league_team_creation",
     *  methods={"POST"},
     *  requirements={"leagueId": "\d+"}
     * )
     */
    public function createTeam($leagueId, Request $request): Response
    {
        try {
            $teamModel = $this->serializer->deserialize($request->getContent(), TeamModel::class, 'json');

            $errors = $this->validator->validate($teamModel);
            if ($errors->count()) {
                throw new FootballException($errors->get(0)->getMessage());
            }

            $result = $this->leagueService->createTeam($leagueId, $teamModel);

            return new Response(
                $this->serializer->serialize(
                    $result,
                    'json'
                ),
                Response::HTTP_CREATED
            );
        } catch (JMSSerializerException $e) {
            throw new BadRequestHttpException(static::INVALID_REQUEST_MSG);
        } catch (NotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (FootballException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    /**
     * @Route(
     *  "/{leagueId}/teams/{teamId}",
     *  name="league_team_replacement",
     *  methods={"PUT"},
     *  requirements={"leagueId": "\d+", "teamId": "\d+"}
     * )
     */
    public function replaceTeam($leagueId, $teamId, Request $request): Response
    {
        try {
            $teamModel = $this->serializer->deserialize($request->getContent(), TeamModel::class, 'json');

            $errors = $this->validator->validate($teamModel);
            if ($errors->count()) {
                throw new FootballException($errors->get(0)->getMessage());
            }

            $this->leagueService->replaceTeam($leagueId, $teamId, $teamModel);

            return new Response(null, Response::HTTP_NO_CONTENT);
        } catch (JMSSerializerException $e) {
            throw new BadRequestHttpException(static::INVALID_REQUEST_MSG);
        } catch (NotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (FootballException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    /**
     * @Route(
     *  "/{leagueId}/teams",
     *  name="league_team_list",
     *  methods={"GET"},
     *  requirements={"leagueId": "\d+"}
     * )
     */
    public function listTeams($leagueId, Request $request): Response
    {
        $filter = new FilterModel();

        $filter->page = $request->get('page', FilterModel::DEFAULT_PAGE);
        $filter->limit = $request->get('limit', FilterModel::DEFAULT_LIMIT);

        $result = $this->leagueService->listTeams($leagueId, $filter);

        return new Response(
            $this->serializer->serialize(
                $result,
                'json'
            ),
            Response::HTTP_OK
        );
    }

    /**
     * @throws NotFoundHttpException
     *
     * @Route(
     *  "/{leagueId}",
     *  name="league_deletion",
     *  methods={"DELETE"},
     *  requirements={"leagueId": "\d+"}
     * )
     */
    public function deleteLeague($leagueId): Response
    {
        try {
            $this->leagueService->deleteLeague($leagueId);

            return new Response(null, Response::HTTP_NO_CONTENT);
        } catch (NotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
    }
}
