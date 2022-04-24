<?php

namespace App\Controller;

use App\Entity\Student;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class StudentController extends AbstractController
{
    #[Route('/student', name: 'student_index')]
    public function studentIndex(ManagerRegistry $registry): Response
    {
        $students = $registry->getRepository(Student::class)->findAll();
        return $this->render('student/index.html.twig', [
            'students' => $students,
        ]);
    }
    #[Route('/student/add', name:'student_add')]
    public function studentAdd(ManagerRegistry $registry, Request $request): Response
    {
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $image = $student->getImage();
            $imgName = uniqid();
            $imgExtension = $image->guessExtension();
            $imageName = $imgName . '_' . $imgExtension;
            try{
                $image->move(
                    $this->getParameter('student_image'),
                    $imageName
                );
            }catch(FileException $e){
                
            }
            $student->setImage($imageName);
            $manager = $registry->getManager();
            $manager->persist($student);
            $manager->flush();
            $this->addFlash('success', "Student has been added successfully !");
            return $this->redirectToRoute('student_index');
        }
        return $this->renderForm('student/add.html.twig',[
            'studentForm' => $form
        ]);
    }
}
