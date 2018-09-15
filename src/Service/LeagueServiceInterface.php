<?php

namespace Football\Service;

use Doctrine\ORM\EntityManagerInterface;
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

    public function createLeague(LeagueModel $league): ResponseModel;

    public function listLeagues(FilterModel $filter): AbstractCollectionModel;

    public function listTeams($leagueId, FilterModel $filter): AbstractCollectionModel;

    public function createTeam($leagueId, TeamModel $team): ResponseModel;

    public function replaceTeam($leagueId, $teamId, TeamModel $team): boolean;

    public function deleteLeague($leagueId): boolean;
}
