<?php

namespace Football\Controller;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AbstractFootballController extends AbstractController
{
    const INVALID_REQUEST_MSG = 'http.request.invalid';

    protected $serializer;
    protected $validator;

    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }
}
