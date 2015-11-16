<?php

namespace Oppen\ProjectBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Oppen\ProjectBundle\Model\FileCat;

use Oppen\ProjectBundle\Model\FileQuery;
use Oppen\ProjectBundle\Model\FileCatQuery;
use Oppen\ProjectBundle\Model\YearQuery;

use Oppen\ProjectBundle\Form\Type\FileCatType;

class FileCatController extends Controller
{
	public function editAction($file_cat_id, $year_id, Request $request) 
	{	
		$buttons = array('save','cancel'); 
		
		if($file_cat_id == 0) {
			$Year = YearQuery::create()->findPk($year_id);
			$FileCat = new FileCat();
			$FileCat->setYear($Year);
		} else {
			$FileCat = FileCatQuery::create()->findPk($file_cat_id);
			$Year = $FileCat->getYear();
			if($FileCat->getIsLocked() == 0) { $buttons[] = 'delete'; }
		}			
		
		$form = $this->createForm(new FileCatType($Year, true), $FileCat);	
        $form->handleRequest($request);
        
		$messages = array(); $errors = array(); $redirect = false;
		if ($form->get('save')->isClicked()) {
			$FC = FileCatQuery::create()->findOneBySymbolAndYear($form->get('symbol')->getData(),$Year);
			if(($file_cat_id == 0 && $FC instanceOf FileCat) || 
			   ($file_cat_id > 0 && $FC instanceOf FileCat && $file_cat_id != $FC->getId())) {
				$errors[] = 'Ten symbol jest jest już użyty.';}
			if(empty($errors)) {
				$FileCat->save();
				$redirect = true;}
		}
		if ($form->get('delete')->isClicked()) {
			$Fcount = FileQuery::create()->filterByFileCat($FileCat)->count();
			if($Fcount > 0) {
				$errors[] = 'Ta kategoria posiada kartoteki.';}
			if($FileCat->getIsLocked()) {
				$errors[] = 'Tej kategorii nie można usunąć.';}				
			if(empty($errors)) {
				$FileCat->delete();	
				$redirect = true; }			
		}
		if ($form->get('cancel')->isClicked()) {	
			$redirect = true;
		}
		if($redirect) {
			return $this->redirect($this->generateUrl('oppen_settings', array(
				'tab_id' => 3,
				'year_id' => $Year->getId()) )); }
				
		return $this->render('OppenProjectBundle:FileCat:edit.html.twig',
			array(	'Year' => $Year,
					'form' => $form->createView(),
					'buttons' => $buttons,
					'messages' => $messages,
					'errors' => $errors ));	
    }
}
