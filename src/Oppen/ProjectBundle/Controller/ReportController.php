<?php

namespace Oppen\ProjectBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use \PropelObjectCollection;
use Symfony\Component\Process\ProcessBuilder;
use Swift_Attachment;
use \Exception;
use ZipArchive;

use Oppen\ProjectBundle\Model\Year;
use Oppen\ProjectBundle\Model\Month;
use Oppen\ProjectBundle\Model\Account;
use Oppen\ProjectBundle\Model\File;
use Oppen\ProjectBundle\Model\FileCat;
use Oppen\ProjectBundle\Model\Doc;
use Oppen\ProjectBundle\Model\DocCat;
use Oppen\ProjectBundle\Model\Cost;
use Oppen\ProjectBundle\Model\CostDoc;
use Oppen\ProjectBundle\Model\CostDocIncome;
use Oppen\ProjectBundle\Model\Contract;
use Oppen\ProjectBundle\Model\Item;
use Oppen\ProjectBundle\Model\ItemColl;
use Oppen\ProjectBundle\Model\Report;
use Oppen\ProjectBundle\Model\ReportList;
use Oppen\ProjectBundle\Model\ReportEntry;

use Oppen\ProjectBundle\Model\YearQuery;
use Oppen\ProjectBundle\Model\MonthQuery;
use Oppen\ProjectBundle\Model\FileQuery;
use Oppen\ProjectBundle\Model\FileCatQuery;
use Oppen\ProjectBundle\Model\CostQuery;
use Oppen\ProjectBundle\Model\ContractQuery;
use Oppen\ProjectBundle\Model\DocQuery;
use Oppen\ProjectBundle\Model\DocCatQuery;
use Oppen\ProjectBundle\Model\ReportQuery;
use Oppen\ProjectBundle\Model\ReportEntryQuery;
use Oppen\ProjectBundle\Model\BookkQuery;
use Oppen\ProjectBundle\Model\BookkEntryQuery;
use Oppen\ProjectBundle\Model\AccountQuery;
use Oppen\ProjectBundle\Model\ParameterQuery;

use Oppen\ProjectBundle\Form\Type\ReportType;
use Oppen\ProjectBundle\Form\Type\ReportListType;

class ReportController extends Controller
{  
    public function listAction($year_id, Request $request) {
		$Year = YearQuery::create()->findPk($year_id);
		$pYear = $Year->getPrevious();
		
		$Month = MonthQuery::create()
			->filterByYear($Year)
			->orderByRank('asc')
			->findOne();
			
		$Reports = ReportQuery::create()->orderBySortableRank()->findByYear($Year);							
		$ReportList =  new ReportList($Year, $Month, $Year->getFromDate(), $Year->getToDate(), $Reports);
		$form = $this->createForm(new ReportListType($Year), $ReportList);	
        $form->handleRequest($request);
		
		$errors = array();
		if ($form->get('search')->isClicked()) {
			$Year = $form->get('Year')->getData();	
			return $this->redirect($this->generateUrl('oppen_reports', array(
					'year_id' => $Year->getId()) )); 			
		}

		if ($form->get('generateTurnOver')->isClicked()) {
			if(empty($ReportList->accNo)) {
				$errors[] = 'Pole Konto nie może być puste'; }
			else {
				return $this->render('OppenProjectBundle:Report:TurnOver.html.twig',
					array(	'ReportList'=> $ReportList,
							'Items' => $this->generateTurnOver($ReportList),
							'form' => $form->createView(),
							'buttons' => array('cancel') ));
			}			
		}
		
		if ($form->get('generateRecords')->isClicked()) {
			if(empty($ReportList->accNo)) {
				$errors[] = 'Pole Konto nie może być puste'; }
			else {				
				return $this->render('OppenProjectBundle:Report:Records.html.twig',
					array(	'ReportList'=> $ReportList,
							'Items' => $this->generateRecords($ReportList),
							'form' => $form->createView(),
							'buttons' => array('cancel') ));	
			}		
		}	
			
		if ($form->get('generateRegister')->isClicked()) {
			return $this->render('OppenProjectBundle:Report:Register.html.twig',
								array(	'ReportList'=> $ReportList,
										'Items' => $this->generateRegister($ReportList),
										'form' => $form->createView(),
										'buttons' => array('cancel') ));		
		}

		if ($form->get('generateOpenBalance')->isClicked()) {
		
			if (empty($pYear))	{
				$errors[] = 'Brak poprzedniego roku, aby utworzyć Bilans otwarcia';
			}
			else {	
				$BO = DocCat::create()
					->filterByYear($Year)
					->findOneBySymbol('BO');
					
				$I = Month::create()
					->orderByRank('asc')
					->findOneByYear($Year);
					
				$Doc = new Doc;
				$Doc->setDocCat($BO);
				$Doc->setMonth($I);
				
				$pAccounts=AccountQuery::create()
					->filterByIncOpenB(true)
					->findByYear( $pYear );
					
				foreach($pAccounts as $pAccount) {
					$Bookk = new Bookk;
					$Bookk->setDoc($Doc);
					
					$Account = AccountQuery::create()
						->filterByAccNo($pAccount->getAccNo())
						->findOneByYear( $Year );
					if(!($Account instanceOf Account)) {
						$message[] = 'Brak konta '.$Account->getAccNo().' w roku '.$Year->getName();
					}	
					else {
						$pFileCatLev1 = $pAccount->getFileCatLev1();
						$pFileCatLev2 = $pAccount->getFileCatLev2();
						$pFileCatLev3 = $pAccount->getFileCatLev3();
						
						if($pFileCatLev1 instanceOf FileCat) {
							$pFilesLev1 = FileQuery::create()->findByFileCat( $FileCatLev1 );
							foreach($FilesLev1 as $FileLev1) {
								if($FileCatLev2 instanceOf FileCat) {
									$FilesLev2 = FileQuery::create()->findByFileCat( $FileCatLev2 );
									foreach($FilesLev2 as $FileLev2) {
										if($FileCatLev3 instanceOf FileCat) {
											$FilesLev3 = FileQuery::create()->findByFileCat( $FileCatLev3 );
											foreach($FilesLev3 as $FileLev3) {
												$FileLev = array($FileLev1, $FileLev2 , $FileLev3 );
												$Bookk->addBEs($pYear, $Account, $FileLev);
											}
										}	
									}
								}
								else {		
									$FileLev = array($FileLev1, null, null);
									$Bookk->addBEs($psYear, $Account, $FileLev);
								}
							}
						}
						else {
							$FileLev = array(null, null, null);
							$Bookk->addBEs($pYear, $Account, $FileLev);
						}
					}
				}
				
				$DocCat = $BO;
				$showBookks = -1;				
				$desc = '*';
				$page = 1;	
				$as_doc_select = false; 
				$as_bookk_accept = true;
				$buttons = array();
				
				$DocList = $this->newDocList($Year, $Month, $DocCat, $showBookks, $desc, $page, $as_doc_select, $as_bookk_accept);										
				$form = $this->createForm(new DocListType($Year, null, null, $this->get('security.context')), $DocList);
				
				$helper = $this->container->get('oneup_uploader.templating.uploader_helper');
				$endpoint = $helper->endpoint('gallery');
				
				return $this->render('OppenProjectBundle:Doc:list.html.twig',array(
					'Year' => $Year,   
					'Month' => $Month,
					'DocCat' => $BO,    
					'form' => $form->createView(),
					'buttons' => $buttons,
					'return' => 'docs',
					'id1' => 0, 'id2' => 0, 'subtitle' => ''));	
			}	
		}
				
		return $this->render('OppenProjectBundle:Report:list.html.twig',
			array(	'Year' => $Year,
					'form' => $form->createView(),
					'buttons' => array(),
					'errors' => $errors ));
	}

