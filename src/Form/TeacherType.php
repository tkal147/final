<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\Teacher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class TeacherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => "Teacher Name",
                    'required' => true
                ]
            )
            ->add(
                'age',
                IntegerType::class,
                [
                    'label' => "Age",
                    'required' => true
                ]
            )
            ->add(
                'Location',
                TextType::class,
                [
                    'label' => "Age",
                    'required' => true
                ]
            )
            ->add(
                'courses',
                EntityType::class,
                [
                    'label' => "Class",
                    'class' => Course::class,
                    'choice_label' => "name",   //show Author name in drop-down list
                    'multiple' => true,        //true: select many, false: select one
                    'expanded' => true         //true: checkbox   , false: drop-down list
                ]
            )
            ->add(
                'Avatar',
                FileType::class,
                [
                    'label' => "Student Avt",
                    'data_class' => null,
                    'required' => is_null($builder->getData()->getAvatar())
                ]
            )
            ;
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Teacher::class,
        ]);
    }
}
