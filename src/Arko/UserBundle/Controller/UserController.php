<?php

namespace Arko\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation as JMS;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Arko\UserBundle\Entity\User;
use Arko\UserBundle\Form\Type\UserFormType;
use Arko\UserBundle\Form\Type\UserEditFormType;

/**
 * User Controller.
 *
 */
class UserController extends Controller
{
    /**
     * @return object
     */
    protected function getDoctrineManager(){
        return $this->getDoctrine()->getManager()->getRepository("UserBundle:User");
    }

    /**
     * @return string
     */
    public function getSuccessMsg($msg){
        return $this->get('session')->getFlashBag()->add('success', $msg);
    }

    /**
     * @Route("/user/list", name="user_list")
     * @Template("ArkoUserBundle:User:list.html.twig")
     * @JMS\Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function indexAction(){
        return array('users' => $this->getDoctrineManager()->findAll());
    }

    /**
     * @Route("/user/details/{id}", name="user_details")
     * @Template("ArkoUserBundle:Profile:show.html.twig")
     * @param User $user
     * @JMS\Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function detailsAction(User $user){
        return array('user' => $user);
    }

    /**
     * @Route("/user/profile", name="user_profile")
     * @Template("ArkoUserBundle:Profile:show.html.twig")
     * @JMS\Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function profileAction(){
        return array('user' => $this->getUser());
    }

    /**
     * @Route("/user/enabled/{id}", name="user_enabled")
     * @param User $user
     * @JMS\Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function enableAction(User $user){
        $user->isEnabled() ? $user->setEnabled(false) AND $this->getSuccessMsg("User Successfully Disabled.") :
            $user->setEnabled(true) AND $this->getSuccessMsg("User Successfully Enabled.");
        $this->getDoctrineManager()->update($user);

        return $this->redirect($this->generateUrl('user_list'));
    }

    /**
     * @Route("/user/add", name="user_add")
     * @Template("ArkoUserBundle:Registration:register.html.twig")
     * @param Request $request
     * @JMS\Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function addAction(Request $request){
        $user = new User();
        $service = $this->get('userbundle_user.registration.form.type');
        $form = $this->createForm($service, $user);

        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->getDoctrineManager()->create($user);
                $this->getSuccessMsg("User Successfully Created.");
                return $this->redirect($this->generateUrl('user_list'));
            }
        }
        return array('form' => $form->createView());
    }

    /**
     * @Route("/user/edit/profile", name="user_edit_profile")
     * @Template("ArkoUserBundle:Profile:edit.html.twig")
     * @param Request $request
     * @JMS\Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function editProfileAction(Request $request){
        $user = $this->getUser();
        $form = $this->createForm(new UserEditFormType(), $user);

        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->getDoctrineManager()->update($user);
                $this->getSuccessMsg("User Successfully Updated.");

                return $this->redirect($this->generateUrl('user_list'));
            }
        }
        return array('form' => $form->createView());
    }

    /**
     * @Route("/user/edit/{id}", name="user_edit")
     * @Template("UserBundle:Registration:register.html.twig")
     * @param Request $request
     * @param User $user
     * @JMS\Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function editAction(Request $request, User $user){
        $form = $this->createForm(new UserEditFormType(), $user);

        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->getDoctrineManager()->update($user);
                $this->getSuccessMsg("User Successfully Updated.");

                return $this->redirect($this->generateUrl('user_list'));
            }
        }
        return array('form' => $form->createView());
    }

    /**
     * @Route("/user/delete/{id}", name="user_delete")
     * @param User $user
     * @JMS\Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function deleteAction(User $user){
        $this->getDoctrineManager()->delete($user);
        $this->getSuccessMsg("User Successfully Deleted.");

        return $this->redirect($this->generateUrl('user_list'));
    }

    public function add($a, $b)
    {
        return $a + $b;
    }
}