	public function checkAccount(Year $prevYear, $Account, $FileLev) {
		$BookkEntry = new BookkEntry;
		$sum = array();
		for($side = 1; $side <= 2; $side++) {
			$BookkEntries = BookkEntryQuery::create()
				->select(array('sum'))
				->useBookkQuery()
					->filterByIsAccepted(1)  // to be activated in prod. ver
					->useDocQuery()
						->useMonthQuery()
							->filterByYear($prevYear)
						->endUse()
					->endUse()
				->endUse()
				->filterByAccNo($BookkEntry->createAccNo($Account, $FileLev))
				->filterBySide($side)		
				->withColumn('SUM(bookk_entry.value)', 'sum')
				->find();								
			$sum[$side]= $BookkEntries[0];
		} 
		$value = $sum[2]-$sum[1];
		if($value > 0) { $side=2;}
		if($value < 0) { $side=1; $value *= -1;}
		
		if($value > 0) {
			return $BookkEntry->setBE($Bookk, $side, $value, $Account, $FileLev);
		}
		else {return null;}
	}
	
	public function editAction($report_id, $year_id, Request $request) {	
		$msg = array('errors' => array(), 'warnings' => array(), 'messages' => array());
		$buttons = array('save','cancel'); 
		
		if($report_id == 0) {
			$Year = YearQuery::create()->findPk($year_id);
			$Report = new Report();
			$Report->setYear($Year);
			$RootEntry = new ReportEntry();
			$RootEntry->setReport($Report)
					  ->setName('Raport')
					  ->makeRoot();
			$ReportEntries = array($RootEntry);
		} else {
			$Report = ReportQuery::create()->findPk($report_id);
			$RootEntry = ReportEntryQuery::create()->findRoot($report_id);
			$ReportEntries = $RootEntry->getBranch(); 
			$Year = $Report->getYear();
			if(!$Report->getIsLocked()) {
				$buttons[] = 'delete';}
		}			
		
		$form = $this->createForm(new ReportType($Report), $Report);	
        $form->handleRequest($request);

		$redirect = false;
		
		if ($form->get('save')->isClicked()) {
			$Report = $form->getData();
			if($Report->getId() > 0) {
				$redirect = true;}
			$Report->save();	
		}
		if ($form->get('delete')->isClicked()) {
			$Report = $form->getData()->delete();	
			$redirect = true;
		}
		if ($form->get('cancel')->isClicked()) {	
			$redirect = true;
		}
		if($redirect) {
			return $this->redirect($this->generateUrl('oppen_settings', array(
				'tab_id' => 4,
				'year_id' => $Year->getId()) )); }
				
		return $this->render('OppenProjectBundle:Report:edit.html.twig',
			array(	'Year' => $Year,
					'form' => $form->createView(),
					'buttons' => $buttons,
					'errors' => $msg['errors'],
					'ReportEntries' => $ReportEntries ));	

	}
	
