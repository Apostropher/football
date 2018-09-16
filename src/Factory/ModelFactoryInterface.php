<?php

namespace Football\Factory;

use Football\Entity\League as LeagueEntity;
use Football\Entity\Team as TeamEntity;
use Football\Model\League as LeagueModel;
use Football\Model\Search\LeagueCollection as LeagueCollectionModel;
use Football\Model\Search\TeamCollection as TeamCollectionModel;
use Football\Model\Team as TeamModel;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface ModelFactoryInterface
{
    public function listLeagues(PaginationInterface $items, $page, $limit): LeagueCollectionModel;

    public function singleLeague(LeagueEntity $leagueEntity): LeagueModel;

    public function listTeams(PaginationInterface $items, $page, $limit): TeamCollectionModel;

    public function singleTeam(TeamEntity $teamEntity): TeamModel;
}
