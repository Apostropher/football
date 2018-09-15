<?php

namespace Football\Repository;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Football\Model\Filter as FilterModel;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

interface LeagueRepositoryInterface
{
    public function __construct(RegistryInterface $registry, PaginatorInterface $paginator);

    public function findPaginatedUndeleted(FilterModel $filter): PaginationInterface;
}