    public function reportAction($report_id, $method_id, Request $request) {

		$Report = ReportQuery::create()->findPk($report_id);
		$RootEntry = ReportEntryQuery::create()->findRoot($report_id);
		$Entries = $RootEntry->getDescendants();		
		$Template = $Report->getTemplate();
        $Params = ParameterQuery::create()->getAll();		
		
		$Year = $Report->getYear();		
		$Prev_Year = $Year->getPrevious();
		if($Prev_Year == null) {$Prev_Year = $Year;}

		$BO = DocCatQuery::create()->findBySymbol('BO');
							
		$Items = new PropelObjectCollection();
		$ItemColls = new PropelObjectCollection();
		$ICdata = array('year' => $Year->getName());
		$form = array('method_id'=>$method_id);
		$buttons = array('cancel');
		$msg = array('errors' => array(), 'warnings' => array(), 'messages' => array());
		
		$path = realpath($this->get('kernel')->getRootDir() . '/../web/uploads/gallery').'/';
		$total = null;			
		switch ($Report->getShortname()) {
			case 'pity11' :
				$ItemColls = $this->setItemColls($Year, null, $ItemColls, $Report, $RootEntry, $Params, $ICdata, $form, 'value');	 			
				$Report->ItemColls = $ItemColls;
				$total = $this->totalByProjects($Report);
				break;
			case 'pit4R' :
				$Items = $this->setItems($Year,  null, $Items, $Report, $RootEntry, $Params, $ICdata, $form, 'value');
				$Report->Items = $Items;	
				break;
			case 'RZiS' :
				$Items = $this->setItems($Prev_Year, null, $Items, $Report, $RootEntry, $Params, $ICdata, $form, 'prev_year');
				$Items = $this->setItems($Year     , null,      $Items, $Report, $RootEntry, $Params, $ICdata, $form, 'curr_year');
				$Report->Items = $Items;	
				break;
			case 'Bilans' :
				$Items = $this->setItems($Year, $BO,  $Items, $Report, $RootEntry, $Params, $ICdata, $form, 'beg_of_year');
				//$Items = $this->setItems($Prev_Year, null,  $Items, $Report, $RootEntry, $Params, $ICdata, $form, 'beg_of_year');
				$Items = $this->setItems($Year, null, $Items, $Report, $RootEntry, $Params, $ICdata, $form, 'end_of_year');
				$Report->Items = $Items;
				break;
		}		

		$form = $this->createForm(new ReportType(), $Report);	
		$form->handleRequest($request); 
		$return = true;	
		
		if($Report->ItemColls != null) {		
			foreach($form->get('ItemColls') as $ItemCollF) {
				if($ItemCollF->get('select')->getData() == 0) {
					$Report->ItemColls->remove($ItemCollF->getData()->id); }
			}		
		}
		
		$zipName = null;
		if ($form->get('downloadZIP')->isClicked()) {
			//$return = false; 

			$ReportShortname = $Report->getShortname();
        	$path = realpath($this->get('kernel')->getRootDir() . '/../web').'/';
        	$zipName = $ReportShortname.".zip";	
        	$zip = $this->createZIP($Report, $Entries, $Params, $form, $Template, $zipName);
    		
    		
     		$response = new Response();
    		$response->setContent(readfile($zipName));
    		$response->headers->set('Content-Type', 'application/zip');
			$response->headers->set('Content-Disposition', 'attachment; filename='.$zipName );
    		$response->headers->set('Content-Length', filesize($zipName));
    		
    		return $response;
    		
    					
			/*					
			$response = $this->render('OppenProjectBundle:Template:raw.html.twig',array(
				'contents' => $this->createSHScript($Report, $Entries, $Params, $form, $Template)));
			$response->headers->set('Content-Type', 'text/sh');
			$response->headers->set('Content-Disposition', 'attachment; filename='.$Report->getShortname().".sh");
			
			return $response;
			*/			
		}	
		
		$summary = null;
		if ($form->get('sendMails')->isClicked()) {
			$summary = $this->sendMails($Report, $Entries, $Params, $form, $Template, $path);
		}
		
		if ($form->get('cancel')->isClicked()) {
			return $this->redirect($this->generateUrl('oppen_reports', array(
			'year_id' => $Year->getId()) ));
		}
		if($return) {
			return $this->render('OppenProjectBundle:Report:'.$Report->getShortname().'.html.twig',
				array('form' => $form->createView(),
					  'buttons' => $buttons,
					  'Report' => $Report,
					  'total' =>$total,
					  'errors' => $msg['errors'],
					  'summary' => $summary,
					  'Params' => $Params,
					  'zipName' => $zipName,));}
	}

