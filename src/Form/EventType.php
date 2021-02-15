<?php

namespace App\Form;

use App\Entity\Event;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom',
                'attr' => array(
                    'placeholder' => 'Nom de l\'événement'
                )
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => array(
                    'placeholder' => 'Description'
                )
            ])
            ->add('location', null, [
                'label' => 'Localisation',
                'attr' => array(
                    'placeholder' => 'Localisation de l\'événement'
                )
            ])
            ->add('date', DateType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
                'attr' => array(
                    'placeholder' => 'Date de l\'événement'
                )
            ])
            ->add('nbParticipant', null, [
                'label' => 'Nombre de participants',
                'attr' => array(
                    'placeholder' => 'Nombre de participants'
                )
            ]);

        if (in_array('attr', $options)) {
            if ($options['attr']) {
                if (in_array('image', $options['attr'])) {
                    if ($options['attr']['image']) {
                        $builder->add('imageFileName', FileType::class, [
                            'required' => false,
                            'label' => 'image de fond de l\'évenement',
                            'constraints' => [
                                new File([
                                    'maxSize' => '1024k',
                                    'mimeTypes' => [
                                        'image/jpeg',
                                        'image/png',
                                    ],
                                    'mimeTypesMessage' => 'Please upload a valid image',
                                ])
                            ]
                        ]);
                    }
                }
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
