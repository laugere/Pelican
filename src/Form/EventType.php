<?php

namespace App\Form;

use App\Entity\Event;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Nom', 'attr' => array(
                'placeholder' => 'Nom de l\'événement'
            )])
            ->add('description', TextareaType::class, ['label' => 'Description', 'attr' => array(
                'placeholder' => 'Description'
            )])
            ->add('location', null, ['label' => 'Localisation', 'attr' => array(
                'placeholder' => 'Localisation de l\'événement'
            )])
            ->add('date', DateType::class, ['label' => 'Date', 'widget' => 'single_text', 'attr' => array(
                'placeholder' => 'Date de l\'événement'
            )])
            ->add('nbParticipant', null, ['label' => 'Nombre de participants', 'attr' => array(
                'placeholder' => 'Nombre de participants'
            )]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
