<?php

namespace Football\Repository;

use Football\Entity\League as LeagueEntity;
use Football\Model\Filter as FilterModel;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

class LeagueRepository implements LeagueRepositoryInterface
{
    private $paginator;

    public function __construct(RegistryInterface $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, LeagueEntity::class);
        $this->paginator = $paginator;
    }

    public function findPaginatedUndeleted(FilterModel $filter): PaginationInterface
    {
        return $this->paginator->paginate([]);
    }

    public function findUndeletedById($id): LeagueEntity
    {
        return new LeagueEntity();
    }
}
