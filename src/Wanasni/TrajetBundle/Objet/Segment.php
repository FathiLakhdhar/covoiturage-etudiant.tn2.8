<?php
/**
 * Created by PhpStorm.
 * User: Toshiba
 * Date: 16-04-2016
 * Time: 14:20
 */

namespace Wanasni\TrajetBundle\Objet;


class Segment
{
    private $start;
    private $end;
    private $prix;

    /**
     * Segment constructor.
     * @param $start
     * @param $end
     * @param $prix
     */
    public function __construct($start, $end, $prix)
    {
        $this->start = $start;
        $this->end = $end;
        $this->prix = $prix;
    }


    /**
     * @return mixed
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param mixed $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * @return mixed
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param mixed $end
     */
    public function setEnd($end)
    {
        $this->end = $end;
    }

    /**
     * @return mixed
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * @param mixed $prix
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;
    }
}