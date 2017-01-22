<?php
namespace Wanasni\TrajetBundle\Objet;


use Symfony\Component\Validator\Constraints\Date;

class Search
{

    /**
     * @var string
     */
    private $origine;
    /**
     * @var string
     */
    private $destination;



    /**
     * @var \DateTime
     */
    private $date;

    /**
     * Search constructor.
     */
    public function __construct()
    {
    }



    /**
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param string $destination
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;
    }

    /**
     * @return string
     */
    public function getOrigine()
    {
        return $this->origine;
    }

    /**
     * @param string $origine
     */
    public function setOrigine($origine)
    {
        $this->origine = $origine;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }
}