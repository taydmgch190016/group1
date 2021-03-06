<?php

namespace App\Controller;

use App\Entity\Student;
use App\Entity\Classroom;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @IsGranted("ROLE_USER")
 */
class StudentController extends AbstractController
{
    #[Route('/student', name: 'student_index')]
    public function studentIndex(ManagerRegistry $registry): Response
    {
        $classes = $registry->getRepository(Classroom::class)->findAll();
        $students = $registry->getRepository(Student::class)->findAll();
        return $this->render('student/index.html.twig', [
            'students' => $students,
            'classes' => $classes
        ]);
    }
    /**
     * @IsGranted("ROLE_STAFF")
     */
    #[Route('/student/add', name:'student_add')]
    public function studentAdd(ManagerRegistry $registry, Request $request): Response
    {
        $classes = $registry->getRepository(Classroom::class)->findAll();
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $image = $student->getImage();
            $imgName = uniqid();
            $imgExtension = $image->guessExtension();
            $imageName = $imgName . '.' . $imgExtension;
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
            'studentForm' => $form,
            'classes' => $classes
        ]);
    }
    /**
     * @IsGranted("ROLE_STAFF")
     */
    #[Route('/student/edit/{id}', name: 'student_edit')]
    public function editStudent(ManagerRegistry $registry, Request $request, $id){
        $classes = $registry->getRepository(Classroom::class)->findAll();
        $student = $registry->getRepository(Student::class)->find($id);
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $file = $form['image']->getData();
            if($file != null){
            $image = $student->getImage();
            $imgName = uniqid();
            $imgExtension = $image->guessExtension();
            $imageName = $imgName . '.' . $imgExtension;
            try{
                $image->move(
                    $this->getParameter('student_image'),
                    $imageName
                );
            }catch(FileException $e){
                
            }
            $student->setImage($imageName);
        }
            $manager = $registry->getManager();
            $manager->persist($student);
            $manager->flush();
            $this->addFlash('success', "Student has been edited successfully !");
            return $this->redirectToRoute('student_index');
        }
        return $this->renderForm('student/edit.html.twig',[
            'studentForm' => $form,
            'student'=>$student,
            'classes' => $classes
        ]);
    }
    /**
     * @IsGranted("ROLE_STAFF")
     */
    #[Route('/student/delete/{id}', name: 'student_delete')]
    public function deleteStudent(ManagerRegistry $registry, $id)
    {
        $classes = $registry->getRepository(Classroom::class)->findAll();
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($student);
            $manager->flush();
            $this->addFlash('error', "Student has been deleted!");
            return $this->redirectToRoute('student_index',[
                'classes' => $classes
            ]);
    }
    #[Route('/student/detail/{id}', name: 'student_detail')]
    public function studentDetail(ManagerRegistry $registry, $id)
    {
        $classes = $registry->getRepository(Classroom::class)->findAll();
        $student = $registry->getRepository(Student::class)->find($id);
        if ($student == null) {
            $this->addFlash('error', 'Student not found !');
            return $this->redirectToRoute('student_index');
        } else { //$student != null
            return $this->render(
                'student/detail.html.twig',
                [
                    'student' => $student,
                    'classes' => $classes
                ]
            );
        }
    }
    #[Route('/search', name:'student_search')]
    public function search(ManagerRegistry $registry, Request $request, StudentRepository $studentRepository){
        $classes = $registry->getRepository(Classroom::class)->findAll();
        $keyword = $request->get('name');
        $students = $studentRepository->search($keyword);
        return $this->render("student/index.html.twig",[
            'students' => $students,
            'classes' => $classes
        ]);
    }
    #[Route('/asc', name: 'student_asc')]
   public function sortAsc(ManagerRegistry $registry, StudentRepository $studentRepository) {
    $classes = $registry->getRepository(Classroom::class)->findAll();
       $students = $studentRepository->sortStudentAsc();
       return $this->render("student/index.html.twig",
                            [
                                'students' => $students,
                                'classes' => $classes
                            ]);
   }

   #[Route('/desc', name: 'student_desc')]
   public function sortDesc(ManagerRegistry $registry, StudentRepository $studentRepository) {
    $classes = $registry->getRepository(Classroom::class)->findAll();
       $students = $studentRepository->sortStudentDesc();
       return $this->render("student/index.html.twig",
                            [
                                'students' => $students,
                                'classes' => $classes
                            ]);
   }
   #[Route('/filter/{id}', name: 'student_filter')]
   public function filter ($id, ManagerRegistry $registry) {
       $classes = $registry->getRepository(Classroom::class)->findAll();
       $class = $registry->getRepository(Classroom::class)->find($id);
       $students = $class->getStudent();
       return $this->render("student/index.html.twig",
                            [
                                    'students' => $students,
                                    'classes' => $classes
                            ]);
   }
}
