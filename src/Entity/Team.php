<?php

namespace Football\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Football\Repository\TeamRepository")
 * @ORM\Table(
 *    name="team",
 *    indexes={
 *        @ORM\Index(name="team_created_atx", columns={"created_at"}),
 *        @ORM\Index(name="team_updated_atx", columns={"updated_at"}),
 *        @ORM\Index(name="team_deleted_atx", columns={"deleted_at"})
 *    }
 * )
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 */
class Team extends AbstractLeague
{
    /**
     * @var League
     * @ORM\ManyToOne(targetEntity="League", inversedBy="teams")
     * @ORM\JoinColumn(name="league_id", referencedColumnName="id")
     */
    private $league;

    /**
     * @var string
     * @ORM\Column(name="strip", type="string", length=255)
     */
    private $strip;

    public function getLeague()
    {
        return $this->league;
    }

    public function setLeague(League $league)
    {
        $this->league = $league;

        return $this;
    }

    public function getStrip()
    {
        return $this->strip;
    }

    public function setStrip(string $strip)
    {
        $this->strip = $strip;

        return $this;
    }
}
