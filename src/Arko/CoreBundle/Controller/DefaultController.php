<?php

namespace Arko\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation as JMS;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template("ArkoCoreBundle:Default:index.html.twig")
     * @JMS\Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function indexAction()
    {
        //   return $this->render('ArkoCoreBundle:Default:index.html.twig');
        return array("user" => "hello world");
    }
}
