<?php

namespace Arko\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FilterGroupResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseGroupEvent;
use FOS\UserBundle\Event\GroupEvent;

use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation as JMS;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Arko\UserBundle\Entity\Group;

/**
 * Group Controller.
 *
 */
class GroupController extends Controller
{
    /**
     * @return object
     */
    protected function getDoctrineManager(){

        return $this->getDoctrine()->getManager()->getRepository("ArkoUserBundle:Group");

    }

    /**
     * @return string
     */
    public function getSuccessMsg($msg){
        return $this->get('session')->getFlashBag()->add('success', $msg);
    }

    /**
     * @Route("/group/list", name="user_group_list")
     * @Template("ArkoUserBundle:Group:list.html.twig")
     * @JMS\Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function indexAction(){

        return array('groups' => $this->getDoctrineManager()->findAll());
    }

    /**
     * @Route("/group/details/{id}", name="user_group_show")
     * @Template("ArkoUserBundle:Group:show.html.twig")
     * @param Group $group
     * @JMS\Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function detailsAction(Group $group){
        return array('group' => $group);
    }


    /**
     * @Route("/group/add", name="user_group_new")
     * @Template("ArkoUserBundle:Group:new.html.twig")
     * @param Request $request
     * @JMS\Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function addAction(Request $request){


        /** @var $groupManager \FOS\UserBundle\Model\GroupManagerInterface */
        $groupManager = $this->container->get('fos_user.group_manager');
        $group = $groupManager->createGroup('');
        $service = $this->get('userbundle_user.group.form.type');
//        var_dump($service);die;
        $form = $this->createForm($service, $group);

        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->getDoctrineManager()->create($group);
                $this->getSuccessMsg("Group Add Successfully.");

                return $this->redirect($this->generateUrl('fos_user_group_list'));
            }
        }
        return array('form' => $form->createView());
    }

    /**
     * @Route("/group/edit/{id}", name="user_group_edit")
     * @Template("ArkoUserBundle:Group:edit.html.twig")
     * @param Request $request
     * @param Group $group
     * @JMS\Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function groupEditAction(Request $request, Group $group){
        $service = $this->get('userbundle_user.group.form.type');
        $form = $this->createForm($service, $group);
        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->getDoctrineManager()->update($group);
                $this->getSuccessMsg("Group Updated Successfully.");
                return $this->redirect($this->generateUrl('fos_user_group_list'));
            }
        }
        return array('form' => $form->createView(), 'group'  => $group );
    }

    /**
     * @Route("/group/delete/{id}", name="user_group_delete")
     * @param Group $group
     * @JMS\Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function groupDeleteAction(Group $group){
        $this->container->get('fos_user.group_manager')->deleteGroup($group);
        $this->getSuccessMsg("Group Deleted Successfully.");
        return new RedirectResponse($this->container->get('router')->generate('fos_user_group_list'));
    }
}