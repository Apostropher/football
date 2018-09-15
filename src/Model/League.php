<?php

namespace Football\Model;

use JMS\Serializer\Annotation as JMSSerializer;
use Symfony\Component\Validator\Constraints as Assert;

class League
{
    /**
     * @JMSSerializer\Type("integer")
     */
    public $id;

    /**
     * @JMSSerializer\Type("string")
     * @Assert\NotBlank(message = "league.creation.name.blank")
     */
    public $name;
}
