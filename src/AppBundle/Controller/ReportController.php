<?php

namespace AppBundle\Controller;

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

use AppBundle\Model\Year;
use AppBundle\Model\Month;
use AppBundle\Model\Account;
use AppBundle\Model\File;
use AppBundle\Model\FileCat;
use AppBundle\Model\Doc;
use AppBundle\Model\DocCat;
use AppBundle\Model\Cost;
use AppBundle\Model\CostDoc;
use AppBundle\Model\CostDocIncome;
use AppBundle\Model\Contract;
use AppBundle\Model\Item;
use AppBundle\Model\ItemColl;
use AppBundle\Model\Report;
use AppBundle\Model\ReportList;
use AppBundle\Model\ReportEntry;

use AppBundle\Model\YearQuery;
use AppBundle\Model\MonthQuery;
use AppBundle\Model\FileQuery;
use AppBundle\Model\FileCatQuery;
use AppBundle\Model\CostQuery;
use AppBundle\Model\ContractQuery;
use AppBundle\Model\DocQuery;
use AppBundle\Model\DocCatQuery;
use AppBundle\Model\ReportQuery;
use AppBundle\Model\ReportEntryQuery;
use AppBundle\Model\BookkQuery;
use AppBundle\Model\BookkEntryQuery;
use AppBundle\Model\AccountQuery;
use AppBundle\Model\ParameterQuery;

use AppBundle\Form\Type\ReportType;
use AppBundle\Form\Type\ReportListType;

