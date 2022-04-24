<?php

namespace App\Controller;

use App\Entity\Teacher;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
}
