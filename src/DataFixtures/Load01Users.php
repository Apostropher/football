<?php

namespace Football\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Football\Entity\User;

class Load01Users extends Fixture
{
    use GetDataTrait;

    public function load(ObjectManager $manager)
    {
        foreach ($this->getData('Users.json') as $datum) {
            $user = (
                new User()
            )
                ->setUsername($datum['name'])
                ->setApiKey($datum['key']);

            $this->addReference($datum['reference'], $user);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
