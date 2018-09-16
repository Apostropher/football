<?php

namespace Football\Model;

use JMS\Serializer\Annotation as JMSSerializer;
use Symfony\Component\Validator\Constraints as Assert;

class JWT
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
     * @Assert\NotNull(message = "jwt.ttl.null")
     */
    public $ttl = self::DEFAULT_TTL;

    /**
     * @JMSSerializer\Type("string")
     * @Assert\NotBlank(message = "jwt.name.blank")
     */
    public $name;
}
