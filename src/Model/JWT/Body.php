<?php

namespace Football\Model\JWT;

use JMS\Serializer\Annotation as JMSSerializer;
use Symfony\Component\Validator\Constraints as Assert;

class Body
{
    const DEFAULT_TTL = 3600;

    /**
     * @JMSSerializer\Type("integer")
     */
    public $iat;

    /**
     * @JMSSerializer\Type("integer")
     */
    public $exp;

    /**
     * @JMSSerializer\Type("integer")
     * @Assert\NotNull(message = "jwt.body.ttl.not_null")
     */
    public $ttl = self::DEFAULT_TTL;

    /**
     * @JMSSerializer\Type("string")
     * @Assert\NotBlank(message = "jwt.body.name.blank")
     */
    public $name;
}
