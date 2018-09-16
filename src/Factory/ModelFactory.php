<?php

namespace Football\Factory;

use Football\Entity\League as LeagueEntity;
use Football\Entity\Team as TeamEntity;
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
            $collection->leagues[] = $this->singleLeague($item);
        }

        return $collection;
    }

    public function singleLeague(LeagueEntity $leagueEntity): LeagueModel
    {
        $leagueModel = new LeagueModel();

        $leagueModel->id = $leagueEntity->getId();
        $leagueModel->name = $leagueEntity->getName();

        return $leagueModel;
    }

    public function listTeams(PaginationInterface $items, $page, $limit): TeamCollectionModel
    {
        $collection = new TeamCollectionModel();

        $collection->page = $page;
        $collection->limit = $limit;
        $collection->total = $items->getTotalItemCount();

        foreach ($items as $item) {
            $collection->teams[] = $this->singleTeam($item);
        }

        return $collection;
    }

    public function singleTeam(TeamEntity $teamEntity): TeamModel
    {
        $teamModel = new TeamModel();

        $teamModel->id = $teamEntity->getId();
        $teamModel->name = $teamEntity->getName();
        $teamModel->leagueId = $teamEntity->getLeague()->getId();

        return $teamModel;
    }
}
