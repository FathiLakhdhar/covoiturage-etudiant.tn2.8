<?php

namespace Wanasni\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Wanasni\AdminBundle\Entity\Contact;
use Wanasni\PhotoBundle\Entity\Photo;
use Wanasni\UserBundle\Entity\User;

class AdminController extends Controller
{
    /**
     * @Route(path="/", name="admin_index")
     */
    public function indexAction()
    {
        return $this->render(':Admin:index.html.twig');
    }


    /**
     * @return Response
     * @Route(path="/show-photos-users", name="admin_show_photos_users")
     */
    public function showPhotosUserAction()
    {

        $rep=$this->getDoctrine()->getManager()->getRepository('WanasniPhotoBundle:Photo');

        $photos=$rep->getPhotosNotValid();

        return $this->render(':Admin:valid_photos.html.twig', array(
            'photos'=>$photos
        ));
    }


    /**
     * @param Photo $photo
     * @param Request $request
     * @return JsonResponse
     * @Route(path="/api-invalid-photo/{id}", name="api_admin_invalid_photo")
     * @ParamConverter("photo", class="WanasniPhotoBundle:Photo")
     */
    public function ApiInvalidPhoto(Photo $photo, Request$request)
    {

        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }


        $photo->removeUpload();
        $photo->setPath(null);
        $em=$this->getDoctrine()->getManager();
        $em->persist($photo);
        $em->flush();

        $message= \Swift_Message::newInstance();
        $message
            ->setFrom('app.wanasni@gmail.com','AdminWanasni')
            ->setTo($photo->getUser()->getEmail())
            ->setSubject('Votre photo a été refusée.')
            ->setBody(
                $this->renderView(':Admin:email_refuse_photo.html.twig',
                    array(
                        'photo'=>$photo
                    ))
            ,'text/html')

        ;

        $this->get('mailer')->send($message);






        return new JsonResponse(
            array(
                'error'=>array(
                    'code'=>200,
                    'message'=>'success'
                )
            ),200
        );
    }



    /**
     * @param Photo $photo
     * @param Request $request
     * @return JsonResponse
     * @Route(path="/api-valid-photo/{id}", name="api_admin_valid_photo")
     * @ParamConverter("photo", class="WanasniPhotoBundle:Photo")
     */
    public function ApivalidPhoto(Photo $photo)
    {

        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }


        $photo->setValid(true);
        $em=$this->getDoctrine()->getManager();
        $em->persist($photo);
        $em->flush();


        $message= \Swift_Message::newInstance();
        $message
            ->setFrom('app.wanasni@gmail.com','AdminWanasni')
            ->setTo($photo->getUser()->getEmail())
            ->setSubject('Votre photo a été accepte.')
            ->setBody(
                $this->renderView(':Admin:email_accepte_photo.html.twig',
                    array(
                        'photo'=>$photo
                    ))
                ,'text/html')

        ;

        $this->get('mailer')->send($message);


        return new JsonResponse(
            array(
                'error'=>array(
                    'code'=>200,
                    'message'=>'success'
                )
            ),200
        );
    }


    /**
     * @Route(path="/contactez-nous" , name="admin_list_contactez_nous")
     */
    public function contactezNousAction()
    {

        $contacts= $this->getDoctrine()->getManager()->getRepository('WanasniAdminBundle:Contact')->findAll();

        return $this->render(':Admin:contactez_nous.html.twig', array(
            'contacts'=>$contacts
        ));

    }


    /**
     *
     * @Route(path="/List-users", name="admin_list_users")
     */
    public function ListUsersAction()
    {
        $rep = $this->getDoctrine()->getManager()->getRepository('WanasniUserBundle:User');
        $list= $rep->findAll();

       return $this->render(':Admin/gerer_users:list_users.html.twig',array(
           'users'=>$list
       )) ;
    }


    /**
     * @Route(path="/list-avis-received/{id}", name="admin_list_avis_received_user")
     */
    public function UserListAvisReceivedAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $user= $em->find('WanasniUserBundle:User', $id);
        $rep= $em->getRepository('WanasniAvisBundle:Avis');
        $list_avis= $rep->findBy(
            array('recepteur'=> $user),
            array('createAt'=>'desc')
        );



        return $this->render(':Admin/gerer_users:list_avis_received_user.html.twig',array(
            'list_avis'=>$list_avis,
            'user'=>$user
        ));
    }


    /**
     * @return JsonResponse
     * @Route(path="/api-repondre", name="admin_api_repondre")
     * @Method(methods={"POST"})
     */
    public function ApiRepondreAction(Request $request)
    {

        if(!$request->isXmlHttpRequest()){
            return new JsonResponse(array(
                'error'=>array(
                    'code'=>400,
                    'message'=>'request Ajax !!'
                ),400
            ));
        }


        $message= \Swift_Message::newInstance();
        $message->setSubject($request->request->get('objet'));
        $message->setFrom('app.wanasni@gmail.com','AdminWanasni');
        $message->setTo($request->request->get('email'));
        $message->setBody(
            $request->request->get('editor1'),
            'text/html'
        );

        $mail= $this->get('mailer');

        $mail->send($message);

        return new JsonResponse(array(
            'error'=>array(
                'code'=>200,
                'message'=>'success'
            )
        ));
    }


    /**
     * @Route(path="/locked-user/{id}", name="admin_locked_user")
     * @ParamConverter("user", class="WanasniUserBundle:User")
     */
    public function lockedUserAction(User $user)
    {

        $user->setLocked(true);
        $em=$this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_list_users'));

    }

    /**
     * @Route(path="/unlocked-user/{id}", name="admin_unlocked_user")
     * @ParamConverter("user", class="WanasniUserBundle:User")
     */
    public function unlockedUserAction(User $user)
    {

        $user->setLocked(false);
        $em=$this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_list_users'));

    }



}
