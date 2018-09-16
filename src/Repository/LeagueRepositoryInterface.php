<?php

namespace Football\Repository;

use Football\Entity\League as LeagueEntity;
use Football\Model\Search\Filter as FilterModel;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

interface LeagueRepositoryInterface
{
    public function __construct(RegistryInterface $registry, PaginatorInterface $paginator);

    public function findPaginatedUndeleted(FilterModel $filter): PaginationInterface;

    public function findUndeletedById($id): LeagueEntity;
}
