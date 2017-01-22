<?php

namespace Wanasni\VehiculeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Wanasni\VehiculeBundle\Entity\Couleur;

class LoadCouleurData implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {

        $arr_color= array(
            '000000'=>'noir',
            '0000FF'=>'bleu',
            '008040'=>'vert foncé',
            '00FF00'=>'vert',
            '336699'=>'bleu foncé',
            '808080'=>'gris foncé',
            '8F13B9'=>'violet',
            '996633'=>'marron',
            '9B0004'=>'bordeaux',
            'B7FDB5'=>'vert clair',
            'BBBBFD'=>'bleu clair',
            'C0C0C0'=>'gris clair',
            'DDC2FE'=>'mauve',
            'FF0000'=>'rouge',
            'FF04FF'=>'rose',
            'FFAD5B'=>'orange',
            'FFFF80'=>'jaune',
            'FFFFFF'=>'blanc',
            'A0A0A0'=>'gris métallisé',
            '988800'=>'doré',
            'F5F5DC'=>'beige',
        );

        foreach ($arr_color as $key => $value){
            $color= new Couleur();
            $color->setId($key);
            $color->setNom($value);

            $manager->persist($color);
        }

        $manager->flush();


    }


}