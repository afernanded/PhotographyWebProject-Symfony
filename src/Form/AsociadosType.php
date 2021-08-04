<?php

namespace App\Form;

use App\Entity\Asociados;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AsociadosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
            $builder
                ->add('nombre', TextType::class)
                ->add('logo', FileType::class, [
                    'label' => 'Logo',
                    'mapped' => false,
                    'required' => false,
                    'constraints' => [
                        new File([
                            'maxSize' => '1024k',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                                'image/gif',
                            ],
                            'mimeTypesMessage' => 'Por favor, suba una imagen valida',
                        ])
                    ],
                ])
                ->add('descripcion', TextareaType::class, [
                    'label' => 'Descripción'])
                ->add('enviar', SubmitType::class, [
                    'attr' => ['class' => 'pull-right btn btn-lg sr-button'],
                    'label' => 'ENVIAR',
                ]);
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Asociados::class,
        ]);
    }
}
