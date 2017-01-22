<?php

namespace Wanasni\AvisBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;


class AvisType extends AbstractType
{


    private $securityContext;

    public function __construct(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;
    }



    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $user = $this->securityContext->getToken()->getUser();
        if (!$user) {
            throw new \LogicException(
                'The AvisFormType cannot be used without an authenticated user!'
            );
        }

        $builder

            ->add('recepteur', 'entity', array(
                'class'=>'Wanasni\UserBundle\Entity\User',
                'empty_value' => 'search ...',
                /*
                'query_builder'=> function (EntityRepository $er) use ($user) {

                    return $er->createQueryBuilder('u')
                                ->where('u.username LIKE :login')
                        ->setParameter('login', '')
                        ->andWhere('u.id != :currentUser')
                        ->setParameter('currentUser',$user->getId())
                    ;

                }*/

            ))


            ->add('role', 'choice', array(
                'choices'=>array(
                    'Passager'=>'Passager',
                    'Conducteur'=>'Conducteur'
                ),
                'multiple'=>false,
                'expanded'=>true,
            ))


            ->add('driving', 'choice', array(
                'choices'=>array(
                    'Conduite agréable'=>'Conduite agréable',
                    'Peut mieux faire'=>'Peut mieux faire',
                    'À éviter'=>'À éviter',
                ),
                'required' => false,
                'multiple'=>false,
                'expanded'=>true,

            ))
            ->add('global', 'choice', array(
                'choices'=>array(
                    'Parfait'=>'Parfait',
                    'Très bien'=>'Très bien',
                    'Bien'=>'Bien',
                    'Décevant'=>'Décevant',
                    'À éviter'=>'À éviter',
                ),
                'multiple'=>false,
                'expanded'=>true
            ))
            ->add('comment', 'textarea', array(
                'attr'=>array(
                    'placeholder'=>'Comment s\'est passé le voyage (ponctualité, ambiance, etc.) ? Est-ce que vous recommandez ce membre à la communauté ?'
                )
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wanasni\AvisBundle\Entity\Avis'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wanasni_avisbundle_avis';
    }
}
