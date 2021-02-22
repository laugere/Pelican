<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', null, ['label' => false, 'attr' => array(
                'id' => 'inputEmail',
                'placeholder' => 'security.register.email'
            )])
            ->add('city', null, ['label' => false, 'attr' => array(
                'id' => 'inputCity',
                'placeholder' => 'security.register.city'
            )])
            ->add('pseudo', null, ['label' => false, 'attr' => array(
                'id' => 'inputPseudo',
                'placeholder' => 'security.register.pseudo'
            )])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => false,
                'attr' => array(
                    'id' => 'inputPassword',
                    'placeholder' => 'security.register.password'
                ),
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'security.register.passwordspan',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => true,
                'allow_delete' => false,
                'delete_label' => false,
                'download_label' => false,
                'download_uri' => false,
                'image_uri' => true,
                'asset_helper' => false,
                'attr' => array(
                    'id' => 'inputImage',
                    'placeholder' => 'security.register.image',
                    'onchange' => 'loadFile(event)'
                )
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'security.register.agree',
                'attr' => array(
                    'id' => 'inputAgreeTerms'
                ),
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'security.register.agreespan',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
