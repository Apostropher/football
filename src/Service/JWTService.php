<?php

namespace Football\Service;

use Football\Exception\FootballException;
use Football\Model\JWT as JWTModel;
use JMS\Serializer\SerializerInterface;

class JWTService implements JWTServiceInterface
{
    const TOKEN_FORMAT = '%s.%s.%s';
    const INVALID_TOKEN_MSG = 'jwt.token.invalid';
    const EXPIRED_TOKEN_MSG = 'jwt.token.expired';

    private $serializer;
    private $secret;

    public function __construct(
        SerializerInterface $serializer,
        string $secret
    ) {
        $this->serializer = $serializer;
        $this->secret = $secret;
    }

    public function generateToken(JWTModel\Body $jwt): JWTModel\Token
    {
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

        $bodyModel = base64_decode($this->serializer->deserialize(
            $bodyString,
            JWTModel\Body::class,
            'json'
        ));

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
