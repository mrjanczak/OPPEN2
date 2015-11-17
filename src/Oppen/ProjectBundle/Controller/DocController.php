<?php

namespace Oppen\ProjectBundle\Controller;

use \Exception;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use \PropelObjectCollection;

use Oppen\ProjectBundle\Model\Year;
use Oppen\ProjectBundle\Model\Month;
use Oppen\ProjectBundle\Model\Doc;
use Oppen\ProjectBundle\Model\DocCat;
use Oppen\ProjectBundle\Model\DocList;
use Oppen\ProjectBundle\Model\File;
use Oppen\ProjectBundle\Model\FileCat;
use Oppen\ProjectBundle\Model\Project;
use Oppen\ProjectBundle\Model\AccountFiles;
use Oppen\ProjectBundle\Model\Bookk;
use Oppen\ProjectBundle\Model\BookkEntry;

use Oppen\ProjectBundle\Model\FileQuery;
use Oppen\ProjectBundle\Model\DocQuery;
use Oppen\ProjectBundle\Model\DocCatQuery;
use Oppen\ProjectBundle\Model\YearQuery;
use Oppen\ProjectBundle\Model\MonthQuery;
use Oppen\ProjectBundle\Model\BookkQuery;
use Oppen\ProjectBundle\Model\AccountQuery;
use Oppen\ProjectBundle\Model\ParameterQuery;

use Oppen\ProjectBundle\Form\Type\DocListType;
use Oppen\ProjectBundle\Form\Type\DocType;
use Oppen\ProjectBundle\Form\Type\BookkDialogType;
use Oppen\ProjectBundle\Form\Type\BookkEntryDialogType;

class DocController extends Controller
{
    public function listAction($year_id, $month_id, $doc_cat_id, Request $request) {								
		// Form has to be created based on requested filter params at the very beginig of Action.
		// Otherwise requestHandle will delete Document ids not present in previous rendering.
		
		$as_doc_select = false; 
		$as_bookk_accept = true;
		$buttons = array();	
		
		// form is created later to preserve Bookks unchanged
				
		if ($request->isMethod('POST')) {
			$DocListR = $request->request->get('doc_list');
			$Year = YearQuery::create()->findPk($DocListR['Year']);
			$Month = MonthQuery::create()->findPk($DocListR['Month']);
			$DocCat = DocCatQuery::create()->findPk($DocListR['DocCat']);
			$showBookks = $DocListR['showBookks'];				
			$desc = $DocListR['desc'];
			$page = $DocListR['page'];
			if($showBookks == -2) {
				$as_bookk_accept = false;}
		}
		else { 
			$Year = YearQuery::create()->findPk($year_id);
			$Month = MonthQuery::create()->findPk($month_id);
			$DocCat = DocCatQuery::create()->findPk($doc_cat_id);
			$showBookks = -1;				
			$desc = '*';
			$page = 1;			
		}				
		
		// Bookks have to be accepted before form creation to enable/disable it in form

		if ($request->isMethod('POST')) {
			if(array_key_exists('Docs',$DocListR)) {					
				foreach($DocListR['Docs'] as $DocR) {
					
					if(array_key_exists('Bookks',$DocR)) {	
						foreach($DocR['Bookks'] as $BookkR) {
							if(array_key_exists('is_accepted',$BookkR)) {
								$Bookk = BookkQuery::create()->findPk($BookkR['id']);	
								$Bookk->setIsAccepted(1)->save();							
								if($Bookk->getIsAccepted() && $Bookk->getNo() == NULL) {
									$Bookk->setNewNo()->save(); 
									if(array_key_exists('deleteBookks',$DocListR)) {
										$Bookk->delete(); }	
									if(array_key_exists('acceptBookks',$DocListR)) {
										$Bookk->setIsAccepted(1)->save(); }									
								}									
							} 
						} 
					}
					
					//---------------------------------
					// Document registration			
					//---------------------------------		
					$Doc = DocQuery::create()->findPk($DocR['id']);
					if($Doc instanceOf Doc) {
						$hasAcceptedBookks = BookkQuery::create()->filterByDoc($Doc)->filterByIsAccepted(1)->count() > 0;
						if($hasAcceptedBookks && $Doc->getRegNo() == NULL) {
							$Doc->setNewRegIdxNo();
							
							$BookkingDate = $Doc->getOperationDate(); //now();
							
							$Doc->setBookkingDate($BookkingDate)
								->setUser($this->getUser())
								->save();
						}
					}
				} 
			} 
		}
	
		$DocList = $this->newDocList($Year, $Month, $DocCat, $showBookks, $desc, $page, 
									 $as_doc_select, $as_bookk_accept);		
									 								
		$securityContext = $this->get('security.context');
		$disable_accepted_docs = ParameterQuery::create()->getOneByName('disable_accepted_docs');
		//$this->container->getParameter('disable_accepted_docs');
		$form = $this->createForm(new DocListType($Year, null, null, null, 
			$securityContext, $disable_accepted_docs), $DocList);
		
        return $this->render('OppenProjectBundle:Doc:list.html.twig',array(
			'Year' => $Year,   
			'Month' => $Month,
			'DocCat' => $DocCat,    
			'form' => $form->createView(),
			'buttons' => $buttons,disable_accepted_docs
			'project_id' => 0,
			'return' => 'docs',
			'id1' => 0,
			'id2' => 0,
			'subtitle' => ''));
			
    }

