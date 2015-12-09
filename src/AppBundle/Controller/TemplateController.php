<?php

namespace AppBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Model\Template;

use AppBundle\Model\YearQuery;
use AppBundle\Model\MonthQuery;
use AppBundle\Model\TemplateQuery;

use AppBundle\Form\Type\TemplateType;
use AppBundle\Form\Type\YearsFilterType;

class TemplateController extends Controller
{  
    public function editAction($template_id, $year_id, Request $request)
    {
		$Year = YearQuery::create()->findPk($year_id);
		$buttons = array('save','cancel');		
		if($template_id == 0) {
			$Template = new Template();	}
		else {	
			$Template = TemplateQuery::create()->findPk($template_id);
			$buttons[] = 'delete';}
		$tab_id = 5;
		
		$form = $this->createForm(new TemplateType(), $Template);			
		$form->handleRequest($request);
		
		$redirect = false;
		if ($form->get('save')->isClicked()) {
			$Template -> save(); 
			$redirect = true; 	}
		if ($form->get('cancel')->isClicked()) {
			$redirect = true;	}	
		if ($form->get('delete')->isClicked()) {
			$Template -> delete(); 
			$redirect = true; }	
						
		if($redirect) {
			return $this->redirect($this->generateUrl('oppen_settings', array(
				'tab_id'  => $tab_id,
				'year_id' => $year_id) )); 			
		}
								
		return $this->render('AppBundle:Template:edit.html.twig',
			array(	'form' => $form->createView(),
					'buttons' => $buttons ));		
	}
}
