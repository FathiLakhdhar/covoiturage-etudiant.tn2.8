<?php

namespace Wanasni\VehiculeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

/**
 * Marque
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wanasni\VehiculeBundle\Entity\MarqueRepository")
 */
class Marque
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=30)
     * @ORM\Id
     * @Groups({"list", "details"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="CarBrand", type="string", length=30)
     * @Groups({"list", "details"})
     */
    private $carBrand;

    /**
     * @var Modele
     * @ORM\OneToMany(targetEntity="Wanasni\VehiculeBundle\Entity\Modele", mappedBy="marque", cascade={"remove","persist"})
     * @Groups({"list", "details"})
     */
    private $modeles;


    /**
     * @ORM\OneToMany(targetEntity="Wanasni\VehiculeBundle\Entity\Vehicule", mappedBy="marque")
     * @Groups({"details"})
     */
    private $vehicules;

    /**
     * Set id
     *
     * @param string $id
     * @return Marque
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
     * Set carBrand
     *
     * @param string $carBrand
     * @return Marque
     */
    public function setCarBrand($carBrand)
    {
        $this->carBrand = $carBrand;
    
        return $this;
    }

    /**
     * Get carBrand
     *
     * @return string 
     */
    public function getCarBrand()
    {
        return $this->carBrand;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->modeles = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add modeles
     *
     * @param \Wanasni\VehiculeBundle\Entity\Modele $modeles
     * @return Marque
     */
    public function addModele(\Wanasni\VehiculeBundle\Entity\Modele $modeles)
    {
        $this->modeles[] = $modeles;
    
        return $this;
    }

    /**
     * Remove modeles
     *
     * @param \Wanasni\VehiculeBundle\Entity\Modele $modeles
     */
    public function removeModele(\Wanasni\VehiculeBundle\Entity\Modele $modeles)
    {
        $this->modeles->removeElement($modeles);
    }

    /**
     * Get modeles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModeles()
    {
        return $this->modeles;
    }



    /**
     * Add vehicules
     *
     * @param \Wanasni\VehiculeBundle\Entity\Vehicule $vehicules
     * @return Marque
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