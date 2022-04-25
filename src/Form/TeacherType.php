<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\Teacher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TeacherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label' => 'Teacher Name',
                'required' => true,
                'attr' =>[
                    'minlength' => 5,
                    'maxlength' => 30
                ]
            ])
            ->add('age', NumberType::class,[
                'label' => 'Age',
                'required'=> true,
            ])
            ->add('phone', TextType::class,[
                'label' => 'Number Phone',
                'required' => true,
            ])
            ->add('address', TextType::class,[
                'label' => 'Address',
                'required' => true
            ])
            ->add('image', TextType::class,[
                'label' => 'Avatar',
                'required' => true
            ])
            ->add('courses', EntityType::class,[
                'label' => 'Course',
                'required' => false,
                'class' => Course::class,
                'choice_label' => 'name',
                'multiple' => true,  
                'expanded' => false
            ])        
            ->add('save', SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Teacher::class,
        ]);
    }
}
