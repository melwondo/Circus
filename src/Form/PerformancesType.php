<?php

namespace App\Form;

use App\Entity\Performances;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PerformancesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('contenu')
            ->add('logo', FileType::class, [
                'label' => 'Image (jpg, png)',
                'required' => false,
                'mapped' => false,
                'data_class' => null,])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Performances::class,
        ]);
    }
}
