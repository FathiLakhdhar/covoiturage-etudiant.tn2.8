<?php

namespace Wanasni\WebsocketBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('WanasniWebsocketBundle:Default:index.html.twig');
    }
}
