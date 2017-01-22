<?php

namespace Wanasni\TrajetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PreferencesType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fumeurs','checkbox',array(
                'attr'=>array('class'=>'iCheck'),
                'required'=>false
            ))
            ->add('animaux','checkbox',array(
                'attr'=>array('class'=>'iCheck'),
                'required'=>false
            ))
            ->add('animauxEnCage','checkbox',array(
                'attr'=>array('class'=>'iCheck'),
                'required'=>false
            ))
            ->add('musique','checkbox',array(
                'attr'=>array('class'=>'iCheck'),
                'required'=>false
            ))
            ->add('detours','checkbox',array(
                'attr'=>array('class'=>'iCheck'),
                'required'=>false
            ))
            ->add('pauseCafe','checkbox',array(
                'attr'=>array('class'=>'iCheck'),
                'required'=>false
            ))
            ->add('nourriture','checkbox',array(
                'attr'=>array('class'=>'iCheck'),
                'required'=>false
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wanasni\TrajetBundle\Entity\Preferences'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wanasni_trajetbundle_preferences';
    }
}
