<?php

namespace AppBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Model\DocCat;

use AppBundle\Model\DocQuery;
use AppBundle\Model\DocCatQuery;
use AppBundle\Model\YearQuery;
use AppBundle\Model\MonthQuery;

use AppBundle\Form\Type\DocCatType;

class DocCatController extends Controller
{
	public function editAction($doc_cat_id, $year_id, Request $request) 
	{	
		$buttons = array('save','cancel'); 
		
		if($doc_cat_id == 0) {
			$Year = YearQuery::create()->findPk($year_id);
			$DocCat = new DocCat();
			$DocCat->setYear($Year);
		} else {
			$DocCat = DocCatQuery::create()->findPk($doc_cat_id);
			$Year = $DocCat->getYear();
			if($DocCat->getIsLocked() == 0) { $buttons[] = 'delete'; }
		}			
		
		$form = $this->createForm(new DocCatType($Year, true), $DocCat);	
        $form->handleRequest($request);
        
		$messages = array(); $errors = array(); $redirect = false;
		if ($form->get('save')->isClicked()) {
			$DC = DocCatQuery::create()->findOneBySymbolAndYear($form->get('symbol')->getData(),$Year);
			if(($doc_cat_id == 0 && $DC instanceOf DocCat) || 
			   ($doc_cat_id > 0 && $DC instanceOf DocCat && $doc_cat_id != $DC->getId())) {
				$errors[] = 'Ten symbol jest jest już użyty.';}
			if(empty($errors)) {
				$DocCat->save();
				$redirect = true;}			
		}
		if ($form->get('delete')->isClicked()) {
			$Dcount = DocQuery::create()->filterByDocCat($DocCat)->count();
			if($DocCat->getIsLocked()) {
				$errors[] = 'Tej kategorii nie można usunąć.';}
			if($Dcount > 0) {
				$errors[] = 'Ta kategoria posiada dokumenty.';}
			if(empty($errors)) {
				$DocCat->delete();	
				$redirect = true; }
		}
		if ($form->get('cancel')->isClicked()) {	
			$redirect = true;
		}
		if($redirect) {
			return $this->redirect($this->generateUrl('oppen_settings', array(
				'tab_id' => 3,
				'year_id' => $Year->getId()) )); }
				
		return $this->render('AppBundle:DocCat:edit.html.twig',
			array(	'Year' => $Year,
					'form' => $form->createView(),
					'buttons' => $buttons,
					'messages' => $messages,
					'errors' => $errors ));	
    }
}