	// Fill Items of Report based on instruction from $Entry->data and data from $Items & $Params. 
	public function setItems($Year, $DocCats, $Items, $Report, $RootEntry, $Params, $ICdata, $form, $column) {
		if($RootEntry->getName() == 'root') {$Entries = $RootEntry->getDescendants();}
		else {$Entries = $RootEntry->getBranch();} 
		
		foreach($Entries as $Entry) {
			
			$id = $Entry->getId();
			if(!array_key_exists( $id, $Items)) { 
				$Item = new Item;
				$Item->id = $id;
				$Item->data = array('level' =>  $Entry->getLevel(),
									'no' =>     $Entry->getNo(),
									'symbol' => $Entry->getSymbol(),
									'name' =>   $Entry->getName());
				$Items->set($id, $Item);
			}
			
			if($Entry->isLeaf()) {
				$Items = $this->setItem($Year, $DocCats, $RootEntry, $Entry, $Items, $Params, $ICdata, $form, $column, true);
						
				$order = ReportEntryQuery::create()->orderByLevel('desc');
				$Ancestors = $Entry->getAncestors($order);	
				
				$Entries2 = array();
				$Entries2[] = $Entry;
				foreach($Ancestors as $Ancestor) 
					{ $Entries2[] = $Ancestor; }
				
				foreach($Entries2 as $Entry2) {
					if($Entry2->hasNextSibling() || $Entry2->getParent() == $RootEntry) {break;}
					$Entry2 = $Entry2->getParent();
					$Items = $this->setItem($Year, $DocCats, $RootEntry, $Entry2, $Items, $Params, $ICdata, $form, $column, false); 
				}
			}
		}	 		
		return $Items; 	
	}

	// Availabe conditional modificators: <, >
	// Available functions (prefix =): =sum:children, =sum:siblings 	
	// Availabe modificators: +, -
	// Availabe data types: account, node
	// Data separator: |
	public function setItem($Year, $DocCats,$RootEntry, $Entry, $Items, $Params, $ICdata, $form, $column, $isLeaf){
		$id = $Entry->getId();
		$Item = $Items->get($id); 
		$method_id = $form['method_id'];
		
		$formula = $Entry->getFormula();
		$symbol_pfx = substr($Entry->getSymbol(),0,1);
		
		$value = NULL;
		$func = '';		
		$if_oper = '';
		
		if(substr($formula,0,1) == '<') {
			$if_oper = '<';
			$formula = substr($formula, 1, strlen($formula)-1); }
		if(substr($formula,0,1) == '>') {
			$if_oper = '>';
			$formula = substr($formula, 1, strlen($formula)-1); }		
		
		if($isLeaf) {						
			$formula = explode('|',$formula);
			foreach($formula as $f) {
				if($f !== NULL && count(explode(':',$f)) > 1) {
					list($source, $p1, $p2, $p3, $p4) = explode(':',$f.':0:0:0');
					if(strpos($p4,':')) 
						{$p4 = substr($p4,1,strpos($p4,':')-1);}
						
					$sign = +1;							
					if(substr($source,0,1) == '+') {
							$source = substr($source, 1, strlen($source)-1); }
					if(substr($source,0,1) == '-') {
							$sign = -1;
							$source = substr($source, 1, strlen($source)-1); }
							
					switch (strtolower($source)) {
						case 'account' :
							// $p1 -> accNo	
							// $p2 -> side
							// $p3 -> month_name		
							// $p4 -> text in bookk desc
									
							$p2 = (int) $p2;						// side
							if($p2 == 0) {$side0 = 1; $side1 = 2;}
							else {$side0 = $p2; $side1 = $p2;}
							
							if(($p3 != '0') and ($p3 != '*')) {
								$Month = MonthQuery::create()
									->filterByYear($Year)
									->filterByName($p3)
									->findOne();
								if(!($Month instanceOf Month)) {
									throw new \Exception('Month '.$p3.' not found.');
								}
								$fromDate = $Month->getFromDate();	
								$toDate = $Month->getToDate();
							}
							elseif($p3 == '0') {
								$fromDate = $Year->getFromDate();	
								$toDate = $Year->getToDate();
							}
							elseif($p3 == '*') {
								$fromDate = YearQuery::create()->orderByFromDate('asc')->findOne()->getFromDate();	
								$toDate = YearQuery::create()->orderByToDate('desc')->findOne()->getToDate();	
							}
							
							for($side = $side0; $side <= $side1; $side++) {
								if($p2 == 0 && $side == 2) 	{ 
									$sign *= -1; 
								}
									
								$BookkEntries = BookkEntryQuery::create()
									->select(array('sum'))
									
									->filterByAccNo($p1)
									->filterBySide($side)	
																		
									->useBookkQuery()
										->filterByIsAccepted(1)  // to be activated in prod. ver
										->useDocQuery()
	
											->_if($method_id == 1)     //metoda kasowa
												->filterByPaymentDate(array('min'=>$fromDate, 'max'=>$toDate))
											->_elseif($method_id == 2) //metoda memoriałowa
												->filterByBookkingDate(array('min'=>$fromDate, 'max'=>$toDate))
											->_endif()
																	
											->_if($DocCats != null)
												->filterByDocCat($DocCats)
											->_endif()
											
										->endUse()
										
										->_if($p4 != '0')
											->filterByDesc('%'.$p4.'%')
										->_endif()										
										
									->endUse()
									
									->withColumn('SUM(bookk_entry.value)', 'sum')
									->find();								
								$value +=  $sign * $BookkEntries[0];
							} 
							
							
								if($symbol_pfx == 'p') { //pasywa 
									$value = -$value; 
								}																
							
							break;
						case 'node' :
							$criteria = ReportEntryQuery::create()->filterBySymbol($p1)->limit(1);
							if(count($RootEntry->getDescendants($criteria)) == 0 ) {
								throw new \Exception('Invalid Item formula - no Node with symbol: '.$p1);}
							list($Node) = $RootEntry->getDescendants($criteria);
							if($Node instanceOf ReportEntry) {
								$Node_Item = $Items->get($Node->getId());
								$value += $sign * $Node_Item->data[$column]; }
							break;	
						case 'item' :
							if(array_key_exists($p1, $ICdata)) {
								$value += $sign * $ICdata[$p1];}
							else {
								throw new \Exception('Invalid Item formula - no data with key: '.$p1);}
							break;
						case 'form' :
							if($form !== null && $form->get($p1) !== null) {
								if($form->get($p1)->getConfig()->getType()->getName() == 'checkbox') {
									$value .=  $form->get($p1)->getData() == 1 ? 'X' : ''; }
								else {
									$value .=  $form->get($p1)->getData(); }	
							}						
							break;	
						case 'param' :
							if(array_key_exists($p1,$Params)) {
								$value .=  $Params[$p1]; }
							break;
						case 'text' :
							$value .=  $p1; break;							
					}
				}
			}			
		}

		if(!$isLeaf ) {      
			$SubNodes = $Entry->getChildren();
			foreach($SubNodes as $SubNode) {						
				$SubItem = $Items->get($SubNode->getId());
				$value += $SubItem->data[$column];	}		
		}
		
		switch ($if_oper) {
			case '>' : $value = $value > 0 ? $value : 0; break;
			case '<' : $value = $value < 0 ? $value : 0; break;
		}		
		
		$Item->data[$column] = $value;
		$Item->data['func'] = $func;
		$Item->data['if_oper'] = $if_oper;		
		$Items->set($id, $Item);
		
		return $Items;
	}
	
