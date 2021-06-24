<?php

namespace App\Form;

use App\Entity\Adventure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AdventureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('state', ChoiceType::class, [
                'label' => 'Etat de l\'aventure',
                'choices' => [
                    'GRATUIT' => 'GRATUIT',
                    'PAYANT' => 'PAYANT',
                    'PRIVE' => 'PRIVE',],
            ])
            ->add('code_adv', TextType::class, [
                'label' => 'Code aventure',
                'data' => 'ADV_',
                'required' => true,
            ])        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adventure::class,
        ]);
    }
}
