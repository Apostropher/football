<?php

namespace Football\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/leagues")
 */
interface LeagueControllerInterface
{
    /**
     * @throws BadRequestHttpException
     * @Route("", name="league_creation", methods={"POST"})
     */
    public function create(Request $request): Response;

    /**
     * @Route("", name="league_list", methods={"GET"})
     */
    public function list(Request $request): Response;

    /**
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @Route("/{leagueId}/teams/{teamId}", name="league_list", methods={"PUT"})
     */
    public function replace($leagueId, $teamId, Request $request): Response;

    /**
     * @throws NotFoundHttpException
     * @Route("/{leagueId}/teams/{teamId}", name="league_list", methods={"DELETE"})
     */
    public function delete($leagueId, $teamId): Response;
}