	// Find all contracts for each contractor in Year and fill in $Item->data table. Returns $Items 
	public function setItemColls($Year, $DocCats, $ItemColls, $Report, $RootEntry, $Params, $ICdata, $form, $column) {
		$ReportShortname = $Report->getShortname();
		$path = realpath($this->get('kernel')->getRootDir() . '/../web/uploads/gallery').'/';

		$FileIDs = FileQuery::create()
				->select('PESEL')
				->groupByPESEL()
				->useFileCatQuery()
					->filterByAsContractor(1)
				->endUse()
				->orderByName()
				->find();				
			
		foreach($FileIDs as $FileID) {			
			$gross = 0;
			$netto = 0;
			$income_cost = 0;
			$tax = 0;
			$projects = array();
			
			$method_id = $form['method_id'];
			$Contracts = ContractQuery::create()
				->filterByTax(array('min'=> 0.01))
				->useFileQuery()
					->filterByPESEL($FileID)
				->endUse()
				->useDocQuery()
					->orderByDocumentDate()
					->_if($method_id == 1)     //metoda kasowa
						->filterByPaymentDate(array('min'=>$Year->getFromDate(), 'max'=>$Year->getToDate()))
					->_elseif($method_id == 2) //metoda memoriałowa
						->filterByBookkingDate(array('min'=>$Year->getFromDate(), 'max'=>$Year->getToDate()))
					->_endif()
				->endUse()
				->find();
				
			foreach($Contracts as $Contract) {	
				$gross += $Contract->getGross();
				$netto += $Contract->getNetto();
				$income_cost += $Contract->getIncomeCost();
				$tax += $Contract->getTax();
				
				$Project = $Contract->getCost()->getProject();
				$Project_name = $Project->getName();
				if(strlen($Project_name) > 30) {
					$Project_name = mb_substr($Project_name,0,30,'UTF-8').'...';}
				
				$projects[] = array('name' => $Project_name ,
									'id' => $Project->getId(),
									'gross' => $Contract->getGross(),
									'netto' => $Contract->getNetto(),
									'income_cost' => $Contract->getIncomeCost(),
									'tax' => $Contract->getTax());				
			}
			$File = $Contract->getFile(); // from last Contract

			$US_name = ''; 
			$US_accNo = 'XXXX'; 
			$US_address1 = ''; 
			$US_address2 = '';
			$SubFile = $File->getSubFile();
			if($SubFile instanceOf File ) {
				$US_name = $SubFile->getName();
				$US_accNo = $SubFile->__AccNo(4);
				$US_address1 = $SubFile->getAddress1();
				$US_address2 = $SubFile->getAddress2(); 
			} 

			$birth_date = ($File->getBirthDate() == NULL )?'':date_format($File->getBirthDate(), 'd-m-Y'); 
								
			$ItemColl = new ItemColl();	
			$ItemColl->id = $File->getId();
			$ICdata = array(									
				'year' => $Year->getName(),

				'first_name' => $File->getFirstName(),
				'last_name' => $File->getLastName(),
				'street' => $File->getStreet(),
				'house' => $File->getHouse(),
				'flat' => $File->getFlat(),
				'code' => $File->getCode(),
				'city' => $File->getCity(),
				'district2' => $File->getDistrict2(),
				'district' => $File->getDistrict(),
				'province' => $File->getProvince(),
				'country' => $File->getCountry(),
				'post_office' => $File->getPostOffice(),
				
				'address1' => $File->getAddress1(),
				'address2' => $File->getAddress2(),
						  
				'birth_date' => $birth_date,
				'PESEL' => $File->getPESEL(),
				
				'gross' => $gross, 
				'netto' => $netto, 
				'income_cost' => $income_cost, 
				'tax' => $tax,
				
				'US_name' => $US_name,
				'US_accNo' => $US_accNo,
				'US_address1' => $US_address1,
				'US_address2' => $US_address2,
				
				'email' => $File->getEmail(),
				'phone' => $File->getPhone(),
				
				'projects' => $projects,
				'filename' => '');
				
			$filename = $this->ItemColl2Filename($ReportShortname, $ICdata).'.pdf';
			
			if(file_exists($path.$filename)) {
				$ICdata['filename'] = $filename;
			}
			
			if($tax > 0) {	
				$ItemColl->data = $ICdata;
				$Items = new PropelObjectCollection();	
				$ItemColl->Items = $this->setItems($Year, $DocCats, $Items, $Report, $RootEntry, $Params, $ICdata, $form, $column);	
				$ItemColls->set($ItemColl->id, $ItemColl);
			}
		}	
		return $ItemColls;		
	}
	
