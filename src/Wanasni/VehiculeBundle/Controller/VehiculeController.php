<?php

namespace Wanasni\VehiculeBundle\Controller;

use Doctrine\ORM\EntityNotFoundException;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Wanasni\VehiculeBundle\Entity\Couleur;
use Wanasni\VehiculeBundle\Entity\Marque;
use Wanasni\VehiculeBundle\Entity\Modele;
use Wanasni\VehiculeBundle\Entity\Vehicule;
use Wanasni\VehiculeBundle\Form\VehiculeType;


class VehiculeController extends Controller
{


    /**
     * @Route("/vehicule", name="cars-show" )
     */
    public function ShowCarAction()
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw new AccessDeniedException();
        }

        $vehicules= $this->getUser()->getVehicules();

        return $this->render(':Vehicule:voir_vehicules.html.twig',array(
            'vehicules'=>$vehicules
        ));
    }


    /**
     * @Route("/vehicule-ajouter", name="car-add" )
     */
    public function AddCarAction()
    {

        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw new AccessDeniedException();
        }

        $car=new Vehicule();
        $form=$this->createForm(new VehiculeType(),$car);
        // On récupère la requête
        $request = $this->getRequest();


        // On vérifie qu'elle est de type POST
        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $car->setUser($this->getUser());
                $em->persist($car);
                $em->flush();

                // On définit un message flash
                $this->get('session')->getFlashBag()->add('info', 'Vehicule bien ajouté');

                return $this->redirect($this->generateUrl('car-add'));

            }

        }

        return $this->render(':Vehicule:car_add.html.twig', array(
            'form'=>$form->createView(),
        ));

    }


    /**
     * @Route(path="/vehicule-supprimer/{id}", name="car_remove")
     */
    public function RemoveCarAction($id)
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw new AccessDeniedException();
        }


        $em=$this->getDoctrine()->getManager();
        $rep= $em->getRepository('WanasniVehiculeBundle:Vehicule');

        $car=$rep->findOneBy(array('id'=>$id,'user'=>$this->getUser()));

        if(!$car){
            throw new EntityNotFoundException();
        }

        if($car->getTrajets()->count()==0){
            $em->remove($car);
            $em->flush();
            $this->get('session')->getFlashBag()->set('success','Véhicule bien supprimer');
        }else{

            $this->get('session')->getFlashBag()->set('info','Véhicule n\'est pas supprimer : cette véhicule utilisé dans un trajet');
        }

        return $this->redirect($this->generateUrl('cars-show'));


    }





    /**
     * @Route(path="/vehicule-modifier/{id}", name="car_edit")
     */
    public function EditCarAction($id,  Request $request)
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw new AccessDeniedException();
        }


        $em=$this->getDoctrine()->getManager();
        $rep= $em->getRepository('WanasniVehiculeBundle:Vehicule');

        $car=$rep->findOneBy(array('id'=>$id,'user'=>$this->getUser()));

        if(!$car){
            throw new EntityNotFoundException();
        }


        $form= $this->createForm(new VehiculeType() , $car);


        if($request->getMethod()=="POST"){
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $em->persist($car);
                $em->flush();
                $this->get('session')->getFlashBag()->set('success','Véhicule bien Modifier');
                return $this->redirect($this->generateUrl('cars-show'));
            }
        }


        return $this->render(':Vehicule:car_edit.html.twig',array(
            'form'=>$form->createView(),
            'id'=>$id
        ));
    }

    /**
     * @Route("/car-models/{brand}", name="car-models" )
     */
    public function CarModelsAction($brand)
    {
        $arr_model = array();
        $em = $this->getDoctrine()->getManager();
        $repositoryMarque = $em->getRepository('WanasniVehiculeBundle:Marque');

        $marque = $repositoryMarque->findOneBy(array('carBrand' => strtoupper($brand)));

        if ($marque) {
        $modeles = $marque->getModeles();

            foreach ($modeles as $model) {
                $arr_model[] = array($model->getId() => $model->getCarModel()) ;
            }
        }
        return new JsonResponse($arr_model);
    }


    /**
     *
     */


    /**
     * @Route(path="/api-cars-all", name="api-car-all")
     */
    public function CarsAction()
    {
        $response= new Response();
        $response->headers->set('Content-Type', 'application/json');
        $rep= $this->getDoctrine()->getRepository("WanasniVehiculeBundle:Marque");
        $marq= $rep->findAll();

        $group= SerializationContext::create()->setGroups(array("list"));
        $serializer= $this->get("serializer");
        $jsonMarq=$serializer->serialize($marq,"json",$group);

        return $response->setContent($jsonMarq);
    }


    /**
     * @Route(path="/api-car-colors", name="api-car-colors")
     */
    public function CarColorsAction()
    {
        $response= new Response();
        $response->headers->set('Content-Type', 'application/json');
        $repc= $this->getDoctrine()->getRepository("WanasniVehiculeBundle:Couleur");
        $colors= $repc->findAll();

        $group= SerializationContext::create()->setGroups(array("list"));
        $serializer= $this->get("serializer");
        $jsonColor=$serializer->serialize($colors,"json",$group);

        return $response->setContent($jsonColor);
    }



    /**
     * @Route(path="/api-car-add", name="api-car-add")
     *
     */
    public function ApiAddAction(Request $request)
    {
        $response=new JsonResponse();
        $marq=$request->get('marque');
        $model=$request->get('modele');
        $cofort=$request->get('confort');
        $nbplaces=$request->get('nbrplaces');
        $color=$request->get('color');
        $immatriculation=$request->get('immatriculation');

        $rep=$this->getDoctrine()->getRepository("WanasniVehiculeBundle:Modele");
        $repcolor=$this->getDoctrine()->getRepository("WanasniVehiculeBundle:Couleur");
        $findmodel=new Modele();
        $findcolor=new Couleur();


        $valid=true;

        $errors=array();

        if(strlen($model)==0){
            $valid=false;
            $errors[]=array(
                "champ"=> "modele",
                "error"=> "choise modele"
            );
        }else{
            $findmodel= $rep->findOneBy(array("id"=>$model));
            if(!$findmodel){
                $valid=false;
                $errors[]=array(
                    "champ"=> "modele",
                    "error"=> "modele not exist"
                );
            }
        }

        if(strlen($color)==0){
            $valid=false;
            $errors[]=array(
                "champ"=> "color",
                "error"=> "choise color"
            );
        }else{
            $findcolor=$repcolor->findOneBy(array("id"=>$color));
            if(!$findcolor){
                $valid=false;
                $errors[]=array(
                    "champ"=> "color",
                    "error"=> "color not exist"
                );
            }
        }

        if(!in_array($cofort,array("BASIC","NORMAL","COMFORT","LUXE"))){
            $valid=false;
            $errors[]=array(
                "champ"=> "confort",
                "error"=> "Champ confort doit etre (BASIC,NORMAL, COMFORT, LUXE)"
            );
        }
        if($nbplaces<0 || $nbplaces>9){
            $valid=false;
            $errors[]=array(
                "champ"=> "nbrplaces",
                "error"=> "Champ nbpalces doit etre nombre entre[0..9]"
            );
        }
        if(strlen($immatriculation)==0){
            $valid=false;
            $errors[]=array(
                "champ"=> "immatriculation",
                "error"=> "Champ immatriculation not null"
            );
        }


        if($valid && $this->getUser()){

            $v= new Vehicule();
            $v->setUser($this->getUser());
            $v->setCouleur($findcolor);
            $v->setMarque($findmodel->getMarque());
            $v->setModele($findmodel);
            $v->setConfort($cofort);
            $v->setNbrPlaces($nbplaces);
            $v->setImmatriculation($immatriculation);

            $em=$this->getDoctrine()->getManager();
            $em->persist($v);
            $em->flush();


            return $response->setData(array(
                'action'=>'api car add',
                'success'=> true,
                'newcar'=>array(
                    'id'=>$v->getId(),
                    'name'=>$v->getMarque()->getCarBrand()." ".$v->getModele()->getCarModel()
                )
            ));
        }




        return $response->setData(array(
           'action'=>'api car add',
            'success'=> false,
            'errors'=>$errors
        ));
    }






}
