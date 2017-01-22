<?php

namespace Wanasni\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('minibio')
            ->add('gender', 'choice', array(
                'choices' => array('homme' => 'homme', 'femme' => 'femme'),

            ))
        ;
    }


    public function getParent()
    {
        //return 'FOS\UserBundle\Form\Type\RegistrationFormType';

        // Or for Symfony < 2.8
        return 'fos_user_registration';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wanasni_user_registration';
    }
}
