<?php

namespace Football\Factory;

use Football\Entity\League as LeagueEntity;
use Football\Entity\Team as TeamEntity;
use Football\Model\League as LeagueModel;
use Football\Model\Team as TeamModel;

class EntityFactory implements EntityFactoryInterface
{
    public function createLeague(LeagueModel $leagueModel): LeagueEntity
    {
        return (new LeagueEntity())->setName($leagueModel->name);
    }

    public function createTeam(TeamModel $teamModel, LeagueEntity $leagueEntity): TeamEntity
    {
        $teamEntity = $this->mapTeam($teamModel, new TeamEntity());

        $leagueEntity->addTeam($teamEntity);

        return $teamEntity;
    }

    public function replaceTeam(TeamModel $teamModel, TeamEntity $teamEntity): TeamEntity
    {
        return $this->mapTeam($teamModel, $teamEntity);
    }

    private function mapTeam(TeamModel $teamModel, TeamEntity $teamEntity)
    {
        return $teamEntity
            ->setName($teamModel->name)
            ->setStrip($teamModel->strip);
    }
}
