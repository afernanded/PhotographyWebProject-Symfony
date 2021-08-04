<?php

namespace App\Form;

use App\Entity\Mensajes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class MensajesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, [
                'label' => 'First Name',
            ])
            ->add('apellidos', TextType::class, [
                'label' => 'Last Name',
                'required' => false,
            ])
            ->add('asunto', TextType::class, [
                'label' => 'Subject',
            ])
            ->add('email', EmailType::class, [
                'constraints' => new Assert\Email(),
            ])
            ->add('texto', TextareaType::class,[
                'label' => 'Message',
            ])
            ->add('enviar', SubmitType::class, [
                'attr' => ['class' => 'pull-right btn btn-lg sr-button'],
                'label' => 'SEND',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Mensajes::class,
        ]);
    }
}
