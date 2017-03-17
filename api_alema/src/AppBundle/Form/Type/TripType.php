<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class);
        $builder->add('dateStart', DateType::class);
        $builder->add('dateEnd', DateType::class);
        $builder->add('location', TextType::class);
        $builder->add('numberPlace', NumberType::class);
        $builder->add('placeStart', TextType::class);
        $builder->add('placeEnd', TextType::class);
        $builder->add('urlPicture', UrlType::class);
        $builder->add('description', TextareaType::class);
        $builder->add('save', SubmitType::class, array('label' => 'Ajouter'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Trip',
            'csrf_protection' => false
        ]);
    }
}