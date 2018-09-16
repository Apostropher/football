<?php

namespace Football\Service;

use Doctrine\ORM\EntityManagerInterface;
use Football\Exception\FootballException;
use Football\Exception\NotFoundException;
use Football\Factory\EntityFactoryInterface;
use Football\Factory\ModelFactoryInterface;
use Football\Model\League as LeagueModel;
use Football\Model\Response as ResponseModel;
use Football\Model\Search\AbstractCollection as AbstractCollectionModel;
use Football\Model\Search\Filter as FilterModel;
use Football\Model\Team as TeamModel;
use Football\Repository\LeagueRepositoryInterface;
use Football\Repository\UserRepositoryInterface;
use Football\Model\Token as TokenModel;
use Football\Model\JWT as JWTModel;

interface JWTServiceInterface
{
    public function __construct(SerializerInterface $serializer, UserRepositoryInterface $userRepository);

    public function generateToken(JWTModel $jwt): TokenModel;

    public function validateToken(string $token): boolean;
}
