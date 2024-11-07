<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AuctionType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('title', TextType::class, [
                'label' => 'label.title'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'label.description'
            ])
            ->add('image', VichImageType::class, [
                'label' => 'label.image',
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/jpg'
                        ]
                    ])
                ]
            ])
            ->add('startBid', MoneyType::class, [
                'label' => 'label.start_bid.label',
                'help' => 'label.start_bid.help',
            ])
            ->add('minimumBid', MoneyType::class, [
                'label' => 'label.minimum_bid.label',
                'help' => 'label.minimum_bid.help',
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'label.quantity.label',
                'help' => 'label.quantity.help',
            ])
            ->add('start', DateTimeType::class, [
                'label' => 'label.start',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text'
            ])
            ->add('end', DateTimeType::class, [
                'label' => 'label.end',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text'
            ]);
    }
}