	public function totalByProjects($Report) {
									
		$total = array( 'gross' => 0, 
						'netto' => 0, 
						'income_cost' => 0, 
						'tax' => 0, 
						'projects' => array());
		
		foreach($Report->ItemColls as $ItemColl) {
			
			foreach($ItemColl->data['projects'] as $p) {
				
				$p_id = $p['id'];
				
				if(!array_key_exists($p['id'], $total['projects'])) {
					$total['projects'][$p_id] = array(  'name' => $p['name'], 
														'gross' => 0, 
														'netto' => 0, 
														'income_cost' => 0, 
														'tax' => 0, );
				}
					
				$total['projects'][$p_id]['gross'] += $p['gross'];
				$total['projects'][$p_id]['netto'] += $p['netto'];
				$total['projects'][$p_id]['income_cost'] += $p['income_cost'];
				$total['projects'][$p_id]['tax'] += $p['tax'];	
				
				$total['gross'] += $p['gross'];
				$total['netto'] += $p['netto'];
				$total['income_cost'] += $p['income_cost'];
				$total['tax'] += $p['tax'];
			}		
		}	
		
		return $total;
	}

	public function createZIP($Report, $Entries, $Params, $form, $Template, $zipName) {
		$env = new \Twig_Environment(new \Twig_Loader_String());
		$form_data = array('objective' => $form->get('objective')->getData());
		$ReportShortname = $Report->getShortname();
		
        $zip = new \ZipArchive();	
		$zip->open($zipName,  ZipArchive::CREATE | ZipArchive::OVERWRITE);
		
		if($Report->ItemColls != null) {
			foreach($Report->ItemColls as $ItemColl) {
				
				$filename = $this->ItemColl2Filename($ReportShortname, $ItemColl->data).".xml";
				$contents = $env->render($Template->getData(),
						array('year' => $Report->getYear()->getName(),
							  'form' => $form_data,
							  'data' => $ItemColl->data,
							  'items'=> $ItemColl->Items,
							  'params'=> $Params));
				$zip->addFromString($filename,  $contents); 
			}
		}

		elseif($Report->Items != null) {
			$filename = $ReportShortname.".xml";
			$contents = $env->render($Template->getData(),
						array('year' => $Report->getYear()->getName(),
							  'form' => $form_data,
							  'items'=> $Report->Items,
							  'params'=> $Params));
			$zip->addFromString($filename,  $contents); 
		}
		
		$zip->close();
		return $zip;
	}	
	
	public function createSHScript($Report, $Entries, $Params, $form, $Template) {
		$env = new \Twig_Environment(new \Twig_Loader_String());
		$form_data = array('objective' => $form->get('objective')->getData());
		$ReportShortname = $Report->getShortname();
		
		$contents = '';
		if($Report->ItemColls != null) {
			foreach($Report->ItemColls as $ItemColl) {
				$filename = $this->ItemColl2Filename($ReportShortname, $ItemColl->data);
				$contents .= 
					'cat <<EOT > '.$filename.'.xml'.PHP_EOL.					
					$env->render($Template->getData(),
						array('year' => $Report->getYear()->getName(),
							  'form' => $form_data,
							  'data' => $ItemColl->data,
							  'items'=> $ItemColl->Items,
							  'params'=> $Params)).
					PHP_EOL.'EOT'.PHP_EOL;
			}
		}
		elseif($Report->Items != null) {
				$contents .= 
					'cat <<EOT > '.$ReportShortname.'.xml'.PHP_EOL.					
					$env->render($Template->getData(),
						array('year' => $Report->getYear()->getName(),
							  'form' => $form_data,
							  'items'=> $Report->Items,
							  'params'=> $Params)).
					'EOT'.PHP_EOL;
		}
		return $contents;
	}	

