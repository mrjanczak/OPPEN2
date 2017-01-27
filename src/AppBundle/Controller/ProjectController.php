<?php

namespace AppBundle\Controller;

use \Propel;
use \PropelObjectCollection;
use Symfony\Component\Process\ProcessBuilder;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Yaml\Parser;
use ZipArchive;

use AppBundle\Controller\IncomeController;
use AppBundle\Controller\CostController;
use AppBundle\Controller\ContractController;

use AppBundle\Model\Year;
use AppBundle\Model\Month;
use AppBundle\Model\Bookk;
use AppBundle\Model\BookkEntry;
use AppBundle\Model\File;
use AppBundle\Model\FileCat;
use AppBundle\Model\Doc;
use AppBundle\Model\DocCat;
use AppBundle\Model\DocList;
use AppBundle\Model\Project;
use AppBundle\Model\ProjectList;
use AppBundle\Model\Income;
use AppBundle\Model\IncomeDoc;
use AppBundle\Model\Cost;
use AppBundle\Model\CostIncome;
use AppBundle\Model\CostDoc;
use AppBundle\Model\CostDocIncome;
use AppBundle\Model\Task;
use AppBundle\Model\Account;

use AppBundle\Model\YearQuery;
use AppBundle\Model\MonthQuery;
use AppBundle\Model\FileQuery;
use AppBundle\Model\FileCatQuery;
use AppBundle\Model\DocQuery;
use AppBundle\Model\DocCatQuery;
use AppBundle\Model\ProjectQuery;
use AppBundle\Model\IncomeQuery;
use AppBundle\Model\IncomeDocQuery;
use AppBundle\Model\CostQuery;
use AppBundle\Model\CostIncomeQuery;
use AppBundle\Model\CostDocQuery;
use AppBundle\Model\CostDocIncomeQuery;
use AppBundle\Model\ContractQuery;
use AppBundle\Model\BookkQuery;
use AppBundle\Model\BookkEntryQuery;
use AppBundle\Model\TemplateQuery;
use AppBundle\Model\ParameterQuery;

use AppBundle\Form\Type\ProjectType;
use AppBundle\Form\Type\ProjectListType;
use AppBundle\Form\Type\IncomeType;
use AppBundle\Form\Type\CostType;
use AppBundle\Form\Type\DocListType;
use AppBundle\Form\Type\TaskDialogType;

class ProjectController extends Controller
{
	public function listAction($year_id, Request $request) { 

		if ($request->isMethod('POST')) {
			$ProjectListR = $request->request->get('project_list');
			$Year = YearQuery::create()->findPk($ProjectListR['Year']);			
			$search_name = $ProjectListR['search_name'];			
		}
		else { 
			$ProjectListR = array();
			$Year = YearQuery::create()->findPk($year_id);
			$search_name = '*';
		}
		
		$ProjectList = new ProjectList($Year, $search_name);
		$Projects = ProjectQuery::create()
			->_if($Year instanceOf Year)
				->filterByYear($Year)
			->_endif()
			
			->_if($search_name != '*') 
				->filterByName($search_name)
			->_endif()
			
			->orderByFromDate()
			->find();
						
 		$form = $this->createForm(new ProjectListType(), $ProjectList); 		
        //$form->handleRequest($request); 
        
        return $this->render('AppBundle:Project:list.html.twig',array(
			'form' => $form->createView(),
			'Year' => $Year,
			'Projects' => $Projects,
			'status_list' => array('-1' => 'w przygotowaniu', '0' => 'rozpoczęty', '1' => 'zamknięty')));
    }
   
