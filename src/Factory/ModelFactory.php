<?php

namespace Football\Factory;

use Football\Model\League as LeagueModel;
use Football\Model\Search\LeagueCollection as LeagueCollectionModel;
use Football\Model\Search\TeamCollection as TeamCollectionModel;
use Football\Model\Team as TeamModel;
use Knp\Component\Pager\Pagination\PaginationInterface;

class ModelFactory implements ModelFactoryInterface
{
    public function listLeagues(PaginationInterface $items, $page, $limit): LeagueCollectionModel
    {
        $collection = new LeagueCollectionModel();

        $collection->page = $page;
        $collection->limit = $limit;
        $collection->total = $items->getTotalItemCount();

        foreach ($items as $item) {
            $leagueModel = new LeagueModel();

            $leagueModel->id = $item->getId();
            $leagueModel->name = $item->getName();

            $collection->leagues[] = $leagueModel;
        }

        return $collection;
    }

    public function listTeams(PaginationInterface $items, $page, $limit): TeamCollectionModel
    {
        $collection = new TeamCollectionModel();

        $collection->page = $page;
        $collection->limit = $limit;
        $collection->total = $items->getTotalItemCount();

        foreach ($items as $item) {
            $teamModel = new TeamModel();

            $teamModel->id = $item->getId();
            $teamModel->name = $item->getName();
            $teamModel->leagueId = $item->getLeague()->getId();

            $collection->teams[] = $teamModel;
        }

        return $collection;
    }
}
