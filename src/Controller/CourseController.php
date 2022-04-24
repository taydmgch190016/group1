<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CourseController extends AbstractController
{
    #[Route('/', name: 'course_index')]
    public function courseIndex (ManagerRegistry $registry) {
        $courses = $registry->getRepository(Course::class)->findAll();
        $teachers = $registry->getRepository(Teacher::class)->findAll();
        return $this->render("course/index.html.twig",
        [
            'courses' => $courses,
            'teachers' => $teachers
        ]);
    }
    #[Route('/detail/{id}', name: 'course_detail')]
    public function courseDetail (ManagerRegistry $registry, $id) {
        $course = $registry->getRepository(Course::class)->find($id);
        $teachers = $registry->getRepository(Teacher::class)->findAll();
        if ($course == null) {
            $this->addFlash("Error","course not found !");
            return $this->redirectToRoute("course_index");
        }
        return $this->render("course/detail.html.twig",
        [
            'course' => $course,
            'teachers' => $teachers
        ]);
    }
    #[Route('/delete/{id}', name: 'course_delete')]
    public function courseDelete (ManagerRegistry $registry, $id) {
        $course = $registry->getRepository(Course::class)->find($id);
        $Teachers = $registry->getRepository(Teacher::class)->findAll();
        if ($course == null) {
            $this->addFlash("Error","course not found !");
        } else {
            $manager = $registry->getManager();
            $manager->remove($course);
            $manager->flush();
            $this->addFlash("Success", "course delete succeed !");
        }
        return $this->redirectToRoute("course_index",
                        [
                            'teachers' => $Teachers
                        ]);
    }

}