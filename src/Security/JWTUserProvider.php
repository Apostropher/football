<?php

namespace Football\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Football\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Football\Repository\UserRepositoryInterface;

class JWTUserProvider implements UserProviderInterface
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUsernameForApiKey($apiKey)
    {
        // Look up the username based on the token in the database, via
        // an API call, or do something entirely different
        $username = $this->userRepository->getIdByKey($apiKey);
        if (!$username) {
            throw new UnsupportedUserException();
        }

        return $username;
    }

    public function loadUserByUsername($username)
    {
        return $this->userRepository->findById($username);
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