    public function editAction($project_id, $tab_id, $year_id,  Request $request)  {	
		
		
		
		$buttons = array('cancel','save');
		$tabs = array(1=>'desc');
				
		if($project_id == 0) {
			$Year = YearQuery::create()->findPk($year_id);
			$Project = new Project();
			$Project->setYear($Year)
					->setStatus(-1);
			$CostFileCat = null;
		} else { 
			$Project = ProjectQuery::create()->findPk($project_id);
			$Year = $Project->getYear();
			$CostFileCat = $Project->getCostFileCat();
			$tabs = array(1=>'desc', 2=>'incomes', 3=>'costs', 4=>'contracts', 5=>'documents');		
			if($tab_id == 1) {
				$buttons[] = 'delete';				
			}			
		}
				
		$Month = MonthQuery::create()->findOneByIsActiveAndYear(1, $Year); 
		if(!($Month instanceOf Month)) { 
			$Month = MonthQuery::create()->orderByFromDate()->findOneByYear($Year); }			
		
		$DocCat = DocCatQuery::create()->findOneByYear($Year);
		$DocCat_asIncome = DocCatQuery::create()->findOneByAsIncomeAndYear(1, $Year);
		$DocCat_asCost   = DocCatQuery::create()->findOneByAsCostAndYear(1, $Year);
		$FileCat_asContractor = FileCatQuery::create()->findOneByAsContractorAndYear(1, $Year);
		
		$Parser = new Parser;
		$Params = ParameterQuery::create()->getAll();	
				
		$BookingTemplates = array();
		$Templates = TemplateQuery::create()->orderByName()->findByAsBooking(true);
		foreach($Templates as $Template) {
			$BookingTemplates[$Template->getSymbol()] = $Parser->parse($Template->getData());}		
		
		$Templates = TemplateQuery::create()->orderByName()->findByAsTransfer(true);
		foreach($Templates as $Template) {
			$TransferTemplates[$Template->getSymbol()] = $Parser->parse($Template->getData());}
		
		$handleRequest = true;
		$File = $Project->getFile();
			
		$balance = array('incomes'=>0, 'costs'=>0, 'result'=>0);
		// Calculate balance
		if (($tab_id == 1) && ($File instanceOf File)) {
			
			$accNo_ = array('incomes'=>'7*', 'costs'=>'5*');
			foreach ($accNo_ as $col => $accNo) {
				$sum = BookkEntryQuery::create()
					->select(array('total'))
					->withColumn('SUM(bookk_entry.value)', 'total')
					->filterByAccNo( $accNo )	
					->filterByFileLev1( $Project->getFile() )	
					->useBookkQuery()
						->filterByIsAccepted(1)  
					->endUse()
					->findOne();
					
				$balance[$col] = $sum;
			}				
			
			// Koszty administracyjne - dekretacje posiadające 1-szą kartotekę z KA 
			$KA = FileCatQuery::create()->findOneBySymbolAndYear('KA', $Year);
			if($Project->getCostFileCat() == $KA) {
				
				$balance['costs'] += BookkEntryQuery::create()
					->select(array('total'))
					->withColumn('SUM(bookk_entry.value)', 'total')
					->filterByAccNo( $accNo_['costs'] )	
					->useAccountQuery()
						->filterByFileCatLev1( $KA )
					->endUse()				
					->useBookkQuery()					
						->filterByIsAccepted(1)  
					->endUse()
					->findOne();				
			}					
				
			$balance['result'] = $balance['incomes']-$balance['costs'];
		}
		
		// Documents tab 	
		if ($tab_id == 5) {
			$buttons = array('cancel');	
			
			// DocList filter refresh
			$as_doc_select = false; 
			$as_bookk_accept = true;
						
			if ($request->isMethod('POST') ) {
				
				$ProjectR = $request->request->get('project');
				$DocListR = $ProjectR['DocList'];
				$handleRequest = false;
				
				$Year = YearQuery::create()->findPk($DocListR['Year']);
				$Month = MonthQuery::create()->findPk($DocListR['Month']);
				$DocCat = DocCatQuery::create()->findPk($DocListR['DocCat']);
				
				$showBookks = $DocListR['showBookks'];
				if($showBookks == -2) {
					$as_bookk_accept = false;}				
				$desc = $DocListR['desc'];
				$page = $DocListR['page'];
				
				// Bookks have to be accepted before form creation to enable/disable it in form
				
				if(array_key_exists('acceptBookks',$DocListR)) {
					
					if(array_key_exists('Docs',$DocListR)) {					
						foreach($DocListR['Docs'] as $DocR) {
							
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
					}
				}
				 				
			} else {  // not POST
				$showBookks = -1;				
				$desc = '*';
				$page = 1;			
			}		
			
			$FirstMonth = MonthQuery::create()
							->useDocQuery()
								->useBookkQuery()
									->filterByProject($Project)
								->endUse()
							->endUse()
							->orderByFromDate()
							->findOne(); 
			if($FirstMonth instanceOf Month) {
				//$Month = $FirstMonth; 
			}
							
			$FirstDocCat = DocCatQuery::create()
							->useDocQuery()
								->useBookkQuery()
									->filterByProject($Project)
								->endUse()
							->endUse()
							->orderById() 
							->findOne(); 
			if($FirstDocCat instanceOf DocCat) {
				//$DocCat = $FirstDocCat; 
			}
													 
			$Project->DocList = DocController::newDocList($Project, $Month, $DocCat, 
				$showBookks, $desc, $page, $as_doc_select, $as_bookk_accept);
		}
		
		// Project form
		$securityContext = $this->get('security.context');
		$disable_accepted_docs = ParameterQuery::create()->getOneByName('disable_accepted_docs');
		//$this->container->getParameter('disable_accepted_docs');
		
		$form = $this->createForm(new ProjectType($tab_id, $Year, 
			$securityContext, $disable_accepted_docs), $Project); 
		 				
        if($handleRequest) {
			$form->handleRequest($request); }
		
		$msg = array('errors' => array(), 'warnings' => array());
		$return = 'project';
		$redirect = false;
		$new_tab_id = $tab_id;
		$PaymentMonth = null;
		
		if ($form->isSubmitted()){
			
			if ($form->get('cancel')->isClicked()) { 
				$redirect = true; $return = 'projects'; }	

			if ($form->get('delete')->isClicked()) { 
				$Project->delete(); 
				$redirect = true; $return = 'projects'; }
					
			if ($form->get('save')->isClicked())  {	
				
				// Create new Project File if not exists			
				if (($tab_id == 1) && ($Project->getFile() === null) && ($Project->getStatus() > -1)) 	{	
					$FileCat = FileCatQuery::create()->filterByYear($Year)->findOneByAsProject(1);
					$file_acc_no = FileQuery::create()->findByFileCat($FileCat)->count() + 1;
					$File = new File();
					$File->setName($Project->getName());
					$File->setAccNo($file_acc_no);	
					$File->setFileCat($FileCat);				
					$Project->setFile($File);
				}
				// New project
				if (($tab_id == 1) && ($Project->getId() == 0)) {
					$redirect = true; }	
				
				// Save incomes
				if ($tab_id == 2 ) {
					foreach($Project->SortedIncomes as $Income) {
						foreach($Income->SortedIncomeDocs as $IncomeDoc) {
							$IncomeDoc->save();
						}
					}
				}
				
				// Check & Save costs
				if ($tab_id == 3 ) {
					foreach($Project->SortedCosts as $Cost) {
						foreach($Cost->SortedCostIncomes as $CostIncome) {
							$CostIncome->save();
						}
						foreach($Cost->SortedCostDocs as $CostDoc) {
							$CDIsum = 0;
							foreach($CostDoc->SortedCostDocIncomes as $CostDocIncome) {
								$CDIsum += $CostDocIncome->getValue();	
							}
							if($CDIsum <> $CostDoc->getValue()) {
								$msg['errors'][] = 'Łączna kwota nie odpowiada cząstkowym dla dokumentu '.$CostDoc->getDoc()->getDocNo();
							} else {								
								$CostDoc->save();
								foreach($CostDoc->SortedCostDocIncomes as $CostDocIncome) {
									$CostDocIncome->save();	
								}								
							}
						}
					}
				}
				
				// Re-calculate Tax & Netto in all Contracts & Save
				if ($tab_id == 4) {
					foreach($Project->SortedCosts as $Cost) {
						foreach($Cost->SortedContracts as $Contract) {
															
							$gross = $Contract->getGross();
							$cost_coef = $Contract->getCostCoef();
							$tax_coef = $Contract->getTaxCoef();
							$income_cost = round( $gross * $cost_coef/100, 2);
							$tax = round( $income_cost * $tax_coef/100, 0);
							
							$Contract->setIncomeCost( $income_cost );
							$Contract->setTax( $tax );
							$Contract->setNetto( $gross - $tax );	
											
							$Contract->save();
						}
					}
				}
				$Project->save();
			}
			
			// Generate Docs
			if (($tab_id == 4) && ($form->get('generateDocs')->isClicked() )) {
				$msg = ContractController::generateDocs($form, $msg);
				$new_tab_id = 3;
				$redirect = true; 
			}					
			
			// Generate Bookks
			if ((in_array($tab_id,array(2,3))) && ($form->get('generateBookks')->isClicked() )) {
				if($tab_id == 2) { $msg = IncomeController::generateBookks($form, $BookingTemplates, $msg); }
				if($tab_id == 3) { $msg = CostController::generateBookks($form, $BookingTemplates, $msg); }
				if(empty($msg['errors'])) {
					$new_tab_id = 5;
					$redirect = true; }
			}

			// Remove Docs
			if ((in_array($tab_id,array(3))) && ($form->get('removeDocs')->isClicked() )) {
				if($tab_id == 3) { 
					$msg = CostController::removeDocsfromCost($form, $msg);
					$redirect = true;
				}
			}
			
			// Costs special buttons
			if ((in_array($tab_id,array(3))) && ($form->get('setBookkingDate')->isClicked() )) {
				$msg = DocController::setDate($form,'bookking_date', $msg); }

			if ((in_array($tab_id,array(3))) && ($form->get('setPaymentDeadlineDate')->isClicked() )) {
				$msg = DocController::setDate($form,'payment_deadline_date', $msg); }		

			if ((in_array($tab_id,array(3))) && ($form->get('setPaymentDate')->isClicked() )) {
				$msg = DocController::setDate($form,'payment_date', $msg); }		

			// Contracts special buttons
			if ((in_array($tab_id,array(4))) && ($form->get('setPaymentPeriod')->isClicked() )) {
				$msg = ContractController::setPaymentPeriod($form, $msg); }	
			
			if ((in_array($tab_id,array(4))) && ($form->get('setEventDesc')->isClicked() )) {
				$msg = ContractController::setEventDesc($form, $msg); }	
							
			// Download transfers
			if (($tab_id == 3) && ($form->get('downloadTransfers')->isClicked() )) {
				
				$msg = $this->generateTransfers($form, $msg, $TransferTemplates, $Project, $Params);
				
				$filename = $Project->getName()."_transfers.csv"; 
				$filename = str_replace(' ','_',$filename);
				
				$response = new Response();
				$response->setContent($msg['content']);
				$response->headers->set('Content-Type', 'text/csv');
				$response->headers->set('Content-Disposition', 'attachment; filename='.$filename);	
				
				return $response;				
			}
			
			// Download Costs
			if (($tab_id == 3) && ($form->get('downloadCosts')->isClicked() )) {
				
				$filename = $Project->getName()."_costs.csv"; 
				$filename = str_replace(' ','_',$filename);
				$response = $this->render('AppBundle:Project:costs.csv.twig', array('Project' => $Project));
				$response->headers->set('Content-Type', 'text/csv');
				$response->headers->set('Content-Disposition', 'attachment; filename='.$filename);	
							
				return $response;	
			}
			
			// Print Contracts
			if (($tab_id == 4) && ($form->get('printContracts')->isClicked() )) {
				return $this->render('AppBundle:Template:page.html.twig', 
					array('pages' => ContractController::printContracts($form)));
			}
			
 		}

		if ($redirect && $return == 'project') {
			return $this->redirect($this->generateUrl('oppen_project', array(
				'project_id' => $Project->getId(),
				'tab_id' => $new_tab_id,
				'year_id' => $Year->getId() )));				
		} 
		
		if ($redirect && $return == 'projects') {
			return $this->redirect($this->generateUrl('oppen_projects', array(
				'year_id' => $Year->getId() )));				
		} 		

		return $this->render('AppBundle:Project:edit.html.twig',array(
			'form' => $form->createView(),
			'buttons' => $buttons,
			'tab_id' => $new_tab_id,
			'tabs' => $tabs,
			'controller' => 'project',
			'errors' => $msg['errors'],			
			'warnings' => $msg['warnings'],
			'balance' => $balance,	
					
			'Year' => $Year,
			'Month' => $Month,
			'DocCat' => $DocCat,
			'DocCat_asIncome' => $DocCat_asIncome,
			'DocCat_asCost' => $DocCat_asCost,
			'FileCat_asContractor' => $FileCat_asContractor,
			
			'BookingTemplates' => $BookingTemplates,
			'TransferTemplates' => $TransferTemplates, ));
	}		

	public function createZIP($form, $msg, $zipName, $path, $TransferTemplates, $Project, $Params) {
		
		$msg = CostController::generateData($form, $msg);
		$Data = $msg['Data'];
		
		$form_data = array(
			'payment_date'=>$form->get('payment_date')->getData(),
			'doc_no'=>$form->get('doc_no')->getData(),	);
		
		$addFromString = FALSE;
		
		$env = new \Twig_Environment(new \Twig_Loader_String());
		
		$zip = new \ZipArchive();
			
		if(!($zip->open($zipName,  ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE)) {
			$msg['errors'][] = 'Archiwum nie utworzone ani nie nadpisane.'; }
			
		else {			
			// Generate .csv for each selected Transfer Template 
			foreach($TransferTemplates as $Symbol => $Template) {
				if($form->get($Symbol)->getData()) {
					
					$for = explode('|',$Template['for']);
					
					foreach($Data[0]['IBA'] as $IBA => $IBA_Data) {
						
						if(!($IBA_Data['IncomeBankAcc'] instanceOf Account)) {
							$msg['errors'][] = 'Brak IncomeBankAcc dla IBA '.$IBA; 
							//return $msg;
						}
						
						$IBA_name = $IBA_Data['IncomeBankAcc']->getName();	
						$IBA_accNo = $IBA_Data['IncomeBankAcc']->getAccNo();	
						if(strpos($IBA_name,'|') === false){
							$msg['errors'][] = 'Błąd formatu nazwy konta '.$IBA_accNo.' '.$IBA_name;
							//return $msg;
						}
						list($bank, $name) = explode('|',$IBA_name);
						if(!array_key_exists($bank,$Template)){
							$msg['errors'][] = 'Brak szablonu przelewu dla banku '.$bank;
							//return $msg;
						}	
							
						// transfers to tax office
						if(($for[0] == 'SUM') && ($for[1] == 'IBA')) {
							
							$filename = $IBA."_".$bank."_".$Symbol.".csv";
							$contents = $env->render($Template[$bank],
									array('form' => $form_data,
										  'param'=> $Params,
										  'value' => $IBA_Data, ));
							$zip->addFromString($filename,  $contents);									
						}
					
						// transfers to contractors
						if(($for[0] == 'EACH') && ($for[1] == 'IBA')) {
							
							$filename = $IBA."_".$bank."_".$Symbol.".csv";
							$contents = '';
							
							foreach($Data as $D => $B) {
								
								if(($D > 0) && (array_key_exists($IBA,$B['IBA']))) {
									
									$B_IBA_Data = $B['IBA'][$IBA];
									
									if(array_key_exists('Doc', $B['SUM'][0])) {
										$SelDoc = $B['SUM'][0]['Doc'];
										if(!($SelDoc instanceOf Doc)) {
											$msg['errors'][] = 'Brak dokumentu id '.$D;
										}
									}						

									if(array_key_exists('SelDocFile', $B['SUM'][0])) {
										$SelDocFile = $B['SUM'][0]['SelDocFile'];
										if(!($SelDocFile instanceOf File)) {
											$msg['errors'][] = 'Brak kartoteki przy dok. id '.$D; 
										}
									}										
									
									$IF = FileQuery::create()
										->select('Id')
										->useIncomeQuery()
											->filterByProject($Project)
											->orderByRank()
										->endUse()
										->findOne(); 
									
									if(array_key_exists($IF,$B['IF'])) {
										$FirstIF = $B['IF'][$IF]; }
									else { $FirstIF = array('accNo' => 0); }
														
									$contents .= SettingsController::utf_win(
										$env->render($Template[$bank],
												array('form' =>  $form_data,
													  'param' => $Params,
													  'doc' =>   $SelDoc,
													  'file' =>  $SelDocFile,
													  'project'=>$Project,
													  'IBA' =>   $B['IBA'][$IBA],
													  'FirstIF'=>$FirstIF,
													   )).PHP_EOL
										);
										
								}
								
							}
							$zip->addFromString($filename,  $contents);
							$addFromString = TRUE;									
						}	
						 				
					}
				}
			}	
							
			$zip->close();
		}
		if(!$addFromString) {
			$msg['errors'][] = 'Brak przelewów do ściągnięcia;';}
		
		return $msg;
	}	


	public function generateTransfers($form, $msg, $TransferTemplates, $Project, $Params) {
		
		$msg = CostController::generateData($form, $msg);
		$Data = $msg['Data'];
		
		$form_data = array(
			'payment_date'=>$form->get('payment_date')->getData(),
			'doc_no'=>$form->get('doc_no')->getData(),	);
		
		$addFromString = FALSE;
		$content = '';
		
		$env = new \Twig_Environment(new \Twig_Loader_String());
			
		// Generate .csv for each selected Transfer Template 
		foreach($TransferTemplates as $Symbol => $Template) {
			
			if($form->get($Symbol)->getData()) {
				
				$for = explode('|',$Template['for']);
				
				foreach($Data[0]['IBA'] as $IBA => $IBA_Data) {
					
					if(!($IBA_Data['IncomeBankAcc'] instanceOf Account)) {
						$msg['errors'][] = 'Brak IncomeBankAcc dla IBA '.$IBA; 
					}
					
					$IBA_name = $IBA_Data['IncomeBankAcc']->getName();	
					$IBA_accNo = $IBA_Data['IncomeBankAcc']->getAccNo();	
					if(strpos($IBA_name,'|') === false){
						$msg['errors'][] = 'Błąd formatu nazwy konta '.$IBA_accNo.' '.$IBA_name;
					}
					list($bank, $name) = explode('|',$IBA_name);
					if(!array_key_exists($bank,$Template)){
						$msg['errors'][] = 'Brak szablonu przelewu dla banku '.$bank;
					}	
						
					// transfers to tax office
					if(($for[0] == 'SUM') && ($for[1] == 'IBA')) {
						
						$filename = $IBA."_".$bank."_".$Symbol.".csv";
						$content .= $env->render($Template[$bank],
								array('form' => $form_data,
									  'param'=> $Params,
									  'value' => $IBA_Data, )).PHP_EOL;								
					}
				
					// transfers to contractors
					if(($for[0] == 'EACH') && ($for[1] == 'IBA')) {
						
						$D_max = count($Data)-1;
						
						foreach($Data as $D => $B) {
							
							if(($D > 0) && (array_key_exists($IBA,$B['IBA']))) {
								
								$B_IBA_Data = $B['IBA'][$IBA];
								
								if(array_key_exists('Doc', $B['SUM'][0])) {
									$SelDoc = $B['SUM'][0]['Doc'];
									if(!($SelDoc instanceOf Doc)) {
										$msg['errors'][] = 'Brak dokumentu id '.$D;
									}
								}						

								if(array_key_exists('SelDocFile', $B['SUM'][0])) {
									$SelDocFile = $B['SUM'][0]['SelDocFile'];
									if(!($SelDocFile instanceOf File)) {
										$msg['errors'][] = 'Brak kartoteki przy dok. id '.$D; 
									}
								}										
								
								$IF = FileQuery::create()
									->select('Id')
									->useIncomeQuery()
										->filterByProject($Project)
										->orderByRank()
									->endUse()
									->findOne(); 
								
								if(array_key_exists($IF,$B['IF'])) {
									$FirstIF = $B['IF'][$IF]; }
								else { $FirstIF = array('accNo' => 0); }
													
								$content .= $env->render($Template[$bank],
											array('form' =>  $form_data,
												  'param' => $Params,
												  'doc' =>   $SelDoc,
												  'file' =>  $SelDocFile,
												  'project'=>$Project,
												  'IBA' =>   $B['IBA'][$IBA],
												  'FirstIF'=>$FirstIF,
												   )).PHP_EOL;
								if($D != $D_max) {			   
									//$content .= PHP_EOL;
								}
									
							}
							
						}
						
						$addFromString = TRUE;	
														
					}	
				}
			}
		}	
		
		$msg['content'] = $content;//.implode(PHP_EOL, $msg['errors']);
		
		if(!$addFromString) {
			$msg['errors'][] = 'Brak przelewów do ściągnięcia;';}
		
		return $msg;
	}	

	public function compareById($Old, $New) {
			if ($Old->getId() === $New->getId()) {
				return 0; 
			} else return 1;
	}
}
