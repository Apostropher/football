<?php

namespace Football\Model\Search;

use JMS\Serializer\Annotation as JMSSerializer;

abstract class AbstractCollection
{
    /**
     * @JMSSerializer\Type("int")
     */
    public $page;

    /**
     * @JMSSerializer\Type("int")
     */
    public $limit;

    /**
     * @JMSSerializer\Type("int")
     */
    public $total;
}
