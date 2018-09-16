<?php

namespace spec\Football\Service;

use Football\Service\JWTService;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use JMS\Serializer\SerializerInterface;

class JWTServiceSpec extends ObjectBehavior
{
    function let(SerializerInterface $serializer)
    {
        $this->beConstructedWith(
            $serializer,
            'test'
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(JWTService::class);
    }
}
