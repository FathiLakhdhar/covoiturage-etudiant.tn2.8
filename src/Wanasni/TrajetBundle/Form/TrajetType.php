<?php

namespace Wanasni\TrajetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Wanasni\TrajetBundle\Entity\Trajet;
use Wanasni\TrajetBundle\Entity\WayDate;
use Wanasni\UserBundle\Entity\User;

class TrajetType extends AbstractType
{

    private $user;

    /**
     * TrajetType constructor.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Origine', new PointType(), array(
                'label' => false,
            ))
            ->add('Destination', new PointType(), array(
                'label' => false,
            ))
            ->add('waypoints', 'collection', array(
                'type' => new PointType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label_attr' => array('class'=>'sr-only'),
            ))
            ->add('date_allet_unique', 'date', array(
                //'format'=>'yyyy/MM/dd',
                'widget'=>'single_text',
                'invalid_message'=>'Format invalide',
                'attr' => array('class' => 'text-indent form-control', 'datepicker' => 'date_allet_unique'),
            ))

            ->add('date_retour_unique', 'date', array(
                //'format'=>'yyyy/MM/dd',
                'widget'=>'single_text',
                'invalid_message'=>'Format invalide',
                'attr' => array('class'=>'text-indent form-control','datepicker' => 'date_retour_unique', 'placeholder' => 'JJ/MM/AAAA'),
                'required'=>false

            ))

            ->add('round_trip', 'checkbox', array(
                'attr' => array('class' => 'round_trip'),
                'required'=>false,
            ))


            ->add('Depart_prevu', 'choice', array(
                'choices' => array('Heure exacte' => 'Heure exacte', '+/- 10 minutes' => '+/- 10 minutes', '+/- 20 minutes' => '+/- 20 minutes', '+/- 30 minutes' => '+/- 30 minutes'),
                'attr'=> array('class'=>'form-control'),

            ))

            ->add('heureAller', 'time', array(
                'input' => 'datetime',
                'widget' => 'choice',
            ))

            ->add('heureRetour', 'time', array(
                'input' => 'datetime',
                'widget' => 'choice',
            ))
            ->add('regular_begin_date', 'date', array(
                'widget'=>'single_text',
                'invalid_message'=>'Format invalide',
                'attr' => array('datepicker' => '', 'placeholder' => 'JJ/MM/AAAA'),
            ))
            ->add('regular_end_date', 'date', array(
                'widget'=>'single_text',
                'invalid_message'=>'Format invalide',
                'attr' => array('datepicker' => '', 'placeholder' => 'JJ/MM/AAAA')
            ))

            ->add('datesAller', 'collection', array(
                'type'=>new WayDateType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference'=>false,
                'label'=>false,
            ))

            ->add('datesRetour', 'collection', array(
                'type'=>new WayDateType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference'=>false,
                'label'=>false,
            ))

            ->add('Bagages', 'choice', array(
                'choices' => array(
                    'Petit' => 'Petit bagage',
                    'Moyen' => 'Bagage moyen',
                    'Grand' => 'Grand bagage',
                    'Aucun' => 'Aucun',
                ),
                'data' => 'Petit',
                'multiple' => false,
                'expanded' => true,
            ))

            ->add('Preferences', new PreferencesType())

            ->add('informationsComplementaires', 'textarea', array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => '0..500 caractères',
                    'maxlength' => "500",
                    "rows" => "5",
                ),
                'required'=>false,

            ))
            ->add('totalDistance', 'hidden', array(
                'attr' => array('class' => 'totalDistance'),
                'data' => '0 km',
            ))
            ->add('totalDuration', 'hidden', array(
                'attr' => array('class' => 'totalDuration'),
                'data' => '0 heurs 0 min',
            ))


            ->add('arrPrix','collection',array(
                'type'=>'number',
                'allow_add' => true,
                'allow_delete' => true,
                'label'=>false
            ))
            ->add('nbPlaces', 'number', array(
                'attr' => array('class' => 'form-control text-indent car-place-spinner'),
                'read_only'=>true,
            ))

            ->add('vehicule', 'entity', array(
                'empty_value' => 'Sélectionnez votre véhicule',
                'class' => 'Wanasni\VehiculeBundle\Entity\Vehicule',
                'property' => 'fullNameCar',
                'choices' => $this->user->getVehicules(),
                'by_reference'=>false,
                'attr' => array('class' => 'form-control choices-vehicules'),

            ))
            ->add('cgu', 'checkbox', array(
                'attr' => array('class' => 'iCheck'),
                'label' => 'Je certifie posséder le permis de conduire et une assurance en cours de validité (CGU) *'
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wanasni\TrajetBundle\Entity\Trajet'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wanasni_trajetbundle_trajet';
    }
}
