<?php

namespace Football\Model\JWT;

use JMS\Serializer\Annotation as JMSSerializer;

class Header
{
    const TYPE = 'JWT';
    const ALGORITHM = 'HS256';

    /**
     * @JMSSerializer\Type("string")
     */
    public $alg = self::ALGORITHM;

    /**
     * @JMSSerializer\Type("string")
     */
    public $typ = self::TYPE;
}
