<?php

namespace AppBundle\Controller;

use \Exception;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use \PropelObjectCollection;

use AppBundle\Model\Year;
use AppBundle\Model\Month;
use AppBundle\Model\Doc;
use AppBundle\Model\DocCat;
use AppBundle\Model\DocList;
use AppBundle\Model\File;
use AppBundle\Model\FileCat;
use AppBundle\Model\Project;
use AppBundle\Model\AccountFiles;
use AppBundle\Model\Bookk;
use AppBundle\Model\BookkEntry;

use AppBundle\Model\YearQuery;
use AppBundle\Model\MonthQuery;
use AppBundle\Model\AccountQuery;
use AppBundle\Model\DocQuery;
use AppBundle\Model\DocCatQuery;
use AppBundle\Model\FileQuery;
use AppBundle\Model\ProjectQuery;
use AppBundle\Model\BookkQuery;
use AppBundle\Model\ParameterQuery;

use AppBundle\Form\Type\DocListType;
use AppBundle\Form\Type\DocType;
use AppBundle\Form\Type\BookkDialogType;
use AppBundle\Form\Type\BookkEntryDialogType;

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
					
					// Bookk accept & number 			
					if(array_key_exists('acceptBookks',$DocListR)) {
					
						if(array_key_exists('SortedBookks',$DocR)) {	
							foreach($DocR['SortedBookks'] as $BookkR) {
								
								if(array_key_exists('is_accepted',$BookkR)) {
									
									$Bookk = BookkQuery::create()->findPk($BookkR['id']);
										
									if($Bookk->getIsAccepted() == false) {
										$Bookk->setIsAccepted(true)->save(); }
										
									if($Bookk->getIsAccepted() && $Bookk->getNo() == NULL) {
										$Bookk->setNewNo()->save(); }
								}					
							} 
						} 
					}
					
					// Document registration		
					$Doc = DocQuery::create()->findPk($DocR['id']);
					
					if($Doc instanceOf Doc) {
						$has_accepted_bookks = BookkQuery::create()->filterByDoc($Doc)->filterByIsAccepted(1)->count() > 0;
						
						if($has_accepted_bookks && empty($Doc->getRegNo()) ) {
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
									 								
		$security_context = $this->get('security.context');
		$disable_accepted_docs = ParameterQuery::create()->getOneByName('disable_accepted_docs');
		//$this->container->getParameter('disable_accepted_docs');
		$form = $this->createForm(new DocListType($Year, null, null, null, 
			$security_context, $disable_accepted_docs), $DocList);
		
        return $this->render('AppBundle:Doc:list.html.twig',array(
			'Year' => $Year,   
			'Month' => $Month,
			'DocCat' => $DocCat,    
			'form' => $form->createView(),
			'buttons' => $buttons,
			'project_id' => 0,
			'return' => 'docs',
			'id1' => 0,
			'id2' => 0,
			'subtitle' => ''));
			
    }

    public function editAction($doc_id, $month_id, $doc_cat_id, $return, $id1, $id2, Request $request) {
		
		$buttons = array('cancel','save');
		$msg = array('errors' => array(), 'warnings' => array());
		
		if($doc_id == 0) 
		{
			$Month = MonthQuery::create()->findPk($month_id);
			if(!($Month instanceOf Month)) 
				{ throw $this->createNotFoundException('The Month (id '.$month_id.') does not exist'); }
							
			$DocCat = DocCatQuery::create()->findPk($doc_cat_id);			
			if(!($DocCat instanceOf DocCat)) 
				{throw $this->createNotFoundException('The Doc (id '.$doc_id.') does not exist'); }
			
			$Doc = new Doc();
			$Doc->setMonth($Month);
			$Doc->setDocCat($DocCat);					
			$has_accepted_bookks = false;			
		} else {
			$Doc = DocQuery::create()->findPk($doc_id); 
			if(!($Doc instanceOf Doc)) 
				{ throw $this->createNotFoundException('The Doc (id '.$doc_id.') does not exist'); }			
			
			$has_accepted_bookks = BookkQuery::create()->filterByDoc($Doc)->filterByIsAccepted(1)->count() > 0;
			if(!$has_accepted_bookks) { $buttons[] = 'delete';}
		}
		
		$Year = $Doc->getMonth()->getYear();
		
		$header_sfx = '';
		if($return == 'project') {
			$Project = ProjectQuery::create()->findPk($id1); 
			if( $Project instanceOf Project) {
				$header_sfx = '('.$Project->getName().')';
			} 
		}
			
		$security_context = $this->get('security.context');
		$disable_accepted_docs = ParameterQuery::create()->getOneByName('disable_accepted_docs');
		
		$form = $this->createForm(new DocType($Year, true, $has_accepted_bookks, 
			$security_context, $disable_accepted_docs), $Doc);	
			
        $form->handleRequest($request); 

		$redirect = false;		
			
		if ($form->get('cancel')->isClicked()) 
		{ 
			$redirect = true; 
		}
			
		if ($form->get('delete')->isClicked()) 
		{
			if($has_accepted_bookks) 
			{
				$msg['errors'][] = 'Nie można usunąć dokumentu posiadającego zatwierdzone dekretacje'; 
			}
			
			if(empty($msg['errors'])) 
			{		
				$Doc->delete(); 
				$redirect = true; 
			} 
		}
										
		if ( ($form->get('save')->isClicked() )) 
		{			
			$User = $security_context->getToken()->getUser();
			$Doc->setUser($User);

			if(empty($msg['errors'])) {
				
				//save Doc and set new DocNo	
				if( empty($Doc->getDocIdx()) ) {
					$Doc->setNewDocIdx(); 
				}

				if( empty($Doc->getDocNo()) ) {
					$Doc->setNewDocNo(); 
				}		
							
				$Doc->save();				
				
	
				// document registration
				$has_accepted_bookks = BookkQuery::create()->filterByDoc($Doc)->filterByIsAccepted(1)->count() > 0;
				
				if($has_accepted_bookks && empty($Doc->getRegNo()) ) {
					$BookkingDate = $Doc->getOperationDate(); 
					$Doc->setNewRegIdxNo()
						->setBookkingDate($BookkingDate)
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
		
		return $this->render('AppBundle:Doc:edit.html.twig',array(
			'Year' => $Year,	
			'doc_id' => $doc_id,	
			'form' => $form->createView(),
			'errors' => $msg['errors'],
			'buttons' => $buttons,
			'header_sfx' => $header_sfx,
			'return' => $return,
			'id1' => $id1,
			'id2' => $id2,			
		));
						
	}

    public function updateFilesAction($doc_cat_id)
    {
		$DocCat = DocCatQuery::create()->findPk($doc_cat_id);
		$FileCat = $DocCat->getFileCat();
		
		$response = new JsonResponse();
		$files = array();	
			
		if($FileCat instanceOf FileCat) { 	

			foreach ($FileCat->getFiles() as $File) {
				$files[] = '<option value="'.$File->getId().'">'.$File.'</option>'; }
					
			$response->setData(array( $files, $FileCat->getName() ));
		} else {
			$response->setData(array( $files, 'Brak kartoteki' ));			
		}
		
		return $response;
	}

	static public function newDocList($Object, $Month, $DocCat, $is_accepted, $desc, $page,
									  $as_doc_select, $as_bookk_accept ) 
	{
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

		$DocsPager = $DocsC->paginate($page , $maxPerPage = 50);			
		foreach ($DocsPager as $k => $Doc) {
			$Docs->set($k, $Doc);}

		return new DocList($Year, $Month, $DocCat, $is_accepted, $desc, $page,
						$as_doc_select, $as_bookk_accept, 
						$Docs, $DocsPager);
	}

	static public function setDate($form, $field, $msg) 
	{
			
		$Project = $form->getData();
							   
		foreach ($form->get('SortedCosts') as $FCost) {
			
			$Cost = $FCost->getData();			
			foreach ($FCost->get('SortedCostDocs') as $FCostDoc) {
			
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
							if($form->get('bookking_date')->getData()) { 
								$bookking_date = $form->get('bookking_date')->getData();
								$Doc->setBookkingDate($bookking_date)->save();}
							break;
						case 'payment_deadline_date' :
							$payment_period = $form->get('payment_period')->getData();
							if($payment_period != '') {
								$payment_deadline_date = $document_date->modify('+2 week');
								$Doc->setPaymentDeadlineDate($payment_deadline_date)->save();}
							break;
						case 'payment_date' :
							if($form->get('payment_date')->getData()) {
								$payment_date = $form->get('payment_date')->getData();
								$Doc->setPaymentDate($payment_date)->save();}
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
							
		return $this->render('AppBundle:Doc:list3.html.twig',array('Year' => $Year, 'Docs' => $Docs ));
			
	}

}
