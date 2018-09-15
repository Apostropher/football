<?php

namespace Football\Controller;

use Football\Service\LeagueServiceInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/leagues")
 */
interface LeagueControllerInterface
{
    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        LeagueServiceInterface $leagueService
    );

    /**
     * @throws BadRequestHttpException
     *
     * @Route("", name="league_creation", methods={"POST"})
     */
    public function createLeague(Request $request): Response;

    /**
     * @Route("", name="league_list", methods={"GET"})
     */
    public function listLeagues(Request $request): Response;

    /**
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     *
     * @Route("/{leagueId}/teams", name="league_team_creation", methods={"POST"})
     */
    public function createTeam($leagueId, Request $request): Response;

    /**
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     *
     * @Route("/{leagueId}/teams/{teamId}", name="league_team_replacement", methods={"PUT"})
     */
    public function replaceTeam($leagueId, $teamId, Request $request): Response;

    /**
     * @Route("/{leagueId}/teams", name="league_team_list", methods={"GET"})
     */
    public function listTeams($leagueId, Request $request): Response;

    /**
     * @throws NotFoundHttpException
     *
     * @Route("/{leagueId}", name="league_deletion", methods={"DELETE"})
     */
    public function deleteLeague($leagueId): Response;
}
