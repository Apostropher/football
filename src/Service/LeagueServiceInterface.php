<?php

namespace Football\Service;

use Doctrine\ORM\EntityManagerInterface;
use Football\Exception\FootballException;
use Football\Exception\NotFoundException;
use Football\Factory\EntityFactoryInterface;
use Football\Factory\ModelFactoryInterface;
use Football\Model\Filter as FilterModel;
use Football\Model\League as LeagueModel;
use Football\Model\Response as ResponseModel;
use Football\Model\Search\AbstractCollection as AbstractCollectionModel;
use Football\Model\Team as TeamModel;
use Football\Repository\LeagueRepositoryInterface;
use Football\Repository\TeamRepositoryInterface;

interface LeagueServiceInterface
{
    public function __construct(
        ModelFactoryInterface $modelFactory,
        EntityFactoryInterface $entityFactory,
        LeagueRepositoryInterface $leagueRepository,
        TeamRepositoryInterface $teamRepository,
        EntityManagerInterface $entityManager
    );

    /**
     * @throws FootballException
     */
    public function createLeague(LeagueModel $league): ResponseModel;

    public function listLeagues(FilterModel $filter): AbstractCollectionModel;

    public function listTeams($leagueId, FilterModel $filter): AbstractCollectionModel;

    /**
     * @throws FootballException
     * @throws NotFoundException
     */
    public function createTeam($leagueId, TeamModel $team): ResponseModel;

    /**
     * @throws FootballException
     * @throws NotFoundException
     */
    public function replaceTeam($leagueId, $teamId, TeamModel $team);

    /**
     * @throws FootballException
     * @throws NotFoundException
     */
    public function deleteLeague($leagueId);
}
