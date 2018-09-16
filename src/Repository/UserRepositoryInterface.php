<?php

namespace Football\Repository;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Football\Entity\User;

interface UserRepositoryInterface
{
    public function __construct(RegistryInterface $registry);

    public function findOneByName($name): ?User;
}
