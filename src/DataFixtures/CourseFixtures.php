<?php

namespace App\DataFixtures;

use App\Entity\Course;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CourseFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i =1; $i<=10; $i++){
            $course = new Course();
            $course -> setName("Course no $i");
            $course -> setDescription("abcxyz");
            $course -> setCoursefee(rand(100,500));
            $manager -> persist($course);
        }

        $manager->flush();
    }
}
