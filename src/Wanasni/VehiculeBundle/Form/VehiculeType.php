<?php

namespace Wanasni\VehiculeBundle\Form;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Wanasni\VehiculeBundle\Entity\Marque;
use Wanasni\VehiculeBundle\Entity\ModeleRepository;

class VehiculeType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('marque', 'entity', array(
                'class' => 'WanasniVehiculeBundle:Marque',
                'property' => 'CarBrand',
                'empty_value' => 'Choisissez',
                'multiple' => false,
                'attr' => array('class' => 'form-control marque'),

            ))
            ->add('modele', 'entity', array(
                'class' => 'Wanasni\VehiculeBundle\Entity\Modele',
                'property' => 'carModel',
                'attr' => array('class' => 'form-control modele'),
                'empty_value' => 'Choisissez',
            ))
            ->add('confort', 'choice', array(
                'choices' => array('BASIC' => 'Basique', 'NORMAL' => 'Normal', 'COMFORT' => 'Confort', 'LUXE' => 'Luxe'),
                'attr' => array('class' => 'form-control')
            ))
            ->add('nbrPlaces', 'choice', array(
                'choices' => array(
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                ),
                'attr' => array('class' => 'form-control')
            ))
            ->add('couleur', 'entity', array(
                'class' => 'Wanasni\VehiculeBundle\Entity\Couleur',
                'property' => 'nom',
                'multiple' => false,
                'attr' => array('class' => 'form-control')
            ))
            ->add('immatriculation', 'text', array(
                'attr' => array('class' => 'form-control matricule')
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wanasni\VehiculeBundle\Entity\Vehicule'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wanasni_vehiculebundle_vehicule';
    }
}
