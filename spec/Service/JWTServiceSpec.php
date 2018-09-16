<?php

namespace spec\Football\Service;

use Football\Service\JWTService;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use JMS\Serializer\SerializerInterface;
use Football\Exception\FootballException;
use Football\Model\JWT as JWTModel;
use Football\Repository\UserRepositoryInterface;
use Football\Exception\NotFoundException;

class JWTServiceSpec extends ObjectBehavior
{
    function let(UserRepositoryInterface $userRepository, SerializerInterface $serializer)
    {
        $this->beConstructedWith(
            $userRepository,
            $serializer,
            'test'
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(JWTService::class);
    }

    function it_successfully_generates_a_token(UserRepositoryInterface $userRepository)
    {
        $payload = new JWTModel\Body();

        $payload->name = 'A-NAME';

        $userRepository->getIdByUsername($payload->name)->shouldBeCalled()->willReturn(1);

        $response = $this->generateToken($payload);

        $response->shouldBeAnInstanceOf(JWTModel\Token::class);
    }

    function it_should_throw_an_exception_if_username_is_non_existent_during_token_generation(
        UserRepositoryInterface $userRepository
    ) {
        $payload = new JWTModel\Body();

        $payload->name = 'A-NAME';

        $userRepository->getIdByUsername($payload->name)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(NotFoundException::class)->during('generateToken', [$payload]);
    }

    function it_should_throw_an_exception_if_signature_is_incorrect_during_token_validation() {
        $token = "ewogICAgImFsZyI6ICJIUzI1NiIsCiAgICAidHlwIjogIkpXVCIKfQ==.ewogICAgImlhdCI6IDE1MzcxMTA0MDYsCiAgICAiZXhwIjogMTUzNzExNDAwNiwKICAgICJ0dGwiOiAzNjAwLAogICAgIm5hbWUiOiAiQS1OQU1FIgp9.876c5c81b0a21bc8db57dce06bdb914f44805e1bfa98eb1474a996407efb9e79";

        $this->shouldThrow(FootballException::class)->during('validateToken', [$token]);
    }

    function it_should_throw_an_exception_if_token_has_expired_during_token_validation() {
        $token = "ewogICAgImFsZyI6ICJIUzI1NiIsCiAgICAidHlwIjogIkpXVCIKfQ==.ewogICAgImlhdCI6IDE1MzcxMTIyMDYsCiAgICAiZXhwIjogMTUzNzExMjIwNywKICAgICJ0dGwiOiAxLAogICAgIm5hbWUiOiAiQS1OQU1FIgp9.dd7609ae6f29e9900cb59bb1347aef642b4eea0d8ecbdcc3529a894559b2e8c2";

        $this->shouldThrow(FootballException::class)->during('validateToken', [$token]);
    }
}
