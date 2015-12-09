<?php

namespace AppBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Model\Report;
use AppBundle\Model\ReportEntry;

use AppBundle\Model\ReportQuery;
use AppBundle\Model\ReportEntryQuery;

use AppBundle\Form\Type\ReportEntryType;

class ReportEntryController extends Controller
{		
    public function editAction($report_entry_id, $parent_id,  Request $request)
    {
		$buttons = array('cancel','save');
		
		if($report_entry_id == 0) {
			$Parent = ReportEntryQuery::create()->findPk($parent_id);
			$Report = $Parent->getReport();		
			$ReportEntry = new ReportEntry();
			$ReportEntry->setReport($Report);
			$ReportEntry->insertAsLastChildOf($Parent);		
		} else {
			$ReportEntry = ReportEntryQuery::create()->findPk($report_entry_id);
			$Parent = $ReportEntry->getParent();
			$Report = $Parent->getReport();
			$buttons[] = 'delete';
		}
		$Year   = $Report->getYear(); 
		$ReportEntry->Parent = $Parent;		
				
 		$form = $this->createForm(new ReportEntryType(), $ReportEntry); 			
        $form->handleRequest($request);  
        $redirect = false;
        
		if ($form->isSubmitted()) {
			if ($form->get('cancel')->isClicked()) { 
				$redirect = true;}
			if ($form->get('delete')->isClicked()) { 
				$ReportEntry->delete(); 
				$redirect = true;}						
			if (($form->get('save')->isClicked()) && ($form->isValid())) { 
				$ReportEntry->save();
					
				if($ReportEntry->getName() == '') {
					$ReportEntry->setName('Id'.$ReportEntry->getId())->save(); }
												
				$NewParent = $form->get('Parent')->getData();
				if($Parent->getId() != $NewParent->getId()) {
					$ReportEntry->moveToLastChildOf($NewParent); }
				$redirect = true;
			}	
		}	
		
		if ($redirect) {
			return $this->redirect($this->generateUrl('oppen_report_edit', array(
					'report_id' => $Report->getId(),
					'year_id' => $Year->getId() ))); 			
		}
									
		return $this->render('AppBundle:ReportEntry:edit.html.twig',array(
			'Report' => $Report,	
			'buttons' => $buttons,		
			'form' => $form->createView() ));
	}  

    public function moveAction($report_entry_id, $dir, Request $request)
    {
		$ReportEntry = ReportEntryQuery::create()->findPk($report_entry_id); 
		$Report = $ReportEntry->getReport();
		$Year = $Report->getYear(); 
				
		if ($dir == 'up')   { 
			$PrevSibling = $ReportEntry->getPrevSibling();
			if($PrevSibling instanceOf ReportEntry) {
				$ReportEntry->moveToPrevSiblingOf($PrevSibling);}
		}		
		if ($dir == 'down')   { 
			$NextSibling = $ReportEntry->getNextSibling();
			if($NextSibling instanceOf ReportEntry) {
				$ReportEntry->moveToNextSiblingOf($NextSibling);}
		}			
		return $this->redirect($this->generateUrl('oppen_report_edit', array(
			'report_id' => $Report->getId(),
			'year_id' => $Year->getId()) ));
	}
			 
}
