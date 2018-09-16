<?php

namespace Football\Controller;

use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

    protected function normalisePaginationLimit($limit, $maxLimit)
    {
        if ((int) $limit < 1) {
            $limit = 1;
        }

        return $limit > $maxLimit ? $maxLimit : $limit;
    }
}
