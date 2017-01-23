<?php

class Snippets 
{
//BookkController

    public function listAction($year_id, $month_id, $acc_no, $is_accepted, Request $request)  {		
		$Year  = YearQuery::create()->findPk($year_id);
		$Month = MonthQuery::create()->findPk($month_id);
	
		$BookksQ = BookkQuery::create()
			->orderByBookingDate();
		if($Month instanceOf Month) {	
			$BookksQ->filterByMonth($Month);	
		}
		else {
			$BookksQ->useMonthQuery()
						->filterByYear($Year)
					->endUse();
			// If Month not defined, create new Bookk in first active Month
			$Month = MonthQuery::create()->filterByYear($Year)->findOneByIsActive(1); 
		}
		$BookksQ->filterByIsAccepted($is_accepted);
		$BookksQ->useBookkEntryQuery()
					->filterByAccNo($acc_no.'%')
				->endUse();	
		$BookksQ->groupById();				
		$Bookks = $BookksQ->find();
				
		$BookksList = new BookksList();								
		$BookksList->Month =  $Month; 
		$BookksList->AccNo =  $acc_no;
		$BookksList->IsAccepted = (bool) $is_accepted;
		$BookksList->Bookks = $Bookks;
		
		$buttons = array('filter','accept','delete');
		
		$form = $this->createForm(new BookksListType($Year), $BookksList);	
        $form->handleRequest($request);

		if ($form->isValid()) {
			if ($form->get('filter')->isClicked()) { }
			
			if ($form->get('accept')->isClicked()) {
				foreach ($form->get('Bookks') as $FBookk) {
					if ($FBookk->get('select')->getData() == 1) { 
						$Bookk = BookkQuery::create()->findPk($FBookk->getData()->getId());
						$Bookk->setIsAccepted(true); 
						$Bookk->save();
					}
				}
			}
			
			if ($form->get('delete')->isClicked()) {
				foreach ($form->get('Bookks') as $FBookk) {
					if ($FBookk->get('select')->getData() == 1) { 
						$Bookk = BookkQuery::create()->findPk($FBookk->getData()->getId());
						$Bookk->delete(); 
					}
				}
			}
			return $this->redirect($this->generateUrl('oppen_bookks', array(
				'year_id'	  => (int) $Year->getId(),
				'month_id'    => (int) $form->get('Month')->getData()->getId(),
				'acc_no'      => $form->get('AccNo')->getData(),
				'is_accepted' => (int) $form->get('IsAccepted')->getData()) ));				
		}
	
		return $this->render('AppBundle:Bookk:list.html.twig',array(
			'Year' => $Year,
			'Month'=> $Month,	
			'Bookks'=>$Bookks,	
			'form' => $form->createView(),
			'buttons' => $buttons));
    }

//DocController

// Options turned off due to memory leak			
/*					
			//search Bookks & BookkEntries to remove
			$BookksNew = array();
			$BookkEntriesNew = array();
			foreach ($Doc->getBookks() as $B => $Bookk) {
				$BookksNew[$B] = $Bookk;
				foreach ($Bookk->getBookkEntries() as $BE => $BookkEntry)  {		
					$BookkEntriesNew[$B][$BE] = $BookkEntry;
				}			
			}
			
			//remove old Bookks 
			$BookksToDel = array_udiff_assoc($BookksOld, $BookksNew,array($this,'compareById'));
			if(empty($msg['errors'])) {
				foreach ($BookksToDel as $BookkToDel) {
					$Doc->removeBookk($BookkToDel);
					$BookkToDel->delete();
				}
			}
			
			//remove old BookkEntries 
			$BookkEntriesToDel = array();
			if(empty($msg['errors'])) {
				foreach ($BookksOld as $B => $BookkOld) {
					if (array_key_exists($B, $BookkEntriesNew)) {
						$BookkEntriesToDel[$B] = array_udiff_assoc($BookkEntriesOld[$B], $BookkEntriesNew[$B], array($this,'compareById'));
						foreach ($BookkEntriesToDel[$B] as $BE => $BookkEntryToDel) {					
							$BookkOld->removeBookkEntry($BookkEntryToDel);
							$BookkEntryToDel->delete();  
						}	
					}
				}					
			}

			//check if bookking date is within booking month
			$FromDate = $Doc->getMonth()->getFromDate();
			$ToDate = $Doc->getMonth()->getToDate();
			foreach ($Doc->getBookks() as $B => $Bookk) {
				if($Bookk->getBookkingDate() < $FromDate || $Bookk->getBookkingDate() > $ToDate ) {
					$msg['errors'][] = 'Data księgowania dekretacji musi zawierać się w miesiącu dokumentu'; }
			}



				
		// Prepare old collection to find items to delete
		$BookksOld = array();
		$BookkEntriesOld = array();

		foreach ($Doc->getBookks() as $B => $Bookk) {
			$BookksOld[$B] = $Bookk;
			$BookkEntriesOld[$B] = array();
			foreach ($Bookk->getBookkEntries() as $BE => $BookkEntry)  {
				$BookkEntriesOld[$B][$BE] = $BookkEntry;
			}			
		}

		$Bookk = new Bookk();
		$Bform = $this->createForm(new BookkDialogType($Year), $Bookk); 
		
		$BookkEntry = new BookkEntry();
		$BookkEntry->setBE($Bookk, 0, 0, null, array(1=>null, 2=>null, 3=>null) );
		$BEform = $this->createForm(new BookkEntryDialogType($Year, null), $BookkEntry); 

			
				//save Bookks & BookkEntries	
				foreach ($Doc->getBookks() as $B => $Bookk) {
					
					$Bookk->setDoc($Doc);
					if($Bookk->getDesc() == NULL || $Bookk->getDesc() == '') {
						$Bookk->setDesc($Doc->getDesc()); }		
					

					if($Bookk->getIsAccepted() && $Bookk->getNo() == NULL) {
						$Bookk->setNewNo(); 
					}	
					
					$Bookk->save();	
					
					//foreach ($Bookk->getBookkEntries() as $BE => $BookkEntry) {
					//	$BookkEntry->setBookk($Bookk)->save();
					//}									
				}
*/ 				

}
