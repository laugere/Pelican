<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Tag;
use Doctrine\DBAL\Types\TextType;
use App\Form\DataTransformer\TagsTransformer;
use App\Form\Type\TagsType;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Doctrine\ORM\EntityManagerInterface;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'attr' => array(
                    'placeholder' => 'event.form.name'
                )
            ])
            ->add('description', TextareaType::class, [
                'attr' => array(
                    'placeholder' => 'event.form.description'
                )
            ])
            ->add('location', null, [
                'attr' => array(
                    'placeholder' => 'event.form.location'
                )
            ])
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
                'attr' => array(
                    'placeholder' => 'event.form.date'
                )
            ])
            ->add('endDate', DateTimeType::class, [
                'widget' => 'single_text',
                'attr' => array(
                    'placeholder' => 'event.form.enddate'
                )
            ])
            ->add('nbParticipant', null, [
                'attr' => array(
                    'placeholder' => 'event.form.number'
                )
            ])
            ->add('tags', TagsType::class, [
                'attr' => array(
                    'class' => 'tagin'
                )
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => false,
                'delete_label' => false,
                'download_label' => false,
                'download_uri' => false,
                'image_uri' => false,
                'asset_helper' => false,
                'attr' => array(
                    'id' => 'inputImage',
                    'placeholder' => 'event.form.image',
                    'onchange' => 'loadFile(event)'
                )
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class
        ]);

        $resolver->setDefault('required', false);
    }
}
