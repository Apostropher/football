<?php

namespace Football\Service;

use Football\Exception\FootballException;
use Football\Model\JWT as JWTModel;
use JMS\Serializer\SerializerInterface;

interface JWTServiceInterface
{
    public function __construct(
        SerializerInterface $serializer,
        string $secret
    );

    public function generateToken(JWTModel\Body $jwt): JWTModel\Token;

    /**
     * @throws FootballException
     */
    public function validateToken(string $token): string;
}
