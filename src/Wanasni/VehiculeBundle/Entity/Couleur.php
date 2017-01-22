<?php

namespace Wanasni\VehiculeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;


/**
 * Couleur
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wanasni\VehiculeBundle\Entity\CouleurRepository")
 */
class Couleur
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=7)
     * @ORM\Id
     * @Groups({"list", "details"})
     *
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=20)
     * @Groups({"list", "details"})
     */
    private $nom;


    /**
     * @ORM\OneToMany(targetEntity="Wanasni\VehiculeBundle\Entity\Vehicule", mappedBy="couleur")
     * @Groups({"details"})
     */
    private $vehicules;



    /**
     * Set id
     *
     * @param string $id
     * @return Couleur
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Couleur
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }



    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->vehicules = new \Doctrine\Common\Collections\ArrayCollection();
    }
    


    /**
     * Add vehicules
     *
     * @param \Wanasni\VehiculeBundle\Entity\Vehicule $vehicules
     * @return Couleur
     */
    public function addVehicule(\Wanasni\VehiculeBundle\Entity\Vehicule $vehicules)
    {
        $this->vehicules[] = $vehicules;
    
        return $this;
    }

    /**
     * Remove vehicules
     *
     * @param \Wanasni\VehiculeBundle\Entity\Vehicule $vehicules
     */
    public function removeVehicule(\Wanasni\VehiculeBundle\Entity\Vehicule $vehicules)
    {
        $this->vehicules->removeElement($vehicules);
    }

    /**
     * Get vehicules
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVehicules()
    {
        return $this->vehicules;
    }
}