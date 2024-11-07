<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'label.firstname'
            ])
            ->add('lastname', TextType::class, [
                'label' => 'label.lastname'
            ])
            ->add('email', EmailType::class, [
                'label' => 'label.email'
            ])
            ->add('grade', TextType::class, [
                'label' => 'label.grade.label',
                'help' => 'label.grade.help',
                'required' => false
            ]);
    }
}