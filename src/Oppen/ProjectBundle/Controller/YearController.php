<?php

namespace Oppen\ProjectBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Oppen\ProjectBundle\Model\Year;
use Oppen\ProjectBundle\Model\Month;

use Oppen\ProjectBundle\Model\YearQuery;
use Oppen\ProjectBundle\Model\MonthQuery;
use Oppen\ProjectBundle\Model\FileQuery;
use Oppen\ProjectBundle\Model\FileCatQuery;
use Oppen\ProjectBundle\Model\DocCatQuery;
use Oppen\ProjectBundle\Model\AccountQuery;

use Oppen\ProjectBundle\Form\Type\YearType;

use Oppen\ProjectBundle\Form\Type\YearsFilterType;

class YearController extends Controller
{  
    public function editAction($year_id,  Request $request) {
		$Year = YearQuery::create()->findPk($year_id);
		$buttons = array('save','cancel');
		$tab_id = 1;
		
		$form = $this->createForm(new YearType(), $Year);			
		$form->handleRequest($request);
		
		$errors = array();
		$redirect = false;
		$refresh = false;
			
		if ($form->get('cancel')->isClicked()) {
			$redirect = true;
		}			
		if ($form->isSubmitted()) {
			
			$Year = $form->getData();
			$YearFromDate = $form->get('from_date')->getData();
			$YearToDate = $form->get('to_date')->getData();			
			$YearName = $form->get('name')->getData();	
						
			$PrevYear = YearQuery::create()->findOneByRank($Year->getRank()-1);
			if($PrevYear instanceOf Year && $PrevYear->getToDate()->modify('+1 day') != $YearFromDate) {
				$errors[] = 'Brak ciągłości dat pomiędzy latami '.$PrevYear->getName().' i '.$YearName.'.';} 
			$NextYear = YearQuery::create()->findOneByRank($Year->getRank()+1);
			if($NextYear instanceOf Year && $NextYear->getFromDate()->modify('-1 day') != $YearToDate) {
				$errors[] = 'Brak ciągłości dat pomiędzy latami '.$YearName.' i '.$NextYear->getName().'.';} 
									
			$FMonths = $form->get('Months');
			foreach ($FMonths as $k=>$FMonth) {	
				$Month = $FMonth->getData();	
				$MonthFromDate = $FMonth->get('from_date')->getData();
				$MonthToDate   = $FMonth->get('to_date')->getData();	
				$MonthName     = $FMonth->get('name')->getData();
						
				if($k == 0 && $MonthFromDate != $YearFromDate) {
					$errors[] = 'Początek okresu '.$Month->getName().' nie pokrywa się z początkiem roku '.$Year->getName().'.';}
				if($k == 11 && $MonthToDate != $YearToDate ) {
					$errors[] = 'Koniec okresu '.$Month->getName().' nie pokrywa się z końcem roku '.$Year->getName().'.';}
				if($k > 0){
					$PrevMonth 		   = $FMonths[$k-1]->getData();
					$PrevMonthFromDate = $FMonths[$k-1]->get('from_date')->getData();
					$PrevMonthToDate   = $FMonths[$k-1]->get('to_date')->getData();
					$PrevMonthName     = $FMonths[$k-1]->get('name')->getData();
											
					if($MonthFromDate->modify('-1 day') != $PrevMonthToDate) {
						$errors[] = 'Brak ciągłości dat pomiędzy miesiącami '.
									$PrevMonthName.' i '.$MonthName.'.';}
				}
				
				$PrevMonth = MonthQuery::create()->findOneByRank($Month->getRank()-1);
				$ActiveMonth = MonthQuery::create()->filterByYear($Year)->findOneByIsActive(1);					
				
				if ($form->get('close_month')->isClicked() && $FMonth->get('select')->getData()) {
					if( $PrevMonth instanceOf Month && !$PrevMonth->getIsClosed()) {
						$errors[] = 'Poprzedni miesiąc nie jest zamknięty';}
					if($Month->getIsClosed()) {	
						$errors[] = 'Miesiąc jest już zamknięty';}							
					if(count($errors) == 0) { 
						$Month->setIsActive(false)
							  ->setIsClosed(true)
							  ->save(); 
						$OpenMonth = MonthQuery::create()->filterByYear($Year)->findOneByIsClosed(0);
						if($OpenMonth == NULL) {
							$Year->setIsActive(false)
								 ->setIsClosed(true);}
						$refresh = true;
					}	
				}
				
				if ($form->get('activate_month')->isClicked() && $FMonth->get('select')->getData() == 1) {	
					if($ActiveMonth instanceOf Month) { 
						$errors[] = 'Rok posiada już aktywny miesiąc.';}
					if($PrevMonth instanceOf Month && !$PrevMonth->getIsClosed() && !$PrevMonth->getIsActive())  {	
						$errors[] = 'Poprzedni miesiąc nie jest ani aktywny ani zamknięty';}
					if($Month->getIsClosed()) {	
						$errors[] = 'Miesiąc jest już zamknięty';}
					if(count($errors) == 0) { 
						$Month->setIsActive(true)->save(); 
						$Year->setIsActive(true); 	
						$refresh = true; 
					}
				} 
			}
			if(count($errors) == 0) { 
				$Year -> save();
				if($form->get('save')->isClicked()) { $redirect = true; }
			}
		}
				
		if($redirect) {
			return $this->redirect($this->generateUrl('oppen_settings', array(
				'tab_id'  => $tab_id,
				'year_id' => $Year->getId()) )); 			
		}
		if($refresh) {
			return $this->redirect($this->generateUrl('oppen_year_edit', array(
				'year_id' => $Year->getId()) )); 			
		}
				
		return $this->render('OppenProjectBundle:Year:edit.html.twig',
			array(	'form' => $form->createView(),
					'buttons' => $buttons,
					'errors' => $errors ));		
	}

    public function updateDocListAction($year_id) {
		$Year = YearQuery::create()->findPk($year_id);

		$months = array('<option value>Wszystkie</option>');
		foreach ($Year->getMonths() as $Month) {
			$months[] = '<option value="'.$Month->getId().'">'.$Month->__toString().'</option>'; }
		 
		$doc_cats = array('<option value>Wszystkie</option>');
		foreach ($Year->getDocCats() as $DocCat) {
			$doc_cats[] = '<option value="'.$DocCat->getId().'">'.$DocCat->getName().'</option>'; }
				
		$response = new JsonResponse();
		$response->setData(array($months, $doc_cats));
		return $response;
	}
}
