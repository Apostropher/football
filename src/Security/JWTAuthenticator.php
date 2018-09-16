<?php

namespace Football\Security;

use Football\Exception\FootballException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;

class JWTAuthenticator implements SimplePreAuthenticatorInterface, AuthenticationFailureHandlerInterface
{
    const TOKEN_HEADER = 'Authorization';
    const UNAUTHORISED_MESSAGE = 'http.request.unauthorised';

    public function createToken(Request $request, $providerKey)
    {
        $header = trim($request->headers->get(self::TOKEN_HEADER));

        $headerParts = explode(' ', $header);

        if (!isset($headerParts[1])) {
            throw new BadCredentialsException();
        }

        return new PreAuthenticatedToken(
            'anon.',
            $headerParts[1],
            $providerKey
        );
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        if (!$userProvider instanceof JWTUserProvider) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The user provider must be an instance of JWTUserProvider (%s was given).',
                    get_class($userProvider)
                )
            );
        }

        $apiKey = $token->getCredentials();

        try {
            $username = $userProvider->getUsernameForApiKey($apiKey);
        } catch (FootballException $e) {
            throw new CustomUserMessageAuthenticationException(
                $e->getMessage()
            );
        }

        $user = $userProvider->loadUserByUsername($username);

        return new PreAuthenticatedToken(
            $user,
            $apiKey,
            $providerKey,
            $user->getRoles()
        );
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // this contains information about *why* authentication failed
        // use it, or return your own message
        throw new UnauthorizedHttpException(
            sprintf('%s: Bearer abcde', self::TOKEN_HEADER),
            self::UNAUTHORISED_MESSAGE
        );
    }
}