    //$return = 'docs', $id1 = 0, $id2 = 0
    public function editAction($doc_id, $month_id, $doc_cat_id, $return, $id1, $id2, Request $request) {
		$buttons = array('cancel','save');
		$msg = array('errors' => array(), 'warnings' => array());
		
		if($doc_id == 0) {
			$Month = MonthQuery::create()->findPk($month_id);
			if(!($Month instanceOf Month)) { throw $this->createNotFoundException('The Month (id '.$month_id.') does not exist'); }			
			$DocCat = DocCatQuery::create()->findPk($doc_cat_id);			
			if(!($DocCat instanceOf DocCat)) {throw $this->createNotFoundException('The Doc (id '.$doc_id.') does not exist'); }
			
			$Doc = new Doc();
			$Doc->setMonth($Month);
			$Doc->setDocCat($DocCat);					
			$hasAcceptedBookks = false;			
		} else {
			$Doc = DocQuery::create()->findPk($doc_id); 
			if(!($Doc instanceOf Doc)) { throw $this->createNotFoundException('The Doc (id '.$doc_id.') does not exist'); }			
			
			$hasAcceptedBookks = BookkQuery::create()->filterByDoc($Doc)->filterByIsAccepted(1)->count() > 0;
			if(!$hasAcceptedBookks) { $buttons[] = 'delete';}
		}
		$Year = $Doc->getMonth()->getYear();
				
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

		$Account = AccountQuery::create()->filterByYear($Year)->orderByAccNo()->findOne();
		$Bookk = new Bookk();
		$Bform = $this->createForm(new BookkDialogType($Year), $Bookk); 
		
		$BookkEntry = new BookkEntry();
		$BookkEntry->setBE($Bookk, 0, 0, $Account, array(1=>null, 2=>null, 3=>null) );
		$BEform = $this->createForm(new BookkEntryDialogType($Year, $Account), $BookkEntry); 

		$securityContext = $this->get('security.context');
		$disable_accepted_docs = ParameterQuery::create()->getOneByName('disable_accepted_docs');
		//$this->container->getParameter('disable_accepted_docs');	
		$form = $this->createForm(new DocType($Year, true, $hasAcceptedBookks, 
			$securityContext, $disable_accepted_docs), $Doc);	
        $form->handleRequest($request); 

		$redirect = false;			
		if ($form->get('cancel')->isClicked()) { 
			$redirect = true; }	
		if ($form->get('delete')->isClicked()) {
			if($hasAcceptedBookks) {
				$msg['errors'][] = 'Nie można usunąć dokumentu posiadającego zatwierdzone dekretacje'; }
			if(empty($msg['errors'])) {		
				$Doc->delete(); 
				$redirect = true; 
			} 
		}
										
		if ( ($form->get('save')->isClicked() )) { 
					
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
			
			$FromDate = $Doc->getMonth()->getFromDate();
			$ToDate = $Doc->getMonth()->getToDate();
			foreach ($Doc->getBookks() as $B => $Bookk) {
				if($Bookk->getBookkingDate() < $FromDate || $Bookk->getBookkingDate() > $ToDate ) {
					$msg['errors'][] = 'Data księgowania dekretacji musi zawierać się w miesiącu dokumentu';}}
					
			if(empty($msg['errors'])) {
				//save Doc and set new DocNo
					
				if($Doc->getDocIdx() == NULL ) {
					$Doc->setNewDocIdx(); 
				}

				if($Doc->getDocNo() == NULL || $Doc->getDocNo() == '') {
					$Doc->setNewDocNo(); 
				}		
							
				$Doc->save();				
				
				//save new Bookks & BookkEntries				
				foreach ($Doc->getBookks() as $B => $Bookk) {
					$Bookk->setDoc($Doc);
					if($Bookk->getDesc() == NULL || $Bookk->getDesc() == '') {
						$Bookk->setDesc($Doc->getDesc()); }		
					$Bookk->save();

					if($Bookk->getIsAccepted() && $Bookk->getNo() == NULL) {
						$Bookk->setNewNo(); 
					}	
						
					foreach ($Bookk->getBookkEntries() as $BE => $BookkEntry) {
						$BookkEntry->setBookk($Bookk)->save();
					}									
				}
				
				//---------------------------------------------
				// document registration
				//---------------------------------------------
				$hasAcceptedBookks = BookkQuery::create()->filterByDoc($Doc)->filterByIsAccepted(1)->count() > 0;
				if($hasAcceptedBookks && $Doc->getRegNo() == NULL) {
					$Doc->setNewRegIdxNo();
					
					$BookkingDate = $Doc->getOperationDate(); //now();
					
					$Doc->setBookkingDate($BookkingDate)
						->setUser($this->getUser())
						->save();
				}			
								
				$redirect = true; 
			}
 		}
	
		if ($redirect) {
			switch ($return) {
				case 'docs' : 
					return $this->redirect($this->generateUrl('oppen_docs', array(
					'year_id'    => $Year->getId(),
					'month_id'   => $month_id,
					'doc_cat_id' => $doc_cat_id,
					'page'       => 1) )); 
				case 'project' : 
					return $this->redirect($this->generateUrl('oppen_project', array(
					'project_id'   => $id1,
					'tab_id' => $id2,
					'year_id' => $Year->getId()) ));			
				case 'contract' : 
					return $this->redirect($this->generateUrl('oppen_contract', array(
					'contract_id'   => $id1,
					'cost_id' => $id2) ));
			}
		}		
		return $this->render('OppenProjectBundle:Doc:edit.html.twig',array(
			'Year' => $Year,		
			'form' => $form->createView(),
			'Bform' => $Bform->createView(),
			'BEform' => $BEform->createView(),
			'errors' => $msg['errors'],
			'buttons' => $buttons));
						
	}