class ReportController extends Controller
{  
    public function listAction($year_id, Request $request) {
		$Year = YearQuery::create()->findPk($year_id);
		$pYear = $Year->getPrevious();
		
		$Month = MonthQuery::create()
			->filterByYear($Year)
			->orderByRank('asc')
			->findOne();
		
		$method_id = 2; //Memorialowa
		
		$Reports = ReportQuery::create()->orderBySortableRank()->findByYear($Year);							
		$ReportList =  new ReportList($Year, $Month, $Year->getFromDate(), $Year->getToDate(), $method_id, $Reports);
		$Params = ParameterQuery::create()->getAll();
		
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
				return $this->render('AppBundle:Report:TurnOver.html.twig',
					array(	'ReportList'=> $ReportList,
							'Items' => $this->generateTurnOver($ReportList),
							'form' => $form->createView(),
							'buttons' => array('cancel'),
							'Params' => $Params ));
			}			
		}
		
		if ($form->get('generateRecords')->isClicked()) {
			if(empty($ReportList->accNo)) {
				$errors[] = 'Pole Konto nie może być puste'; }
			else {				
				return $this->render('AppBundle:Report:Records.html.twig',
					array(	'ReportList'=> $ReportList,
							'Items' => $this->generateRecords($ReportList),
							'form' => $form->createView(),
							'buttons' => array('cancel'),
							'Params' => $Params ));	
			}		
		}	
			
		if ($form->get('generateRegister')->isClicked()) {
			return $this->render('AppBundle:Report:Register.html.twig',
								array(	'ReportList'=> $ReportList,
										'Items' => $this->generateRegister($ReportList),
										'form' => $form->createView(),
										'buttons' => array('cancel'),
										'Params' => $Params ));		
		}

		if ($form->get('generateObligations')->isClicked()) {
			return $this->render('AppBundle:Report:Obligations.html.twig',
								array(	'ReportList'=> $ReportList,
										'Items' => $this->generateObligations($ReportList),
										'form' => $form->createView(),
										'buttons' => array('cancel'),
										'Params' => $Params ));		
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
								
				return $this->render('AppBundle:Doc:list.html.twig',array(
					'Year' => $Year,   
					'Month' => $Month,
					'DocCat' => $BO,    
					'form' => $form->createView(),
					'buttons' => $buttons,
					'return' => 'docs',
					'id1' => 0, 'id2' => 0, 'subtitle' => ''));	
			}	
		}
				
		return $this->render('AppBundle:Report:list.html.twig',
			array(	'Year' => $Year,
					'form' => $form->createView(),
					'method_id' => $method_id,
					'buttons' => array(),
					'errors' => $errors,
					'Params' => $Params ));
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
				
		return $this->render('AppBundle:Report:edit.html.twig',
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
		
		$ReportShortname = $Report->getShortname();
		$zipName = $ReportShortname.".zip";	
		$path = realpath($this->get('kernel')->getRootDir() . '/../web').'/';
		
		if ($form->get('downloadZIP')->isClicked()) {
        	
        	$msg = $this->createZIP($form, $msg, $zipName, $path, $Report, $Entries, $Params, $Template);     	
        	
        	if(empty($msg['errors'])) {


				$response = new Response(file_get_contents($zipName));
				$response->headers->set('Content-Type', 'application/zip');
				$response->headers->set('Content-Disposition', 'attachment;filename="' . $zipName . '"');
				$response->headers->set('Content-length', filesize($zipName));

				return $response;
				
			}		
		}	
				
		if ($form->get('cancel')->isClicked()) {
			return $this->redirect($this->generateUrl('oppen_reports', array(
			'year_id' => $Year->getId()) ));
		}
		if($return) {
			return $this->render('AppBundle:Report:'.$Report->getShortname().'.html.twig',
				array('form' => $form->createView(),
					  'buttons' => $buttons,
					  'Report' => $Report,
					  'total' =>$total,
					  'errors' => $msg['errors'],
					  'Params' => $Params,
					  'path' => $path,
					  'zipName' => $zipName,
					  ));}
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
									
								$BEsum = BookkEntryQuery::create()
									->select(array('sum'))
									
									->filterByAccNo($p1)
									->filterBySide($side)	
																		
									->useBookkQuery()
										->filterByIsAccepted(1)  // to be activated in prod. ver
										
										->useDocQuery()
	
											//metoda kasowa
											->_if($method_id == 1)     
												->filterByPaymentDate(array('min'=>$fromDate, 'max'=>$toDate))
												
											//metoda memoriałowa	
											->_elseif($method_id == 2) 
												->filterByBookkingDate(array('min'=>$fromDate, 'max'=>$toDate))
												
											->_endif()
																	
											->_if($DocCats != null)
												->filterByDocCat($DocCats)
											->_endif()
											
											// ****************************** Added 7/7/2016 **********************
											->useDocCatQuery()
												->filterByYear($Year)
											->endUse()
											// ******************************************************************
										->endUse()
										
										->_if($p4 != '0')
											->filterByDesc('%'.$p4.'%')
										->_endif()										
										
									->endUse()
									
									->withColumn('SUM(bookk_entry.value)', 'sum')
									->find();
								
								$sum =  $BEsum[0]+0; 
									
								if($symbol_pfx == 'p') { //pasywa 
									$sum = -$sum; 
								}										
																	
								$value +=  $sign * $sum;
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
		
		// Find all unique PESELS
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
					
					//metoda kasowa
					//->_if($method_id == 1)     
						->filterByPaymentDate(array('min'=>$Year->getFromDate(), 'max'=>$Year->getToDate()))
						
					//metoda memoriałowa	
					//->_elseif($method_id == 2) 
					//	->filterByBookkingDate(array('min'=>$Year->getFromDate(), 'max'=>$Year->getToDate()))
						
					//->_endif()
					
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
			$US_accNo = ''; 
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
				
			$filename = $this->ItemColl2Filename($ReportShortname, $ICdata).'.xml';
			
			//if(file_exists($path.$filename)) {
			//	$ICdata['filename'] = $filename;
			//	}
			
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

	public function createZIP( $form, $msg, $zipName, $path, $Report, $Entries, $Params,$Template) {
		$env = new \Twig_Environment(new \Twig_Loader_String());
		$form_data = array('objective' => $form->get('objective')->getData());
		$ReportShortname = $Report->getShortname();
		
        $zip = new \ZipArchive();	
        
		if(!($zip->open($zipName,  ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE)) {  //$path.
			$msg['errors'][] = 'Archiwum nie otworzone ani nadpisane.'; }
			
		else {
			
			$list = '';
			
			if($Report->ItemColls != null) {
				foreach($Report->ItemColls as $ItemColl) {
					
					if($list == '') {
						// Headers for Mail Merge Google Docs Plugin
						$list= 'First Name; Last Name; Email Address; File Attachments;';
						foreach($ItemColl->Items as $Item) {
							$list.= $Item->data['symbol'].';';
						}			
						$list.= 'Projects; Scheduled Date; Mail Merge Status;'.PHP_EOL;	
					}
						
					$tagged_items = '';
					foreach($ItemColl->Items as $Item) {
						$tag = $Item->data['symbol'];
						$val = $Item->data['value'];
						$tagged_items .= '<'.$tag.'>'.$val.'</'.$tag.'>'.PHP_EOL;
					}
					
					$filename = $this->ItemColl2Filename($ReportShortname, $ItemColl->data);
					$contents = $env->render($Template->getData(),
							array('year' => $Report->getYear()->getName(),
								  'form' => $form_data,
								  'data' => $ItemColl->data,
								  'items'=> $ItemColl->Items,
								  'tagged_items' => $tagged_items,
								  'params'=> $Params));
								  
					$zip->addFromString($filename.".xml",  $contents); 
					
					$list .= $ItemColl->data['first_name'].';'.
						 $ItemColl->data['last_name'].';'.
						 $ItemColl->data['email'].';'.
						 $filename.".pdf;";
					foreach($ItemColl->Items as $Item) {
						$list .= $Item->data['value'].';';
					}
					$projects = '';
					foreach($ItemColl->data['projects'] as $p) {
						$projects .= $p[name].' ('.$p['gross'].'PLN) | ';
					}
					$list .= $projects.';'.PHP_EOL;	
							 
					//$file = fopen($path.$filename.".xml", "w") or die("Unable to open file!");
					//fwrite($file, $contents);
					//fclose($file);							 				
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
			
			if(!empty($list)) {
				$zip->addFromString('list.csv',  $list); 
			}
			
			//$file = fopen($path.'list.csv', "w") or die("Unable to open file!");
			//fwrite($file, $list);
			//fclose($file);				
		
			$zip->close();
		} 
		
		$msg['zip'] = $zip;
		
		if(!file_exists($path.$zipName)) {
			$msg['errors'][] = 'Archiwum nie istnieje'; }
		
		return $msg;
	}	
	
	public function ItemColl2Filename($ReportShortname, $ICdata) {
		return str_replace(array('ą','Ą','ć','Ć','ę','Ę','ł','Ł','ń','Ń','ó','Ó','ś','Ś','ż','Ż','ź','Ź'),
						   array('a','A','c','C','e','E','l','L','n','N','o','O','s','S','z','Z','z','Z'),
						   $ReportShortname.'_'.trim($ICdata['first_name']).'_'.trim($ICdata['last_name'])); //.'_' .$ICdata['year']
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
						
						// by Bookk date
						->filterByBookkingDate( array('min'=> $Item->data['Month']->getFromDate(),
												 	  'max'=> $Item->data['Month']->getToDate()) )
												 	  
						->useDocQuery()
						
							// by Doc date
							//->filterByBookkingDate(array('min'=> $Item->data['Month']->getFromDate(),
							//					 	  'max'=> $Item->data['Month']->getToDate()) )
						
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
				->orderByBookkingDate()
				//->useDocQuery()
					//->useDocCatQuery()
						//->filterBySymbol('BO',\Criteria::NOT_EQUAL)
						//->filterByYear($Year)
					//->endUse()	
					//->filterByOperationDate(array('min'=>$FromDate, 'max'=>$ToDate))		
					//->orderByOperationDate()			
				//->endUse()						
			->endUse()
			->filterByAccNo($ReportList->accNo)	
			->find();			
			
		$SumSide = array(1=>0, 2=>0);
		foreach($BookkEntries as $BookkEntry) {
			$id++;
			$Item = new Item;
			$Side = $BookkEntry->getSide();
			//$Item->data['Month'] = $BookkEntry->getBookk()->getDoc()->getMonth()->getName();						
			$Item->data['OperationDate'] = $BookkEntry->getBookk()->getBookkingDate(); //getDoc()->getOperationDate();						
			$Item->data['DocNo'] = $BookkEntry->getBookk()->getDoc()->getDocNo();						
			$Item->data['Desc'] = $BookkEntry->getBookk()->getDesc(); //->getDoc()->getDesc();
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

	public function generateObligations($ReportList) {
		
		$Items = new PropelObjectCollection();
		
		$FromDate = $ReportList->FromDate;
		$ToDate = $ReportList->ToDate;
		$Account = $ReportList->Account;
		
		if(($Account instanceOf Account) && ($Account->getFileCatLev1() instanceOf FileCat)) {	
				
			$FileCatLev1 = $Account->getFileCatLev1();
			$Files = $FileCatLev1->getFiles();	
		
			foreach($Files as $id => $File) {
				
				$Item = new Item;
				$Item->data['name'] = $File->getName() ;
				$Item->data['FileCat'] = $FileCatLev1;
				
				$accNo = $Account->getAccNo().'-'.$File->__AccNo().'%';
				
				for($side = 1; $side <= 2; $side++) {
					
					$BEsum = BookkEntryQuery::create()
						->select(array('sum'))
						->filterBySide($side)			
						->filterByAccNo($accNo)
									
						->useBookkQuery()
							->filterByIsAccepted(1)
							->filterByBookkingDate(array('min'=> $FromDate, 'max'=> $ToDate) )
						->endUse()
						
						->withColumn('SUM(bookk_entry.value)', 'sum')
						->find();	
													
										
					$sum =  $BEsum[0]+0; 
					
					$Item->data['side'.$side] = $sum;
				} 
				$Items->set($id, $Item);
			
			} 

		}
			
		return $Items;
		
	}
		
}
