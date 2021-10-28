<?php

namespace App\Form;

use App\Entity\Room;
use App\Entity\Classes;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
            $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => "Room Name",
                    'required' => true
                ]
            )
            ->add(
                'location',
                TextType::class,
                [
                    'label' => "Location",
                    'required' => true
                ]
            )
            ->add(
                'class',
                EntityType::class,
                [
                    'label' => "Class Name",
                    'class' => Classes::class,
                    'choice_label' => "name",
                    'multiple' => false,
                    'expanded' => false
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Room::class,
        ]);
    }
}
