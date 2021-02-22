<?php

namespace App\Form;

use App\Entity\Settings;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('language', ChoiceType::class, [
                'choices'  => [
                    'settings.locale.fr' => 'fr_FR',
                    'settings.locale.en' => 'en_US'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Settings::class,
        ]);
    }
}
