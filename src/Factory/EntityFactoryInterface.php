<?php

namespace Football\Factory;

use Football\Entity\League as LeagueEntity;
use Football\Entity\Team as TeamEntity;
use Football\Model\League as LeagueModel;
use Football\Model\Team as TeamModel;

interface EntityFactoryInterface
{
    public function createLeague(LeagueModel $leagueModel): LeagueEntity;

    public function createTeam(TeamModel $teamModel, LeagueEntity $leagueEntity): TeamEntity;

    public function replaceTeam(TeamModel $teamModel, TeamEntity $teamEntity): TeamEntity;
}
