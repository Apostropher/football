<?php

namespace Football\Service;

use Football\Exception\FootballException;
use Football\Exception\NotFoundException;
use Football\Model\JWT as JWTModel;
use Football\Repository\UserRepositoryInterface;
use JMS\Serializer\SerializerInterface;

class JWTService implements JWTServiceInterface
{
    const TOKEN_FORMAT = '%s.%s.%s';
    const INVALID_TOKEN_MSG = 'jwt.token.invalid';
    const EXPIRED_TOKEN_MSG = 'jwt.token.expired';
    const INVALID_BODY_NAME_MSG = 'jwt.body.name.invalid';

    private $userRepository;
    private $serializer;
    private $secret;

    public function __construct(
        UserRepositoryInterface $userRepository,
        SerializerInterface $serializer,
        string $secret
    ) {
        $this->userRepository = $userRepository;
        $this->serializer = $serializer;
        $this->secret = $secret;
    }

    public function generateToken(JWTModel\Body $jwt): JWTModel\Token
    {
        $id = $this->userRepository->getIdByUsername($jwt->name);
        if (!$id) {
            throw new NotFoundException(self::INVALID_BODY_NAME_MSG);
        }

        $headerString = base64_encode($this->serializer->serialize(
            new JWTModel\Header(),
            'json'
        ));

        $jwt->iat = time();
        $jwt->exp = ($jwt->iat + $jwt->ttl);

        $bodyString = base64_encode($this->serializer->serialize(
            $jwt,
            'json'
        ));

        $signature = $this->generateSignature($headerString, $bodyString);

        $tokenModel = new JWTModel\Token();
        $tokenModel->token = sprintf(self::TOKEN_FORMAT, $headerString, $bodyString, $signature);

        return $tokenModel;
    }

    public function validateToken(string $token): string
    {
        list($headerString, $bodyString, $signature) = explode('.', $token);

        $expectedSignature = $this->generateSignature($headerString, $bodyString);

        if (!hash_equals($expectedSignature, $signature)) {
            throw new FootballException(self::INVALID_TOKEN_MSG);
        }

        $bodyModel = $this->serializer->deserialize(
            base64_decode($bodyString),
            JWTModel\Body::class,
            'json'
        );

        if (time() > $bodyModel->exp) {
            throw new FootballException(self::EXPIRED_TOKEN_MSG);
        }

        return $bodyModel->name;
    }

    private function generateSignature($headerString, $bodyString)
    {
        return hash_hmac('sha256', $headerString.'.'.$bodyString, $this->secret);
    }
}
