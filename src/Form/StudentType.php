<?php

namespace App\Form;

use App\Entity\Student;
use App\Entity\Classroom;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [

            ])
            ->add('address', TextType::class, [

            ])
            ->add('image', FileType::class, [
                'required' => false,
                'data_class' => null,
                'required' => is_null($builder->getdata()->getImage())
            ])
            ->add('birthday', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('classrooms', EntityType::class, [
                'class' => Classroom::class,
                'choice_label' => "name",
                'multiple' => true,
                'expanded' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