	public function sendMails($Report, $Entries, $Params, $form, $Template, $path) {
		$ReportShortname = $Report->getShortname();
		$mailer_user = $this->container->getParameter('mailer_user');
		$translator = $this->get('translator');
				
		if($Report->ItemColls != null) {
			foreach($Report->ItemColls as $ItemColl) {
				$attachment = $path.$ItemColl->data['filename'];

				$message = \Swift_Message::newInstance()
					->setSubject($form->get('mail_subject')->getData())
					->setFrom($mailer_user)
					->setTo($ItemColl->data['email'])
					->setBody($form->get('mail_body')->getData());
							
				if($form->get('mail_attach_report')->getData()) {
					$attachment = $path.$ItemColl->data['filename'];
					if(file_exists($attachment)) {
						$message->attach(Swift_Attachment::fromPath($attachment)); 
					} 
				}	
				$this->get('mailer')->send($message);
			}
		}
		
		$data = array(array($translator->trans('headers.common.name'), 
							$translator->trans('headers.common.email'),
							$translator->trans('headers.contracts.gross'),
							$translator->trans('headers.contracts.netto'),
							$translator->trans('headers.contracts.income_cost'),
							$translator->trans('headers.contracts.tax'),
							$translator->trans('headers.common.filename'), ));
		
		foreach($Report->ItemColls as $ItemColl) {
			
			$d = $ItemColl->data;
			
			$data[] = array('name' => $d['first_name'].' '.$d['last_name'],
							'email' => $d['email'],
							'gross' => $d['gross'],
							'netto' => $d['netto'],
							'income_cost' => $d['income_cost'],
							'tax' => $d['tax'],
							'filename' => $d['filename'], );
		}
		
		$summary = $this->renderView('OppenProjectBundle:Template:table.html.twig',
									  array('data' => $data ) );
		
		if($form->get('mail_summary')->getData()) {
			$message = \Swift_Message::newInstance()
				->setSubject('Wysyłka '.$Report->getName().' '.$Report->getYear())
				->setFrom($mailer_user)
				->setTo($this->get('security.context')->getToken()->getUser()->getEmail())
				->setBody($summary, 'text/html');	
			$this->get('mailer')->send($message);
		}
		
		return $summary;
	}	

	public function ItemColl2Filename($ReportShortname, $ICdata) {
		return str_replace(array('ą','Ą','ć','Ć','ę','Ę','ł','Ł','ń','Ń','ó','Ó','ś','Ś','ż','Ż','ź','Ź'),
						   array('a','A','c','C','e','E','l','L','n','N','o','O','s','S','z','Z','z','Z'),
						   $ReportShortname.'_' .trim($ICdata['first_name']).'_'.trim($ICdata['last_name']));
	}
				
	public function generateTurnOver($ReportList) {
		
		$Items = new PropelObjectCollection();

		$FirstMonth = MonthQuery::create()
			->filterByYear($ReportList->Year)
			->orderByRank('asc')
			->findOne();
								
		$id = 0;
		$Item = new Item;
		$Item->data = array('id' => $id,
							'name'=>'BO', 
							'Month' => $FirstMonth,
							'BOcrit' => \Criteria::EQUAL,
							'Side1'=>0, 'Side2'=>0, 
							'CumulSide1'=>0, 'CumulSide2'=>0);
		$Items->set($id, $Item);					

		$Months = MonthQuery::create()
			->filterByYear($ReportList->Year)
			//->filterByIsClosed(1)
			->orderByRank('asc')
			->limit(12)
			->find();
			
		foreach($Months as $Month) {
			$id++;
			$Item = new Item;
			$Item->data = array('id' => $id,
								'name'=> $Month->getName(), 
								'Month' => $Month, 
								'BOcrit' => \Criteria::NOT_EQUAL,
								'Side1'=>0, 'Side2'=>0, 
								'CumulSide1'=>0, 'CumulSide2'=>0);
			$Items->set($id, $Item);			
		}
		
		$CumulSide = array(1=>0, 2=>0);
		
		for($id2 =0; $id2 <= $id; $id2++) {
			$Item = $Items->get($id2);
			
			for($side = 1; $side <= 2; $side++) {
				
				$BEsum = BookkEntryQuery::create()
					->select(array('sum'))
					->filterBySide($side)			
					->useBookkQuery()
						->filterByIsAccepted(1)
						->filterByBookkingDate( array('min'=> $Item->data['Month']->getFromDate(),
												 	  'max'=> $Item->data['Month']->getToDate()) )
						->useDocQuery()
							->useDocCatQuery()
								->filterBySymbol('BO', $Item->data['BOcrit'])
							->endUse()						
							//->filterByMonth($Item->data['Month'])	
						->endUse()						
					->endUse()
					->filterByAccNo($ReportList->accNo)	
					->withColumn('SUM(bookk_entry.value)', 'sum')
					->find();	
												
									
				$sum =  $BEsum[0]+0; 
				$CumulSide[$side] += $sum; 
				
				$Item->data['Side'.$side] = $sum ;
				$Item->data['CumulSide'.$side] = $CumulSide[$side];
			} 
		} 
		
		$LastMonthItem = $Item;
		
		$id++;
		$Item = new Item;
		$Item->data = array('id' => $id,
							'name'=>'SUM', //'Saldo na koniec '.$LastMonthItem->data['name'], 
							'Side1'=>0, 'Side2'=>0, 
							'CumulSide1'=>0, 'CumulSide2'=>0);
		$balance = $CumulSide[2] - $CumulSide[1];
		if($balance > 0) { $Item->data['CumulSide2'] = $balance; }
		else { $Item->data['CumulSide1'] = -1*$balance; }
		$Items->set($id, $Item);
		
		return $Items;
	}	
	
