<?php

namespace App\Form;

use App\Entity\Player;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class MajPlayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id_player', TextType::class, [
                'label' => 'ID_ Player',
                'required' => true])
            ->add('currency3', IntegerType::class, [
                'label' => 'Currency 3',
                'data' => 0,
                'empty_data' => 0,
                'required' => true,                
            ])
            ->add('currency4', IntegerType::class, [
                'label' => 'Currency 4',
                'data' => 0,
                'empty_data' => 0,
                'required' => true,                      
            ])
            ->add('currency5', IntegerType::class, [
                'label' => 'Currency 5',
                'data' => 0,
                'empty_data' => 0,
                'required' => true,                             
            ])
            ->add('currency6', IntegerType::class, [
                    'label' => 'Currency 6',
                    'data' => 0,
                    'empty_data' => 0,
                    'required' => true,                             
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Player::class,
        ]);
    }
}
