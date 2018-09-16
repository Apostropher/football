<?php

namespace Football\Repository;

use Symfony\Bridge\Doctrine\RegistryInterface;

interface UserRepositoryInterface
{
    public function __construct(RegistryInterface $registry);

    public function getIdByName($name): ?int;
}