	public function generateRecords($ReportList) {
		$Items = new PropelObjectCollection();
		$Year = $ReportList->Year;
		$FromDate = $ReportList->FromDate;
		$ToDate = $ReportList->ToDate;
		
		$FirstMonth = MonthQuery::create()
			->filterByYear($Year)
			->orderByRank('asc')
			->findOne();
			
		$id = 0;
		$BookkEntries = BookkEntryQuery::create()		
			->useBookkQuery()
				->filterByIsAccepted(1)  //***************************** to be enabled in prod
				->filterByBookkingDate( array('min'=> $FromDate, 'max'=> $ToDate) )
				->useDocQuery()
					//->useDocCatQuery()
						//->filterBySymbol('BO',\Criteria::NOT_EQUAL)
						//->filterByYear($Year)
					//->endUse()	
					//->filterByOperationDate(array('min'=>$FromDate, 'max'=>$ToDate))		
					->orderByOperationDate()			
				->endUse()						
			->endUse()
			->filterByAccNo($ReportList->accNo)	
			->find();			
			
		$SumSide = array(1=>0, 2=>0);
		foreach($BookkEntries as $BookkEntry) {
			$id++;
			$Item = new Item;
			$Side = $BookkEntry->getSide();
			$Item->data['Month'] = $BookkEntry->getBookk()->getDoc()->getMonth()->getName();						
			$Item->data['OperationDate'] = $BookkEntry->getBookk()->getDoc()->getOperationDate();						
			$Item->data['DocNo'] = $BookkEntry->getBookk()->getDoc()->getDocNo();						
			$Item->data['Desc'] = $BookkEntry->getBookk()->getDoc()->getDesc();
			$Item->data['Side1'] = 0;
			$Item->data['Side2'] = 0;
			$Item->data['Side'.$Side] = $BookkEntry->getValue();
			$SumSide[$Side] += $BookkEntry->getValue();

			$OppositeBEs = BookkEntryQuery::create()	
				->filterByBookk($BookkEntry->getBookk())
				->filterBySide(3-$Side)
				->find(); //$BookkEntry->getId(),\Criteria::NOT_EQUAL
			$OppositeAccNo = array();
			foreach($OppositeBEs as $OppositeBE) {
				$OppositeAccNo[] = $OppositeBE->getAccNo();}
			$Item->data['OppositeAccNo'] = implode('|', $OppositeAccNo);									
			$Items->set($id, $Item);
		}

		$id++;
		$Item = new Item;
		$Item->data['Month'] = '';						
		$Item->data['OperationDate'] = $ToDate;						
		$Item->data['DocNo'] = '';						
		$Item->data['Desc'] = 'SUMA';
		$Item->data['Side1'] = $SumSide[1];
		$Item->data['Side2'] = $SumSide[2];
		$Item->data['OppositeAccNo'] = '';
		$Items->set($id, $Item);
		
		return $Items;		
	}	

	public function generateRegister($ReportList) {
		$Items = new PropelObjectCollection();
		$Year = $ReportList->Year;
		$Month = $ReportList->Month;
		$FromDate = $ReportList->FromDate;
		$ToDate = $ReportList->ToDate;
		
		$BookkEntries = BookkEntryQuery::create()
			->joinWith('Bookk')
			->joinWith('Bookk.Doc')
			->joinWith('Doc.DocCat')
			->useBookkQuery()
				->filterByBookkingDate( array('min'=> $Month->getFromDate(),
											  'max'=> $Month->getToDate()) )
				->useDocQuery()
					->orderByRegIdx('asc')
					//->orderByDocumentDate('asc')
					
					->useDocCatQuery()
						->filterBySymbol('BO',\Criteria::NOT_EQUAL)
					->endUse()
				->endUse()
				->orderByBookkingDate('asc')
			->endUse()
			->find();

		$prev_Doc_id = 0;
		$prev_Bookk_id = 0;
		$D = 0;
		$Docs = array();
		foreach($BookkEntries as $BookkEntry) {
			$Bookk = $BookkEntry->getBookk();
			$Doc   = $Bookk->getDoc();
			$DocCat   = $Doc->getDocCat();
			
			$Doc_id = $Doc->getId();
			$Bookk_id = $Bookk->getId();
			
			if ($Doc_id != $prev_Doc_id) {
				$D++;
				$B = 0;	
				$Docs[$D] = array(
					'RegNo' => $Doc->getRegNo(),						
					'BookkingDate' => $Doc->getBookkingDate(),					
					'OperationDate' => $Doc->getOperationDate(),						
					'DocumentDate' => $Doc->getDocumentDate(),						
					'DocNo' => $Doc->getDocNo(),						
					'Desc' => $Doc->getDesc(),						
					'DocIdx' => $DocCat->getSymbol() .' '. $Doc->getDocIdx(),						
					'User' => $Doc->getUser(),						
					'Bookks' => array());		
			}
			if ($Bookk_id != $prev_Bookk_id) {
				$B++;
				$Docs[$D]['Bookks'][$B] = array(
					'BookkNo' => $Bookk->getNo(),
					'Desc' => $Bookk->getDesc(),
					'Sides'=> array(1=>array(), 2=>array()));
			}
			$Docs[$D]['Bookks'][$B]['Sides'][$BookkEntry->getSide()][] = array(
					'value'=>$BookkEntry->getValue(),
					'AccNo'=>$BookkEntry->getAccNo());
			
			$prev_Doc_id   = $Doc_id;
			$prev_Bookk_id = $Bookk_id;
		}

		foreach($Docs as $D => $Doc) {
			$Item = new Item;
			$Item->data = $Doc;
			$Items->set($D, $Item);		
		}
		 
		return $Items;		
	}	
		
}