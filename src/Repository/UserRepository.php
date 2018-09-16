<?php

namespace Football\Repository;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Football\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NoResultException;

class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findOneByName($name): ?User
    {
        return $this->findOneBy(['name' => $name]);
    }
}
