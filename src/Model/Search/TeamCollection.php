<?php

namespace Football\Model\Search;

use Football\Model\Team;
use JMS\Serializer\Annotation as JMSSerializer;

class TeamCollection extends AbstractCollection
{
    /**
     * @var Team[]
     * @JMSSerializer\Type("array<Football\Model\Team>")
     */
    public $teams = [];
}
