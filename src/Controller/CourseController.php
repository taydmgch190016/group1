<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Teacher;
use App\Form\CourseType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
#[Route('/course')]
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
    #[Route('/add', name: 'course_add')]
    public function courseAdd (Request $request, ManagerRegistry $registry, $id){
        // $teachers = $registry->getRepository(Teacher::class)->findAll();
        $course = $registry->getRepository(Course::class)->find($id);
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $manager = $registry->getManager();
            $manager->persist($course);
            $manager->flush();
            $this->addFlash("Success", "Add course succeed !");
            return $this->redirectToRoute('course_index');
        }
        return $this->renderForm('course/add.html.twig',
                                [
                                    'courseForm' => $form
                                ]);
    }
    #[Route('/edit/{id}', name: 'course_edit')]
    public function courseEdit (Request $request, ManagerRegistry $registry, $id){
        // $teachers = $registry->getRepository(Teacher::class)->findAll();
        $course = $registry->getRepository(Course::class)->find($id);
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $manager = $registry->getManager();
            $manager->persist($course);
            $manager->flush();
            $this->addFlash("Success", "Edit course succeed !");
            return $this->redirectToRoute('course_index');
        }
        return $this->renderForm('course/edit.html.twig',
                                [
                                    'courseForm' => $form
                                ]);
    }
}