	static public function newDocList($Object, $Month, $DocCat, $is_accepted, $desc, $page,
									  $as_doc_select, $as_bookk_accept ) {
		$Project = null;
		$Year = null;
		
		if($Object instanceOf Project) {
			$Project = $Object;	 
			$Year = $Project->getYear(); }									  
		elseif($Object instanceOf Year) { 
			$Year = $Object; }
		else {
			return null;}
							  
		$Docs = new PropelObjectCollection();
		$DocsC = DocQuery::create()
			// find documents of project (Project>Documents)
			->_if($Project instanceOf Project)		
				->useBookkQuery()
					->filterByProject($Project)
				->endUse()
			->_endif()
			
			// find documents from month or whole year
			->_if($Month instanceOf Month) 	
				->filterByMonth($Month)
			->_elseif($Year instanceOf Year)	
				->useMonthQUery()
					->filterByYear($Year)
					->orderByRank()
				  ->endUse()
			->_endif()
			
			->_if($DocCat instanceOf DocCat) 
				->filterByDocCat($DocCat)
			->_endif()	
					
			->_if($is_accepted > -1) 
				->useBookkQuery()
					->filterByIsAccepted($is_accepted)
				->endUse()
			->_endif()
			
			->_if($desc != '*') 		  
				->filterByDesc($desc)
			->_endif()
			
			->useDocCatQuery()
				->orderBySymbol()
			->endUse()
			
			->orderByDocNo()
			->groupById();

		$DocsPager = $DocsC->paginate($page , $maxPerPage = 30);			
		foreach ($DocsPager as $k => $Doc) {
			$Docs->set($k, $Doc);}

		return new DocList($Year, $Month, $DocCat, $is_accepted, $desc, $page,
						$as_doc_select, $as_bookk_accept, 
						$Docs, $DocsPager);
	}

