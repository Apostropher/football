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
use Football\Model\Filter as FilterModel;
use Football\Model\League as LeagueModel;
use Football\Model\Response as ResponseModel;
use Football\Model\Search\AbstractCollection as AbstractCollectionModel;
use Football\Model\Team as TeamModel;
use Football\Entity\League as LeagueEntity;
use Football\Entity\Team as TeamEntity;
use Football\Exception\FootballException;
use Football\Exception\NotFoundException;
use Doctrine\DBAL\DBALException;

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

        $entityManager->persist($teamEntity)->shouldBeCalled()->willReturn(null);
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

        $entityManager->persist($teamEntity)->shouldBeCalled()->willReturn(null);
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

        $entityManager->persist($teamEntity)->shouldBeCalled()->willReturn(null);
        $entityManager->flush()->shouldBeCalled()->willReturn(null);

        $this->replaceTeam($leagueId, $teamId, $teamModel);
    }

    function it_should_throw_an_exception_in_case_of_database_error_during_league_replacement(
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

        $entityManager->persist($teamEntity)->shouldBeCalled()->willReturn(null);
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
}
