<?php

namespace Football\Factory;

use Football\Model\Search\LeagueCollection as LeagueCollectionModel;
use Football\Model\Search\TeamCollection as TeamCollectionModel;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface ModelFactoryInterface
{
    public function listLeagues(PaginationInterface $items, $page, $limit): LeagueCollectionModel;

    public function listTeams(PaginationInterface $items, $page, $limit): TeamCollectionModel;
}