	static public function setDate($form, $field, $msg) {
			
		$Project = $form->getData();
							   
		foreach ($form->get('Costs') as $FCost) {
			
			$Cost = $FCost->getData();			
			foreach ($FCost->get('CostDocs') as $FCostDoc) {
			
				if($FCostDoc->get('select')->getData()) {
					
					$CostDoc = $FCostDoc->getData();
					$Doc = $CostDoc->getDoc();
					$document_date = $Doc->getDocumentDate();
					
					$D = $Doc->getId();
					if(!($Doc instanceOf Doc)) { 
						$msg['errors'][] = 'Brak dokumentu w grupie kosztów '.$Cost->getName(); 
						return $msg; 
					}
					
					switch($field) {
						case 'bookking_date' :
							if($form->get('bookking_date')->getData()) {  // ==0 to remove
								$Doc->setBookkingDate($form->get('bookking_date')->getData())->save();}
							else {
								$Doc->setBookkingDate($Doc->getReceiptDate())->save();}		
							break;
						case 'payment_deadline_date' :
							$payment_period = $form->get('payment_period')->getData();
							if($payment_period != '') {
								$payment_deadline_date = $document_date->modify('+2 week');
								$Doc->setPaymentDeadlineDate($payment_deadline_date)->save();}
							break;
						case 'payment_date' :
							if($form->get('payment_date')->getData()) {
								$Doc->setPaymentDate($form->get('payment_date')->getData())->save();}
							break;	
					}
				}
			}
		}
		return $msg;
	}

	public function fileNo($AccNo, $Name) {
		return (string) substr('00'.$AccNo,0,3).' - '.$Name;
	}	

	public function compareById($Old, $New) {
			if ($Old->getId() === $New->getId()) {
				return 0; 
			} else return 1;
	}

	public function repairAction($year_id) {
		
		$Year = YearQuery::create()->findPk($year_id); 	
		$Docs  = DocQuery::create()
			->useMonthQuery()
				->filterByYear($Year)
			->endUse()
		->find();

		foreach($Docs as $Doc) {
			
			$document_date = $Doc->getDocumentDate();
				 	
			if($Doc->getBookkingDate() == null) {
				$Doc->setBookkingDate($document_date)->save();
			}

			if($Doc->getPaymentDate() == null) {
				$Doc->setPaymentDate($document_date)->save();
			}
			
			if($Doc->getPaymentDeadlineDate() == null) {
				$Doc->setPaymentDeadlineDate($document_date)->save();
			}	
		}
		
		$Docs  = DocQuery::create()
			->useMonthQuery()
				->filterByYear($Year)
			->endUse()
		->find();
							
		return $this->render('OppenProjectBundle:Doc:list3.html.twig',array('Year' => $Year, 'Docs' => $Docs ));
			
	}

}
