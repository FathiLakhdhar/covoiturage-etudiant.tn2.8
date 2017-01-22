<?php

namespace Wanasni\MessageBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\MessageBundle\Controller\MessageController as BaseControler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class MessageController extends BaseControler
{

    /**
     * @param $threadId
     * @param Request $request
     * @return JsonResponse
     * @Route(path="/api/thread-reply/{threadId}", name="api_thread_msg")
     */
    public function ApithreadAction($threadId, Request $request)
    {

        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array(
                'error' => array(
                    'code' => 400,
                    'message' => 'You can access this only using Ajax!'
                )
            ),
                400);
        }

        if ($request->getMethod() != 'POST') {
            return new JsonResponse(array(
                'error' => array(
                    'code' => 400,
                    'message' => 'You can access this only using Method POST'
                )
            ),
                400);
        }
        $thread = $this->getProvider()->getThread($threadId);

        if (!$thread) {
            return new JsonResponse(array(
                'error' => array(
                    'code' => 404,
                    'message' => 'thread not found'
                )
            ),
                404);
        }
        $form = $this->container->get('fos_message.reply_form.factory')->create($thread);
        $formHandler = $this->container->get('fos_message.reply_form.handler');
        if ($message = $formHandler->process($form)) {
            return new JsonResponse(
                array(
                    'error' => array(
                        'code' => 200,
                        'message' => 'success send message'
                    ),
                    'message' => array(
                        'id' => $message->getId(),
                        'body' => $message->getBody(),
                        'createAt' => $message->getCreatedAt()->format("F jS \\a\\t g:ia"),
                        'sender' => $message->getSender()->getUsername(),
                        'icon' => $this->container->get('templating.helper.assets')->getUrl($message->getSender()->getPhoto()->getwebPath())
                    )
                ), 200);
        } else {
            return new JsonResponse(array(
                'error' => array(
                    'code' => 404,
                    'message' => 'error send message'
                )
            ),
                404);
        }


    }


    /**
     * @return JsonResponse
     * @Route(path="/api/user/threads", name="api_threads_user")
     */
    public function ApiGetthreadsAction()
    {
        $provider = $this->container->get('fos_message.provider');
        $threads = $provider->getInboxThreads();

        $arr = array();
        foreach ($threads as $thread) {
            $arr[] = array(
                'id' => $thread->getId(),
                'link' => $this->container->get('router')->generate('fos_message_thread_view', array('threadId' => $thread->getId())),
                'message' => array(
                    'id' => $thread->getLastMessage()->getId(),
                    'body' => $thread->getLastMessage()->getBody(),
                    'createAt' => $thread->getLastMessage()->getCreatedAt()->format("F jS \\a\\t g:ia"),
                    'isRead' => $thread->getLastMessage()->isReadByParticipant($this->container->get('security.context')->getToken()->getUser()),
                    'sender' => $thread->getLastMessage()->getSender()->getUsername(),
                    'icon' => $this->container->get('templating.helper.assets')->getUrl($thread->getLastMessage()->getSender()->getPhoto()->getwebPath())
                )
            );
        }

        return new JsonResponse($arr);
    }


    /**
     * @return JsonResponse
     * @Route(path="/api/messages/nb-unread", name="api_nb_unread_messages")
     */
    public function ApiGetThreadUnreadAction()
    {

        $provider = $this->container->get('fos_message.provider');
        $NbUnreadMessages = $provider->getNbUnreadMessages();
        return new JsonResponse(
            array(
                'NbUnreadMessages' => $NbUnreadMessages
            ), 200
        );
    }

    /**
     * @param $threadId
     * @param Request $request
     * @return JsonResponse
     * @Route(path="/api/messages-unread/{threadId}", name="api_thread_messages_unread")
     */
    public function ApiGetMessagesThreadUnreadAction($threadId, Request $request)
    {
        $thread = $this->getProvider()->getThread($threadId);
        if (!$thread) {
            return new JsonResponse(
                array(
                    'error' => array(
                        'code' => 404,
                        'message' => 'thread not found'
                    )
                ),404);
        }

        $messages=$thread->getMessages();
        $participant= $this->container->get('security.context')->getToken()->getUser();
        $messagesUnreadByParticipant= array();
        foreach($messages as $msg){
            if(!$msg->isReadByParticipant($participant) && $msg->getSender()!=$participant){
                $messagesUnreadByParticipant[]=
                     array(
                    'id' => $msg->getId(),
                    'body' => $msg->getBody(),
                    'createAt' => $msg->getCreatedAt()->format("F jS \\a\\t g:ia"),
                    'isRead' => false,
                    'sender' => $msg->getSender()->getUsername(),
                    'icon' =>$this->container->get('templating.helper.assets')->getUrl($msg->getSender()->getPhoto()->getwebPath())

                );
            }
        }

        return new JsonResponse(
            array(
                'error' => array(
                    'code' => 200,
                    'message' => 'messages unread'
                ),
                'messages'=>$messagesUnreadByParticipant
            )
            ,200
        );
    }


}
