<?php

namespace Football\Repository;

use Football\Entity\Team as TeamEntity;
use Football\Model\Search\Filter as FilterModel;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TeamRepository implements TeamRepositoryInterface
{
    public function __construct(RegistryInterface $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, TeamEntity::class);
        $this->paginator = $paginator;
    }

    public function findPaginatedUndeletedByLeagueId($leagueId, FilterModel $filter): PaginationInterface
    {
        return $this->paginator->paginate([]);
    }

    public function findUndeletedByIdAndLeagueId($id, $leagueId): TeamEntity
    {
        return new TeamEntity();
    }
}
