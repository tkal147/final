<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\Classes;
use App\Entity\Student;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'Name',
                TextType::class,
                [
                    'label' => "Student Name",
                    'required' => true
                ]
            )
            ->add(
                'phone',
                TextType::class,
                [
                    'label' => "Phone",
                    'required' => true
                ]
            )
            ->add(
                'avatar',
                FileType::class,
                [
                    'label' => "Student Avt",
                    'data_class' => null,
                    'required' => is_null($builder->getData()->getAvatar())
                ]
            )
            ->add(
                'classes',
                EntityType::class,
                [
                    'label' => "Class",
                    'class' => Classes::class,
                    'choice_label' => "name",   //show Author name in drop-down list
                    'multiple' => false,        //true: select many, false: select one
                    'expanded' => false         //true: checkbox   , false: drop-down list
                ]
            )
            ->add(
                'course',
                EntityType::class,
                [
                    'label' => "Course",
                    'class' => Course::class,
                    'choice_label' => "name",   //show Author name in drop-down list
                    'multiple' => false,        //true: select many, false: select one
                    'expanded' => false         //true: checkbox   , false: drop-down list
                ]
            );;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
