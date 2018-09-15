<?php

namespace Football\Factory;

use Football\Model\Search\LeagueCollection as LeagueCollectionModel;
use Football\Model\Search\TeamCollection as TeamCollectionModel;
use Knp\Component\Pager\Pagination\PaginationInterface;

class ModelFactory implements ModelFactoryInterface
{
    public function listLeagues(PaginationInterface $items, $page, $limit): LeagueCollectionModel
    {
        return new LeagueCollectionModel();
    }

    public function listTeams(PaginationInterface $items, $page, $limit): TeamCollectionModel
    {
        return new TeamCollectionModel();
    }
}
