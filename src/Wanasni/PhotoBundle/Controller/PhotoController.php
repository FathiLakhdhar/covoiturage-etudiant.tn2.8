<?php

namespace Wanasni\PhotoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wanasni\PhotoBundle\Entity\Photo;
use Wanasni\PhotoBundle\Form\PhotoType;

class PhotoController extends Controller
{
    /**
     * @Route(path="/photo", name="photo_edit")
     */
    public function EditPhotoAction(Request $request)
    {


        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw new AccessDeniedException();
        }


        $photo=$this->getUser()->getPhoto();


        $form= $this->createForm(new PhotoType(),$photo);

        if($request->getMethod()=='POST'){
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $em=$this->getDoctrine()->getManager();
                $em->persist($photo);
                $em->flush();
            }
        }


        return $this->render('@FOSUser/Profile/photo.html.twig',array(
            'form'=>$form->createView(),
        ));
    }
}
