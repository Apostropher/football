<?php

namespace Football\Service;

use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Football\Entity\League as LeagueEntity;
use Football\Entity\Team as TeamEntity;
use Football\Exception\FootballException;
use Football\Exception\NotFoundException;
use Football\Factory\EntityFactoryInterface;
use Football\Factory\ModelFactoryInterface;
use Football\Model\League as LeagueModel;
use Football\Model\Response as ResponseModel;
use Football\Model\Search\AbstractCollection as AbstractCollectionModel;
use Football\Model\Search\Filter as FilterModel;
use Football\Model\Team as TeamModel;
use Football\Repository\LeagueRepositoryInterface;
use Football\Repository\TeamRepositoryInterface;

class LeagueService implements LeagueServiceInterface
{
    const DATABASE_ERROR_MESSAGE = 'database.error';
    const LEAGUE_NOT_FOUND_MESSAGE = 'league.not_found';
    const TEAM_NOT_FOUND_MESSAGE = 'team.not_found';

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
        try {
            $leagueEntity = $this->entityFactory->createLeague($league);

            $this->entityManager->persist($leagueEntity);
            $this->entityManager->flush();

            $response = new ResponseModel();
            $response->id = $leagueEntity->getId();

            return $response;
        } catch (DBALException $e) {
            throw new FootballException(self::DATABASE_ERROR_MESSAGE);
        }
    }

    public function listLeagues(FilterModel $filter): AbstractCollectionModel
    {
        $leagues = $this->leagueRepository->findPaginatedUndeleted($filter);

        return $this->modelFactory->listLeagues($leagues, $filter->page, $filter->limit);
    }

    public function singleLeague($leagueId): LeagueModel
    {
        $leagueEntity = $this->leagueRepository->findUndeletedById($leagueId);

        if (!$leagueEntity instanceof LeagueEntity) {
            throw new NotFoundException(self::LEAGUE_NOT_FOUND_MESSAGE);
        }

        return $this->modelFactory->singleLeague($leagueEntity);
    }

    public function listTeams($leagueId, FilterModel $filter): AbstractCollectionModel
    {
        $teams = $this->teamRepository->findPaginatedUndeletedByLeagueId($leagueId, $filter);

        return $this->modelFactory->listTeams($teams, $filter->page, $filter->limit);
    }

    public function singleTeam($leagueId, $teamId): TeamModel
    {
        $teamEntity = $this->teamRepository->findUndeletedByIdAndLeagueId($teamId, $leagueId);

        if (!$teamEntity instanceof TeamEntity) {
            throw new NotFoundException(self::TEAM_NOT_FOUND_MESSAGE);
        }

        return $this->modelFactory->singleTeam($teamEntity);
    }

    public function createTeam($leagueId, TeamModel $team): ResponseModel
    {
        try {
            $leagueEntity = $this->leagueRepository->findUndeletedById($leagueId);

            if (!$leagueEntity instanceof LeagueEntity) {
                throw new NotFoundException(self::LEAGUE_NOT_FOUND_MESSAGE);
            }

            $teamEntity = $this->entityFactory->createTeam($team, $leagueEntity);

            $this->entityManager->flush();

            $response = new ResponseModel();
            $response->id = $teamEntity->getId();

            return $response;
        } catch (DBALException $e) {
            throw new FootballException(self::DATABASE_ERROR_MESSAGE);
        }
    }

    public function replaceTeam($leagueId, $teamId, TeamModel $team)
    {
        try {
            $teamEntity = $this->teamRepository->findUndeletedByIdAndLeagueId($teamId, $leagueId);

            if (!$teamEntity instanceof TeamEntity) {
                throw new NotFoundException(self::TEAM_NOT_FOUND_MESSAGE);
            }

            $this->entityFactory->replaceTeam($team, $teamEntity);

            $this->entityManager->flush();
        } catch (DBALException $e) {
            throw new FootballException(self::DATABASE_ERROR_MESSAGE);
        }
    }

    public function deleteLeague($leagueId)
    {
        try {
            $leagueEntity = $this->leagueRepository->findUndeletedById($leagueId);

            if (!$leagueEntity instanceof LeagueEntity) {
                throw new NotFoundException(self::LEAGUE_NOT_FOUND_MESSAGE);
            }

            $this->entityManager->remove($leagueEntity);
            $this->entityManager->flush();
        } catch (DBALException $e) {
            throw new FootballException(self::DATABASE_ERROR_MESSAGE);
        }
    }
}
