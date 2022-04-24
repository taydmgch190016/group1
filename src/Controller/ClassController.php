<?php

namespace App\Controller;

use App\Entity\Classroom;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClassController extends AbstractController
{
    #[Route('/class', name: 'class_index')]
    public function classIndex(ManagerRegistry $registry): Response
    {
        $classes = $registry->getRepository(Classroom::class)->findAll();
        return $this->render('class/index.html.twig', [
            'classes' => $classes,
        ]);
    }
}
