<?php

namespace App\Controller;

use App\Entity\Student;
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
        $students = $registry->getRepository(Student::class)->findAll();
        return $this->render('student/index.html.twig', [
            'students' => $students,
        ]);
    }
    /**
     * @IsGranted("ROLE_STAFF")
     */
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
            'studentForm' => $form
        ]);
    }
    /**
     * @IsGranted("ROLE_STAFF")
     */
    #[Route('/student/edit/{id}', name: 'student_edit')]
    public function editStudent(ManagerRegistry $registry, Request $request, $id){
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
            'student'=>$student
        ]);
    }
    /**
     * @IsGranted("ROLE_STAFF")
     */
    #[Route('/student/delete/{id}', name: 'student_delete')]
    public function deleteStudent($id)
    {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($student);
            $manager->flush();
            $this->addFlash('error', "Student has been deleted!");
            return $this->redirectToRoute('student_index');
    }
    #[Route('/student/detail/{id}', name: 'student_detail')]
    public function studentDetail(ManagerRegistry $registry, $id)
    {
        $student = $registry->getRepository(Student::class)->find($id);
        if ($student == null) {
            $this->addFlash('error', 'Student not found !');
            return $this->redirectToRoute('student_index');
        } else { //$student != null
            return $this->render(
                'student/detail.html.twig',
                [
                    'student' => $student
                ]
            );
        }
    }
    #[Route('/search', name:'student_search')]
    public function search(Request $request, StudentRepository $studentRepository){
        $keyword = $request->get('name');
        $students = $studentRepository->search($keyword);
        return $this->render("student/index.html.twig",[
            'students' => $students
        ]);
    }
    #[Route('/asc', name: 'student_asc')]
   public function sortAsc(StudentRepository $studentRepository) {
       $students = $studentRepository->sortStudentAsc();
       return $this->render("student/index.html.twig",
                            [
                                'students' => $students
                            ]);
   }

   #[Route('/desc', name: 'student_desc')]
   public function sortDesc(StudentRepository $studentRepository) {
       $students = $studentRepository->sortStudentDesc();
       return $this->render("student/index.html.twig",
                            [
                                'students' => $students
                            ]);
   }
}
