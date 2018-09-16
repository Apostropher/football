<?php

namespace Football\Repository;

use Football\Entity\Team as TeamEntity;
use Football\Model\Search\Filter as FilterModel;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

interface TeamRepositoryInterface
{
    public function __construct(RegistryInterface $registry, PaginatorInterface $paginator);

    public function findPaginatedUndeletedByLeagueId($leagueId, FilterModel $filter): PaginationInterface;

    public function findUndeletedByIdAndLeagueId($id, $leagueId): TeamEntity;
}
