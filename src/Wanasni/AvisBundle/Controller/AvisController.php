<?php

namespace Wanasni\AvisBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Wanasni\AvisBundle\Entity\Avis;
use Wanasni\AvisBundle\Form\AvisType;

class AvisController extends Controller
{


    /**
     * @return Response
     * @Route(path="/laisser", name="avis_laisser")
     */
    public function laisserAvisAction(Request $request)
    {

        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw new AccessDeniedException();
        }


        $avis = new Avis();
        $form= $this->createForm(new AvisType($this->get('security.context')), $avis);


        if($request->getMethod()=="POST"){
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){

                $avis->setEmetteur($this->getUser());

                $em=$this->getDoctrine()->getManager();
                $em->persist($avis);
                $em->flush();

                $this->get('session')->getFlashBag()->set('success','avis bien ajoute');
                return $this->redirect($this->generateUrl('avis_given'));
            }
        }


        return $this->render(':Avis:laisser_un_avis.html.twig',array(
            'form'=>$form->createView()
        ));
    }


    /**
     * @return Response
     * @Route(path="/received", name="avis_received")
     */
    public function AvisReceivedAction()
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw new AccessDeniedException();
        }

        $rep= $this->getDoctrine()->getManager()->getRepository('WanasniAvisBundle:Avis');

        $countall=$rep->CountAllAvis($this->getUser());


        if($countall > 0){
            $countParfait=$rep->CountAvisBy($this->getUser(),"Parfait");
            $countTresBien=$rep->CountAvisBy($this->getUser(),"Très bien");
            $countBien=$rep->CountAvisBy($this->getUser(),"Bien");
            $countDecevant=$rep->CountAvisBy($this->getUser(),"Décevant");
            $countEviter=$rep->CountAvisBy($this->getUser(),"À éviter");

            return $this->render(':Avis:received_avis.html.twig',array(
                //number_format(  )
                'parfait'=>number_format( ($countParfait/$countall)*100 ),
                'tresBien'=>number_format( ($countTresBien/$countall)*100 ),
                'bien'=> number_format( ($countBien/$countall)*100 ),
                'decevant'=>number_format( ($countDecevant/$countall)*100 ),
                'eviter'=>number_format( ($countEviter/$countall)*100 ),
            ));
        }else{
            return $this->render(':Avis:received_avis.html.twig',array(
                //number_format(  )
                'parfait'=>0,
                'tresBien'=>0,
                'bien'=> 0,
                'decevant'=>0,
                'eviter'=>0,
            ));
        }

    }


    /**
     * @return Response
     * @Route(path="/given", name="avis_given")
     */
    public function AvisGivenAction()
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw new AccessDeniedException();
        }

        $AvisGiven= $this->getUser()->getAvisGiven();

        return $this->render(':Avis:given_avis.html.twig',array(
            'AvisGiven'=>$AvisGiven
        ));
    }


}
