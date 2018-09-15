<?php

namespace Football\Service;

use Doctrine\ORM\EntityManagerInterface;
use Football\Factory\EntityFactoryInterface;
use Football\Factory\ModelFactoryInterface;
use Football\Model\Filter as FilterModel;
use Football\Model\League as LeagueModel;
use Football\Model\Response as ResponseModel;
use Football\Model\Search\AbstractCollection as AbstractCollectionModel;
use Football\Model\Search\LeagueCollection as LeagueCollectionModel;
use Football\Model\Search\TeamCollection as TeamCollectionModel;
use Football\Model\Team as TeamModel;
use Football\Repository\LeagueRepositoryInterface;
use Football\Repository\TeamRepositoryInterface;

class LeagueService implements LeagueServiceInterface
{
    private $modelFactory;
    private $entityFactory;
    private $leagueRepository;
    private $teamRepository;
    private $entityManager;

    public function __construct(
        ModelFactoryInterface $modelFactory,
        EntityFactoryInterface $entityFactory,
        LeagueRepositoryInterface $leagueRepository,
        TeamRepositoryInterface $teamRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->modelFactory = $modelFactory;
        $this->entityFactory = $entityFactory;
        $this->leagueRepository = $leagueRepository;
        $this->teamRepository = $teamRepository;
        $this->entityManager = $entityManager;
    }

    public function createLeague(LeagueModel $league): ResponseModel
    {
        return new ResponseModel();
    }

    public function listLeagues(FilterModel $filter): AbstractCollectionModel
    {
        return new LeagueCollectionModel();
    }

    public function listTeams($leagueId, FilterModel $filter): AbstractCollectionModel
    {
        return new TeamCollectionModel();
    }

    public function createTeam($leagueId, TeamModel $team): ResponseModel
    {
        return new ResponseModel();
    }

    public function replaceTeam($leagueId, $teamId, TeamModel $team)
    {
        return null;
    }

    public function deleteLeague($leagueId)
    {
        return null;
    }
}
