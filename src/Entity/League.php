<?php

namespace Football\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Football\Repository\LeagueRepository")
 * @ORM\Table(
 *    name="league",
 *    indexes={
 *        @ORM\Index(name="league_created_atx", columns={"created_at"}),
 *        @ORM\Index(name="league_updated_atx", columns={"updated_at"}),
 *        @ORM\Index(name="league_deleted_atx", columns={"deleted_at"})
 *    }
 * )
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 */
class League extends AbstractLeague
{
    /**
     * @var Team[]
     * @ORM\OneToMany(targetEntity="Team", mappedBy="league", cascade={"persist", "remove", "merge"}, orphanRemoval=true)
     */
    private $teams;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
    }

    public function getTeams()
    {
        return $this->teams;
    }

    public function addTeam(Team $team)
    {
        $this->teams->add($team);
        $team->setLeague($this);

        return $this;
    }

    public function removeTeam(Team $team)
    {
        $this->teams->removeElement($team);

        return $this;
    }
}
