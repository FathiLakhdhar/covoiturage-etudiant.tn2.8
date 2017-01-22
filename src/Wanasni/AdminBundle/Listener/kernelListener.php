<?php
/**
 * Created by PhpStorm.
 * User: Toshiba
 * Date: 15-05-2016
 * Time: 13:56
 */

namespace Wanasni\AdminBundle\Listener;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Templating\EngineInterface;

class kernelListener
{

    protected $templating;

    /**
     * kernelListener constructor.
     */
    public function __construct(EngineInterface $templating)
    {
        $this->templating=$templating;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {

        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        //$event->setResponse(new Response($this->templating->render(':Admin:maintenance.html.twig')));
    }
}