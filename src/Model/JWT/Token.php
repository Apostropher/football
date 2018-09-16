<?php

namespace Football\Model\JWT;

use JMS\Serializer\Annotation as JMSSerializer;

class Token
{
    /**
     * @JMSSerializer\Type("string")
     */
    public $token;
}
