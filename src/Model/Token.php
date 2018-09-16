<?php

namespace Football\Model;

use JMS\Serializer\Annotation as JMSSerializer;

class Token
{
    /**
     * @JMSSerializer\Type("string")
     */
    public $token;
}
