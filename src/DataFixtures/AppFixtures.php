<?php

namespace App\DataFixtures;

use App\Entity\Location;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    const locations = ['Madrid', 'Mexico City', 'United Kingdom'];

    public function load(ObjectManager $manager): void
    {
        foreach (self::locations as $locationName) {
            $location = new Location();
            $location->setName($locationName);

            $manager->persist($location);
        }

        $manager->flush();
    }
}
