<?php

namespace App\Form;

use App\Entity\Drop;
use Symfony\Component\Form\AbstractType;
use Symfony\UX\Dropzone\Form\DropzoneType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DropType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', DropzoneType::class, [                
                'mapped' => false,
                'required' => true,               
                
                
            ])         
           
            ;
            
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //'data_class' => Drop::class,            
        ]);
    }
}
