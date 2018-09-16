<?php

namespace Football\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Football\Entity\League as LeagueEntity;
use Football\Model\Search\Filter as FilterModel;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

class LeagueRepository extends ServiceEntityRepository implements LeagueRepositoryInterface
{
    private $paginator;

    public function __construct(RegistryInterface $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, LeagueEntity::class);
        $this->paginator = $paginator;
    }

    public function findPaginatedUndeleted(FilterModel $filter): PaginationInterface
    {
        $qb = $this
            ->createUndeletedQueryBuilder()
            ->orderBy('l.updatedAt', 'DESC');

        return $this->paginator->paginate($qb->getQuery(), $filter->page, $filter->limit/*, ['wrap-queries' => true]*/);
    }

    public function findUndeletedById($id): ?LeagueEntity
    {
        return $this
            ->createUndeletedQueryBuilder()
            ->andWhere('l.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    private function createUndeletedQueryBuilder()
    {
        return $this
            ->createQueryBuilder('l')
            ->select('l')
            ->where('l.deletedAt IS NULL');
    }
}
