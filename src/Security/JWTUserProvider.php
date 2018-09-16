<?php

namespace Football\Security;

use Football\Entity\User;
use Football\Exception\FootballException;
use Football\Repository\UserRepositoryInterface;
use Football\Service\JWTServiceInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class JWTUserProvider implements UserProviderInterface
{
    private $jwtService;
    private $userRepository;

    public function __construct(
        JWTServiceInterface $jwtService,
        UserRepositoryInterface $userRepository
    ) {
        $this->jwtService = $jwtService;
        $this->userRepository = $userRepository;
    }

    public function getUsernameForApiKey($apiKey)
    {
        // Look up the username based on the token in the database, via
        // an API call, or do something entirely different
        $name = $this->jwtService->validateToken($apiKey);

        $id = $this->userRepository->getIdByUsername($name);
        if (!$id) {
            throw new FootballException(sprintf('Token "%s" does not exist.', $apiKey));
        }

        return $id;
    }

    public function loadUserByUsername($username)
    {
        return $this->userRepository->find($username);
    }

    public function refreshUser(UserInterface $user)
    {
        // this is used for storing authentication in the session
        // but in this example, the token is sent in each request,
        // so authentication can be stateless. Throwing this exception
        // is proper to make things stateless
        throw new UnsupportedUserException();
    }

    public function supportsClass($class)
    {
        return User::class === $class;
    }
}
