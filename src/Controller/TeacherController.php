<?php

namespace App\Controller;

use App\Entity\Teacher;
use App\Form\TeacherType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/teacher')]
class TeacherController extends AbstractController
{
    #[Route('/', name: 'teacher_index')]
    public function teacherIndex(ManagerRegistry $registry)
    {
        $teachers = $registry ->getRepository(Teacher::class)->findAll();
        return $this->render('teacher/index.html.twig', [
            
            'teachers' => $teachers
        ]);
    }
    #[Route('/detail/{id}', name:'teacher_detail')]
    public function teacherDetail(ManagerRegistry $registry, $id){
        $teacher = $registry->getRepository(Teacher::class)->find($id);
        if($teacher == null){
            $this -> addFlash('Erorr', 'Teacher Not Found');
            return $this->redirectToRoute('teacher_index');
        }
        return $this->render("teacher/detail.html.twig",
        [
            'teacher' => $teacher
        ]);   
    }
    #[Route('/delete/{id}', name: 'teacher_delete')]
    public function teacherDelete (ManagerRegistry $registry, $id) {
        $teacher = $registry->getRepository(Teacher::class)->find($id);
        if ($teacher == null) {
            $this->addFlash("Error", "teacher not found !");
        }
        //check xem teacher cần xóa có tồn tại tối thiểu 1 book hay không
        //nếu có thì không cho xóa và thông báo lỗi
        else if (count($teacher->getBooks()) >= 1) {
            $this->addFlash("Error", "Can not delete this teacher !");
        }
        else {
            $manager = $registry->getManager();
            $manager->remove($teacher);
            $manager->flush();
            $this->addFlash("Success", "Delete teacher succeed !");
        }
        return $this->redirectToRoute("teacher_index");
    }
    #[Route('/add', name: 'add_teacher')]
    public function teacherAdd(Request $request, ManagerRegistry $registry){
        $teacher = new Teacher;
        $form = $this -> createForm(TeacherType::class, $teacher);
        $form->handleRequest($request);
        if($form-> isSubmitted() && $form -> isValid()){
            $manager = $registry->getManager();
            $manager ->persist($teacher);
            $manager ->flush();
            $this -> addFlash("Success","Add teacher succeed");
            return $this->redirectToRoute('teacher_index');
        }
        return $this ->renderForm('teacher/add.html.twig',
        ['teacherForm'=>$form]);
    }
    #[Route('/edit/{id}', name: 'teacher_edit')]
    public function teacherEdit(Request $request, ManagerRegistry $registry, $id) {
        $teacher = $registry->getRepository(Teacher::class)->find($id);
        $form = $this->createForm(TeacherType::class, $teacher);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $registry->getManager();
            $manager->persist($teacher);
            $manager->flush();
            $this->addFlash("Success", "Edit teacher succeed !");
            return $this->redirectToRoute('teacher_index');
        }
        return $this->renderForm('teacher/edit.html.twig',
                                [
                                    'teacherForm' => $form
                                ]);
    }
}
