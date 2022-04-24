<?php

namespace App\DataFixtures;

use App\Entity\Teacher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TeacherFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i =1; $i<=5;$i++){
            $teacher = new Teacher();
            $teacher -> setName("Teacher No $i");
            $teacher -> setAge(rand(30,50));
            $teacher -> setPhone("0123456789");
            $teacher -> setAddress("Ha Noi $i");
            $teacher -> setImage("https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/001-teacher.svg/2048px-001-teacher.svg.png");
        }

        $manager->flush();
    }
}
