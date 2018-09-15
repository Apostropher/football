<?php

namespace Football\Model;

use JMS\Serializer\Annotation as JMSSerializer;

class Response
{
    /**
     * @JMSSerializer\Type("integer")
     */
    public $id;
}
