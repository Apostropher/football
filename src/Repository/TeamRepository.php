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
        $qb = $this
            ->createUndeletedByLeagueIdQueryBuilder($leagueId)
            ->orderBy('t.updatedAt', 'DESC');

        return $this->paginator->paginate($qb->getQuery(), $filter->page, $filter->limit/*, ['wrap-queries' => true]*/);
    }

    public function findUndeletedByIdAndLeagueId($id, $leagueId): ?TeamEntity
    {
        return $this
            ->createUndeletedByLeagueIdQueryBuilder($leagueId)
            ->andWhere('t.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    private function createUndeletedByLeagueIdQueryBuilder($leagueId)
    {
        return $this
            ->createQueryBuilder('t')
            ->select('t, l')
            ->join('t.league', 'l')
            ->where('t.deletedAt IS NULL')
            ->andWhere('l.id = :leagueId')
            ->setParameter('leagueId', $leagueId);
    }
}
