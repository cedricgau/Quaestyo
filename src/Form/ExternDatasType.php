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
                'label' => 'Chiffre d\'Affaires',
                'data' => 0,                
                'required' => true,])
            ->add('advert', NumberType::class, [
                'label' => 'Publicité investie',
                'data' => 0,                
                'required' => true,])
            ->add('date_payed', DateType::class, [
                'label' => 'Mise à jour mensuelle concernée',
                'days' => ['disabled' => true],
                'format' => 'dd-MM-yyyy',                       
                'years' => range(2021,2099),               
                ])
            ->add('download', NumberType::class, [
                    'label' => 'Téléchargements',
                    'data' => 0,                
                    'required' => true,])
            ->add('uninstall', NumberType::class, [
                        'label' => 'Désinstallations',
                        'data' => 0,                
                        'required' => true,])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExternDatas::class,
        ]);
    }
}
