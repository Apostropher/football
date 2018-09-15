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

        $response->shouldBeAnInstanceOf(ResponseModel::class)
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
}
