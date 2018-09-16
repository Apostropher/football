<?php

namespace spec\Football\Service;

use Football\Service\LeagueService;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Football\Repository\LeagueRepositoryInterface;
use Football\Repository\TeamRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Football\Factory\EntityFactoryInterface;
use Football\Factory\ModelFactoryInterface;
use Football\Model\Search\Filter as FilterModel;
use Football\Model\League as LeagueModel;
use Football\Model\Response as ResponseModel;
use Football\Model\Search\AbstractCollection as AbstractCollectionModel;
use Football\Model\Team as TeamModel;
use Football\Entity\League as LeagueEntity;
use Football\Entity\Team as TeamEntity;
use Football\Exception\FootballException;
use Football\Exception\NotFoundException;
use Doctrine\DBAL\DBALException;
use Knp\Component\Pager\Paginator;
use Football\Model\Search\LeagueCollection as LeagueCollectionModel;
use Football\Model\Search\TeamCollection as TeamCollectionModel;

class LeagueServiceSpec extends ObjectBehavior
{
    function let(
        ModelFactoryInterface $modelFactory,
        EntityFactoryInterface $entityFactory,
        LeagueRepositoryInterface $leagueRepository,
        TeamRepositoryInterface $teamRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->beConstructedWith(
            $modelFactory,
            $entityFactory,
            $leagueRepository,
            $teamRepository,
            $entityManager
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(LeagueService::class);
    }

    function it_successfully_creates_a_league(
        EntityFactoryInterface $entityFactory,
        LeagueEntity $leagueEntity,
        EntityManagerInterface $entityManager
    ) {
        $leagueModel = new LeagueModel();
        $leagueModel->name = 'LEAGUE 1';
        $leagueEntityId = 1;

        $leagueEntity->getId()->willReturn($leagueEntityId);

        $entityFactory->createLeague($leagueModel)->shouldBeCalled()->willReturn($leagueEntity);

        $entityManager->persist($leagueEntity)->shouldBeCalled()->willReturn(null);
        $entityManager->flush()->shouldBeCalled()->willReturn(null);

        $response = $this->createLeague($leagueModel);

        $response->shouldBeAnInstanceOf(ResponseModel::class);
    }

    function it_should_throw_an_exception_in_case_of_database_error_during_league_creation(
        EntityFactoryInterface $entityFactory,
        LeagueEntity $leagueEntity,
        EntityManagerInterface $entityManager
    ) {
        $leagueModel = new LeagueModel();

        $entityFactory->createLeague($leagueModel)->shouldBeCalled()->willReturn($leagueEntity);

        $entityManager->persist($leagueEntity)->shouldBeCalled()->willReturn(null);
        $entityManager->flush()->shouldBeCalled()->willThrow(DBALException::class);

        $this->shouldThrow(FootballException::class)->during('createLeague', [$leagueModel]);
    }

    function it_successfully_creates_a_team(
        EntityFactoryInterface $entityFactory,
        LeagueEntity $leagueEntity,
        TeamEntity $teamEntity,
        LeagueRepositoryInterface $leagueRepository,
        EntityManagerInterface $entityManager
    ) {
        $teamModel = new TeamModel();
        $teamModel->name = 'Team 1';
        $teamModel->strip = 'Black';

        $leagueId = 1;
        $teamId = 1;

        $leagueEntity->getId()->willReturn($leagueId);
        $teamEntity->getId()->willReturn($teamId);

        $leagueRepository->findUndeletedById($leagueId)->shouldBeCalled()->willReturn($leagueEntity);

        $entityFactory->createTeam($teamModel, $leagueEntity)->shouldBeCalled()->willReturn($teamEntity);

        $entityManager->flush()->shouldBeCalled()->willReturn(null);

        $response = $this->createTeam($leagueId, $teamModel);

        $response->shouldBeAnInstanceOf(ResponseModel::class);
    }

    function it_should_throw_an_exception_in_case_of_database_error_during_team_creation(
        EntityFactoryInterface $entityFactory,
        LeagueEntity $leagueEntity,
        TeamEntity $teamEntity,
        LeagueRepositoryInterface $leagueRepository,
        EntityManagerInterface $entityManager
    ) {
        $teamModel = new TeamModel();
        $teamModel->name = 'Team 1';
        $teamModel->strip = 'Black';

        $leagueId = 1;
        $teamId = 1;

        $leagueEntity->getId()->willReturn($leagueId);
        $teamEntity->getId()->willReturn($teamId);

        $leagueRepository->findUndeletedById($leagueId)->shouldBeCalled()->willReturn($leagueEntity);

        $entityFactory->createTeam($teamModel, $leagueEntity)->shouldBeCalled()->willReturn($teamEntity);

        $entityManager->flush()->shouldBeCalled()->willThrow(DBALException::class);

        $this->shouldThrow(FootballException::class)->during('createTeam', [$leagueId, $teamModel]);
    }

    function it_should_throw_an_exception_if_league_is_non_existent_during_team_creation(
        LeagueRepositoryInterface $leagueRepository
    ) {
        $leagueId = 1;

        $leagueRepository->findUndeletedById($leagueId)->shouldBeCalled()->willReturn(null);


        $this->shouldThrow(NotFoundException::class)->during('createTeam', [$leagueId, new TeamModel()]);
    }

    function it_successfully_replaces_a_team(
        EntityFactoryInterface $entityFactory,
        TeamEntity $teamEntity,
        TeamRepositoryInterface $teamRepository,
        EntityManagerInterface $entityManager
    ) {
        $teamModel = new TeamModel();
        $teamModel->name = 'Team 1';
        $teamModel->strip = 'Black';

        $leagueId = 1;
        $teamId = 1;

        $teamEntity->getId()->willReturn($teamId);

        $teamRepository->findUndeletedByIdAndLeagueId($teamId, $leagueId)->shouldBeCalled()->willReturn($teamEntity);

        $entityFactory->replaceTeam($teamModel, $teamEntity)->shouldBeCalled()->willReturn($teamEntity);

        $entityManager->flush()->shouldBeCalled()->willReturn(null);

        $this->replaceTeam($leagueId, $teamId, $teamModel);
    }

    function it_should_throw_an_exception_in_case_of_database_error_during_team_replacement(
        EntityFactoryInterface $entityFactory,
        TeamEntity $teamEntity,
        TeamRepositoryInterface $teamRepository,
        EntityManagerInterface $entityManager
    ) {
        $teamModel = new TeamModel();
        $teamModel->name = 'Team 1';
        $teamModel->strip = 'Black';

        $leagueId = 1;
        $teamId = 1;

        $teamEntity->getId()->willReturn($teamId);

        $teamRepository->findUndeletedByIdAndLeagueId($teamId, $leagueId)->shouldBeCalled()->willReturn($teamEntity);

        $entityFactory->replaceTeam($teamModel, $teamEntity)->shouldBeCalled()->willReturn($teamEntity);

        $entityManager->flush()->shouldBeCalled()->willThrow(DBALException::class);

        $this->shouldThrow(FootballException::class)->during('replaceTeam', [$leagueId, $teamId, $teamModel]);
    }

    function it_should_throw_an_exception_if_team_is_non_existent_during_team_replacement(
        TeamRepositoryInterface $teamRepository
    ) {
        $leagueId = 1;
        $teamId = 1;

        $teamRepository->findUndeletedByIdAndLeagueId($teamId, $leagueId)->shouldBeCalled()->willReturn(null);


        $this->shouldThrow(NotFoundException::class)->during('replaceTeam', [$leagueId, $teamId, new TeamModel()]);
    }

    function it_successfully_deletes_a_league(
        LeagueEntity $leagueEntity,
        LeagueRepositoryInterface $leagueRepository,
        EntityManagerInterface $entityManager
    ) {
        $leagueId = 1;

        $leagueRepository->findUndeletedById($leagueId)->shouldBeCalled()->willReturn($leagueEntity);

        $entityManager->remove($leagueEntity)->shouldBeCalled()->willReturn(null);
        $entityManager->flush()->shouldBeCalled()->willReturn(null);

        $this->deleteLeague($leagueId);
    }

    function it_should_throw_an_exception_in_case_of_database_error_during_league_deletion(
        LeagueEntity $leagueEntity,
        LeagueRepositoryInterface $leagueRepository,
        EntityManagerInterface $entityManager
    ) {
        $leagueId = 1;

        $leagueRepository->findUndeletedById($leagueId)->shouldBeCalled()->willReturn($leagueEntity);

        $entityManager->remove($leagueEntity)->shouldBeCalled()->willReturn(null);
        $entityManager->flush()->shouldBeCalled()->willThrow(DBALException::class);

        $this->shouldThrow(FootballException::class)->during('deleteLeague', [$leagueId]);
    }

    function it_should_throw_an_exception_if_league_is_non_existent_during_deletion(
        LeagueRepositoryInterface $leagueRepository
    ) {
        $leagueId = 1;

        $leagueRepository->findUndeletedById($leagueId)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(NotFoundException::class)->during('deleteLeague', [$leagueId]);
    }

    function it_successfully_lists_leagues(
        LeagueEntity $leagueEntity,
        LeagueRepositoryInterface $leagueRepository,
        ModelFactoryInterface $modelFactory
    ) {
        $filter = new FilterModel();
        $filter->page = 1;
        $filter->limit = 1;

        $paginator = new Paginator();
        $leagueEntities = $paginator->paginate([$leagueEntity]);

        $leagueEntity->getName()->willReturn('League 1');
        $leagueEntity->getId()->willReturn(1);

        $leagueRepository->findPaginatedUndeleted($filter)->shouldBeCalled()->willReturn($leagueEntities);

        $modelFactory->listLeagues($leagueEntities, $filter->page, $filter->limit)->shouldBeCalled();

        $collection = $this->listLeagues($filter);

        $collection->shouldBeAnInstanceOf(LeagueCollectionModel::class);
    }

    function it_successfully_retrieves_a_single_league(
        LeagueEntity $leagueEntity,
        LeagueRepositoryInterface $leagueRepository,
        ModelFactoryInterface $modelFactory
    ) {
        $leagueId = 1;

        $leagueEntity->getName()->willReturn('League 1');
        $leagueEntity->getId()->willReturn($leagueId);

        $leagueRepository->findUndeletedById($leagueId)->shouldBeCalled()->willReturn($leagueEntity);

        $modelFactory->singleLeague($leagueEntity)->shouldBeCalled()->willReturn(new LeagueModel());

        $this->singleLeague($leagueId);
    }

    function it_should_throw_an_exception_if_league_is_non_existent_during_single_retrieval(
        LeagueRepositoryInterface $leagueRepository
    ) {
        $leagueId = 1;

        $leagueRepository->findUndeletedById($leagueId)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(NotFoundException::class)->during('singleLeague', [$leagueId]);
    }

    function it_successfully_lists_teams(
        LeagueEntity $leagueEntity,
        TeamEntity $teamEntity,
        TeamRepositoryInterface $teamRepository,
        ModelFactoryInterface $modelFactory
    ) {
        $filter = new FilterModel();
        $filter->page = 1;
        $filter->limit = 1;

        $leagueId = 1;

        $paginator = new Paginator();
        $teamEntities = $paginator->paginate([$teamEntity]);

        $leagueEntity->getName()->willReturn('League 1');
        $leagueEntity->getId()->willReturn($leagueId);

        $teamEntity->getName()->willReturn('Team 1');
        $teamEntity->getId()->willReturn(1);
        $teamEntity->getLeague()->willReturn($leagueEntity);

        $teamRepository->findPaginatedUndeletedByLeagueId($leagueId, $filter)->shouldBeCalled()->willReturn($teamEntities);

        $modelFactory->listTeams($teamEntities, $filter->page, $filter->limit)->shouldBeCalled();

        $collection = $this->listTeams($leagueId, $filter);

        $collection->shouldBeAnInstanceOf(TeamCollectionModel::class);
    }

    function it_successfully_retrieves_a_single_team(
        LeagueEntity $leagueEntity,
        TeamEntity $teamEntity,
        TeamRepositoryInterface $teamRepository,
        ModelFactoryInterface $modelFactory
    ) {
        $leagueId = 1;
        $teamId = 1;

        $leagueEntity->getName()->willReturn('League 1');
        $leagueEntity->getId()->willReturn($leagueId);

        $teamEntity->getName()->willReturn('Team 1');
        $teamEntity->getId()->willReturn($teamId);
        $teamEntity->getLeague()->willReturn($leagueEntity);

        $teamRepository->findUndeletedByIdAndLeagueId($teamId, $leagueId)->shouldBeCalled()->willReturn($teamEntity);

        $modelFactory->singleTeam($teamEntity)->shouldBeCalled()->willReturn(new TeamModel());

        $this->singleTeam($leagueId, $teamId);
    }

    function it_should_throw_an_exception_if_team_is_non_existent_during_single_retrieval(
        TeamRepositoryInterface $teamRepository
    ) {
        $leagueId = 1;
        $teamId = 1;

        $teamRepository->findUndeletedByIdAndLeagueId($teamId, $leagueId)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(NotFoundException::class)->during('singleTeam', [$leagueId, $teamId]);
    }
}
