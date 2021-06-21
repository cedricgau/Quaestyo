<?php

namespace App\Form;

use App\Entity\ExternDatas;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExternDatasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('CA', NumberType::class, [
                'label' => 'Chiffre d\'Affaires'])
            ->add('advert', NumberType::class, [
                'label' => 'Publicité investie'])
            ->add('date_payed', DateType::class, [
                'label' => 'Mois de paiement',
                'days' => ['disabled' => true],
                'format' => 'dd-MM-yyyy',
                'months' => ['data' => date('m')],          
                'years' => range(2021,2099),
                'years' => ['data' => date('Y')],
                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExternDatas::class,
        ]);
    }
}
