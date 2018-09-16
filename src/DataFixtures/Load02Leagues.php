<?php

namespace Football\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Football\Entity\League;
use Football\Entity\Team;

class Load02Leagues extends Fixture
{
    use GetDataTrait;

    public function load(ObjectManager $manager)
    {
        foreach ($this->getData('Leagues.json') as $datum) {
            $leagueEntity = new League();
            $leagueEntity->setName($datum['name']);

            foreach ($datum['teams'] as $teamData) {
                $teamEntity = (
                    new Team()
                )
                    ->setName($teamData['name'])
                    ->setStrip($teamData['strip']);

                $this->addReference($teamData['reference'], $teamEntity);

                $leagueEntity->addTeam($teamEntity);
            }

            $this->addReference($datum['reference'], $leagueEntity);

            $manager->persist($leagueEntity);
        }

        $manager->flush();
    }
}
