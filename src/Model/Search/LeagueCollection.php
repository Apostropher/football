<?php

namespace Football\Model\Search;

use Football\Model\League;
use JMS\Serializer\Annotation as JMSSerializer;

class LeagueCollection extends AbstractCollection
{
    /**
     * @var League[]
     * @JMSSerializer\Type("array<Football\Model\League>")
     */
    public $leagues = [];
}
