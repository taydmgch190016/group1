<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label'=> 'Course Name',
                'required' => true,
                'attr' =>[
                    'minlength' => 5,
                    'maxlength' => 30
                ]
            ])
            ->add('description', TextType::class,[
                'label' => 'Course Description',
                'Required' => true,
            ])
            ->add('coursefee', MoneyType::class,[
                'label' => 'Course Fee',
                'required' => true,

            ])
            ->add('teacher', EntityType::class,[
                'label' => "Teacher",
                'required' => true,
                'class' => Teacher::class,
                'choice_label' => 'name',
                'multiple' => false,  
                'expanded' => false
                //multiple = true: ManyToMany, OneToMany => có thể chọn nhiều item
                //multiple = false: OneToOne, ManyToOne => chỉ được chọn 1 item
                //expanded = true: hiển thị danh sách mở rộng
                //expanded = false: hiển thị danh sách rút gọn
            ])
            ->add('save', SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
