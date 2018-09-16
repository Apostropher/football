<?php

namespace Football\Model;

use JMS\Serializer\Annotation as JMSSerializer;
use Symfony\Component\Validator\Constraints as Assert;

class Team
{
    /**
     * @JMSSerializer\Type("integer")
     */
    public $id;

    /**
     * @JMSSerializer\Type("integer")
     */
    public $leagueId;

    /**
     * @JMSSerializer\Type("string")
     * @Assert\NotBlank(message = "team.creation_or_replacement.name.blank")
     */
    public $name;

    /**
     * @JMSSerializer\Type("string")
     * @Assert\NotBlank(message = "team.creation_or_replacement.strip.blank")
     */
    public $strip;
}
