<?php

namespace AppBundle\Controller;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Controller\ProfileController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use AppBundle\Form\Type\UserType;
use AppBundle\Model\YearQuery;

class UserController extends Controller
{
    
    public function edit2Action($user_id, Request $request)
    {
		$userManager = $this->container->get('fos_user.user_manager');

        if($user_id == 0) {
			$user = $userManager->createUser();
			$user->setEnabled(true);  }	
		else {
			$user = $userManager->findUserBy(array('id' => $user_id)); }
			
        $form = $this->createForm(new UserType(), $user);       
		
		$buttons = array('delete','save','cancel');
		
        if ('POST' === $request->getMethod()) {
			$form->handleRequest($request);
			
            if ($form->get('save')->isClicked()) {
				
				if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
					throw new AccessDeniedException(); }
					
				$this->get('fos_user.user_manager')->updateUser($user); 
			}	

            if ($form->get('delete')->isClicked()) {
				
				if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
					throw new AccessDeniedException(); }
					
				$this->get('fos_user.user_manager')->deleteUser($user); 
			}			
			
			$Year  = YearQuery::getFirstActive();
			return $this->redirect($this->generateUrl('oppen_settings', array(
				'tab_id'  => 7,
				'year_id' => $Year->getId()) )); 
        }
        
		return $this->render('AppBundle:User:edit.html.twig',
			array('form' => $form->createView(),
				  'buttons' => $buttons));
    }        
}
