<?php

namespace Wanasni\AvisBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;
use Wanasni\UserBundle\Entity\User;

/**
 * Avis
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wanasni\AvisBundle\Entity\AvisRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Avis
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=20)
     * @Assert\Choice(choices={"Passager","Conducteur"}, message="Choose a valid role.")
     */
    private $role;





    /**
     * @var string
     *
     * @ORM\Column(name="driving", type="string", length=50, nullable=true)
     * @Assert\Choice(choices={"Conduite agréable", "Peut mieux faire", "À éviter"}, message="Choose a valid driving.")
     */
    private $driving;

    /**
     * @var string
     * @ORM\Column(name="global", type="string", length=20)
     * @Assert\Choice(choices={"Parfait", "Très bien", "Bien", "Décevant", "À éviter"})
     */
    private $global;

    /**
     * @var string
     * @ORM\Column(name="comment", type="text", length=500)
     */
    private $comment;


    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     *
     * @var User
     * @ORM\ManyToOne(targetEntity="Wanasni\UserBundle\Entity\User", inversedBy="avis_given")
     */
    private $emetteur;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Wanasni\UserBundle\Entity\User", inversedBy="avis_received")
     */
    private $recepteur;





    /**
     * Get id
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Avis
     */
    public function setRole($role)
    {
        $this->role = $role;
    
        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set driving
     *
     * @param string $driving
     * @return Avis
     */
    public function setDriving($driving)
    {
        $this->driving = $driving;
    
        return $this;
    }

    /**
     * Get driving
     *
     * @return string 
     */
    public function getDriving()
    {
        return $this->driving;
    }

    /**
     * Set global
     *
     * @param string $global
     * @return Avis
     */
    public function setGlobal($global)
    {
        $this->global = $global;
    
        return $this;
    }

    /**
     * Get global
     *
     * @return string 
     */
    public function getGlobal()
    {
        return $this->global;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return Avis
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set emetteur
     *
     * @param \Wanasni\UserBundle\Entity\User $emetteur
     * @return Avis
     */
    public function setEmetteur(\Wanasni\UserBundle\Entity\User $emetteur = null)
    {
        $this->emetteur = $emetteur;
        return $this;
    }

    /**
     * Get emetteur
     *
     * @return \Wanasni\UserBundle\Entity\User 
     */
    public function getEmetteur()
    {
        return $this->emetteur;
    }

    /**
     * Set recepteur
     *
     * @param \Wanasni\UserBundle\Entity\User $recepteur
     * @return Avis
     */
    public function setRecepteur(\Wanasni\UserBundle\Entity\User $recepteur = null)
    {
        $this->recepteur = $recepteur;
    
        return $this;
    }

    /**
     * Get recepteur
     *
     * @return \Wanasni\UserBundle\Entity\User 
     */
    public function getRecepteur()
    {
        return $this->recepteur;
    }









    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     * @return Avis
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;
    
        return $this;
    }

    /**
     * Get createAt
     *
     * @return \DateTime 
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }



    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->createAt= new \DateTime();
    }


}