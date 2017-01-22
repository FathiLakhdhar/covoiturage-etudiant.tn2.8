<?php
namespace Wanasni\TrajetBundle\Controller;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Wanasni\NotificationBundle\Services\TypeNotification;
use Wanasni\TrajetBundle\Entity\Alert;
use Wanasni\TrajetBundle\Entity\Reservation;
use Wanasni\TrajetBundle\Entity\Trajet;
use Wanasni\TrajetBundle\Form\AlertType;
use Wanasni\TrajetBundle\Form\TrajetRegulierType;
use Wanasni\TrajetBundle\Form\TrajetUniqueType;
use Wanasni\TrajetBundle\Objet\Search;
use Symfony\Component\Routing\Annotation\Route;
class TrajetController extends Controller
{

    /**
     * @Route("/covoiturages-mes-trajets", name="mes-trajets" )
     */
    public function MesTrajetsAction(){
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw new AccessDeniedException();
        }
        $trajets=$this->getUser()->getTrajets();

        return $this->render(':Trajet/Gerer:voir_mes_trajets.html.twig',array(
            'trajets'=>$trajets
        ));
    }

    /**
     * @Route("/covoiturages-trajets-reserve", name="mes-reservations" )
     */
    public function MesReservationAction(){
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw new AccessDeniedException();
        }

        $reservations=$this->getUser()->getReservations();
        $trajets= new ArrayCollection();
        foreach($reservations as $reservation){
            $trajets->add($reservation->getTrajet());
        }

        return $this->render(':Trajet/Gerer:voir_mes_reservation.html.twig',array(
            'reservations'=>$reservations,
            'trajets'=>$trajets
        ));
    }




    /**
     * @Route("/mes-alertes", name="mes-alertes")
     */
    public function MesAlertesAction(){

        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw new AccessDeniedException();
        }

        $rep= $this->getDoctrine()->getManager()->getRepository('WanasniTrajetBundle:Alert');
        $alerts= $rep->findBy(
            array('email'=>$this->getUser()->getEmail()),
            array('createAt'=>'desc')
        );


        return $this->render(':Trajet/Gerer:voir_mes_alertes.html.twig',array(
            'alertes'=>$alerts
        ));
    }


    /**
     * @Route("/proposer-trajet", name="trajet_proposer_unique" )
     */
    public function ProposerAction()
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw new AccessDeniedException();
        }

        $trajet = new Trajet();
        // On crée le formulaire grâce à la TrajetUniqueType
        $form = $this->createForm(new TrajetUniqueType($this->getUser()), $trajet);
        // On récupère la requête
        $request = $this->getRequest();
        // On vérifie qu'elle est de type POST
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $trajet->setConducteur($this->getUser());
                $em->persist($trajet);
                $em->flush();
                $serviceAlert=$this->container->get('wanasni_trajet.alert_mailer');
                $serviceAlert->EnvoyerMailer($trajet);
                // On définit un message flash
                $this->get('session')->getFlashBag()->add('success', 'Trajet bien ajouté');
                return $this->redirect($this->generateUrl(
                    'trajet_show',
                    array('id' => $trajet->getId())
                ));
            }
        }
        return $this->render(':Trajet/Proposer:proposer_trajet_unique.html.twig',
            array(
                'form' => $form->createView(),
            ));
    }


    /**
     * @Route("/proposer-trajet-regulier", name="trajet_proposer_regulier" )
     */
    public function ProposerRegulierAction()
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw new AccessDeniedException();
        }
        $trajet = new Trajet();
        // On crée le formulaire grâce à la TrajetType
        $form = $this->createForm(new TrajetRegulierType($this->getUser()), $trajet);
        // On récupère la requête
        $request = $this->getRequest();
        // On vérifie qu'elle est de type POST
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $trajet->setConducteur($this->getUser());
                $em->persist($trajet);
                $em->flush();
                $serviceAlert=$this->container->get('wanasni_trajet.alert_mailer');
                $serviceAlert->EnvoyerMailer($trajet);
                // On définit un message flash
                $this->get('session')->getFlashBag()->add('info', 'Trajet bien ajouté');
                return $this->redirect($this->generateUrl(
                    'trajet_show',
                    array('id' => $trajet->getId())
                ));
            }
        }
        return $this->render(':Trajet/Proposer:proposer_trajet_regulier.html.twig',
            array(
                'form' => $form->createView(),
            ));
    }



    /**
     *
     * @Route("/voir-trajet/{id}", name="trajet_show")
     *
     */
    public function ShowAction($id)
    {


        $rep= $this->getDoctrine()->getManager()->getRepository('WanasniTrajetBundle:Trajet');

        $trajet=$rep->getTrajetById($id);

        return $this->render(':Trajet/Gerer:voir_trajet.html.twig',
            array(
                'trajet' => $trajet,
            ));
    }



    /**
     * @Route("/rechercher-trajet", name="trajet_search")
     */
    public function SearchAction(Request $request)
    {
        $search = new Search();
        $alert=new Alert();
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $alert->setEmail($this->getUser()->getEmail());
        }
        $Trajets = null;
        if ($request->getMethod() == 'POST') {
            $search->setOrigine($request->get('search_origine'));
            $search->setDestination($request->get('search_destination'));
            $date = $request->get('search_date');
            $search->setDate($date);
            $alert->setOrigine($search->getOrigine());
            $alert->setDestination($search->getDestination());
            $alert->setDate($date);
            $em = $this->getDoctrine()->getManager();
            $rep = $em->getRepository('WanasniTrajetBundle:Trajet');
            $Trajets = $rep->SearchByOrigineAndDestination($search->getOrigine(), $search->getDestination(), $search->getDate());
            // On définit un message flash
            //$this->get('session')->getFlashBag()->add('info', 'Trajet Trouver');
            $form=$this->createForm(new AlertType(),$alert);
            return $this->render(':Trajet/Gerer:rechercher_trajet.html.twig', array(
                'search'=>$search,
                'trajets'=>$Trajets,
                'form'=>$form->createView()
            ));
        }
        return $this->render(':Trajet/Gerer:rechercher_trajet.html.twig', array(
            'search'=>$search,
            'trajets'=>$Trajets,
            'form'=>null
        ));
    }



    /**
     * @Route("/add-alert", name="trajet_create_alert")
     */
    public function AlertAction(Request $request){
        $alert=new Alert();
        $form= $this->createForm(new AlertType(),$alert);
        if($request->getMethod()=="POST"){
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {
                $em=$this->getDoctrine()->getManager();
                $em->persist($alert);
                $em->flush();
                $this->get('session')->getFlashBag()
                    ->add('success','Vous avez créé une alerte. Vous recevrez donc un e-mail :
• à chaque fois qu\'un conducteur publiera un trajet qui correspond à vos besoins');
                return $this->redirect($this->generateUrl('trajet_search'));
            }
        }
        return new Response();
    }


    /**
     * @Route("/prix-optimal/{metre}", name="prix_optimal")
     */
    public function PrixOptimalAction($metre)
    {
        // 100 km => 5 TND
        $prix = 0;
        $em = $this->getDoctrine()->getManager();
        $PrixOptimel = $em->find('WanasniTrajetBundle:PrixOptimal', 1);
        if ($PrixOptimel) {
            $prix = $metre / $PrixOptimel->getX();
        }
        return new JsonResponse(array('PrixOptimal' => ceil($prix), 'Unite' => 'TND'));
    }




    /**
     * @Route("/modifier-trajet/{id}", name="trajet_edit")
     */
    public function EditAction($id, Request $request){
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }

        $rep= $this->getDoctrine()->getManager()->getRepository('WanasniTrajetBundle:Trajet');

        $trajet=$rep->getTrajetById($id);

        if(!$trajet){
            throw new EntityNotFoundException();
        }


        if($trajet->getConducteur() != $this->getUser()){
            throw new AccessDeniedException();
        }

        $em=$this->getDoctrine()->getManager();
        $origine_points =$trajet->getWaypoints();

        if($trajet->getFrequence()=="UNIQUE"){
            $form=$this->createForm(new TrajetUniqueType($this->getUser()), $trajet);
            if($request->getMethod()=='POST'){
                $trajet->setArrPrix(array());
                $form->handleRequest($request);
                if($form->isSubmitted() && $form->isValid()){
                    foreach($origine_points as $waypoint){
                        if(false === $trajet->getWaypoints()->contains($waypoint)){
                            $waypoint->setWay(null);
                            $em->remove($waypoint);
                        }
                    }
                    $em->persist($trajet);
                    $em->flush();
                    return $this->redirect($this->generateUrl('trajet_show',array(
                        'id'=>$trajet->getId()
                    )));
                }
            }
            return $this->render(':Trajet/Gerer:modifier_trajet_unique.html.twig',array(
                'form'=>$form->createView(),
                'id'=>$trajet->getId()
            ));

        }else{//regulier
            $form=$this->createForm(new TrajetRegulierType($this->getUser()), $trajet);

            $origine_datesA=$trajet->getDatesAller();
            $origine_datesR=$trajet->getDatesRetour();


            if($request->getMethod()=='POST'){
                $trajet->setArrPrix(array());
                $form->handleRequest($request);
                if($form->isSubmitted() && $form->isValid()){
                    foreach($origine_points as $waypoint){
                        if(false === $trajet->getWaypoints()->contains($waypoint)){
                            $waypoint->setWay(null);
                            $em->remove($waypoint);
                        }
                    }
                    foreach($origine_datesA as $dateway){
                        if(false === $trajet->getDatesAller()->contains($dateway)){
                            $dateway->setTarjetAller(null);
                            $em->remove($dateway);
                        }
                    }
                    foreach($origine_datesR as $dateway){
                        if(false === $trajet->getDatesRetour()->contains($dateway)){
                            $dateway->setTarjetRetour(null);
                            $em->remove($dateway);
                        }
                    }
                    $em->persist($trajet);
                    $em->flush();
                    return $this->redirect($this->generateUrl('trajet_show',array(
                        'id'=>$trajet->getId()
                    )));
                }
            }

            return $this->render(':Trajet/Gerer:modifier_trajet_regulier.html.twig', array(
                'form'=>$form->createView(),
                'id'=>$trajet->getId()
            ));
        }
    }




    /**
     * @Route(path="/trajet-delete/{id}", name="trajet_delete")
     */
    public function deleteAction($id)
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }

        $em=$this->getDoctrine()->getManager();
        $rep=$em->getRepository('WanasniTrajetBundle:Trajet');

        $trajet= $rep->findOneBy(
            array(
                'id'=>$id,
                'conducteur'=>$this->getUser()
            )
        );

        if($trajet){
            $trajet->PreRemove();
            $em->remove($trajet);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Trajet supprimer');
        }

        return $this->redirect($this->generateUrl('mes-trajets'));
    }


    /**
     *
     * @Route(path="api/reservation/{id}", name="trajet_demande_reservation")
     */
    public function DemandeReservationAction($id, Request $request)
    {

        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }


        $em=$this->getDoctrine()->getManager();

        $trajet= $em->getRepository('WanasniTrajetBundle:Trajet')
            ->findOneBy(array(
                'id'=>$id
            ));

        if (!$trajet) {
            return  new EntityNotFoundException();
        }


        if( ($trajet->getNbPlacesRestants() > 0) && (!$trajet->isReserverByPassage($this->getUser()) ) ){

            $reservation= new Reservation();

            $reservation->setPassager($this->getUser());
            $reservation->setTrajet($trajet);

            $serviceNotif=$this->container->get('wanasni_notification.create');
            $notif=$serviceNotif->CreateNotification($reservation,$trajet->getConducteur(),TypeNotification::Demande);

            $em->persist($reservation);
            $em->persist($notif);
            $em->flush();


            $serviceNotif->EnvoyeEmail($notif);

        }


        return $this->redirect($this->generateUrl('trajet_show',array('id'=>$trajet->getId())));

    }




}
