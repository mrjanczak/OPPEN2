<?php

namespace AppBundle\Controller;

use \Exception;
use \PropelObjectCollection;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Controller\DocController;
use AppBundle\Controller\BookkController;

use AppBundle\Model\Year;
use AppBundle\Model\Month;
use AppBundle\Model\File;
use AppBundle\Model\FileCat;
use AppBundle\Model\Doc;
use AppBundle\Model\DocCat;
use AppBundle\Model\DocList;
use AppBundle\Model\Project;
use AppBundle\Model\Income;
use AppBundle\Model\IncomeDoc;
use AppBundle\Model\Cost;
use AppBundle\Model\CostIncome;
use AppBundle\Model\CostDoc;
use AppBundle\Model\CostDocIncome;
use AppBundle\Model\Account;
use AppBundle\Model\Bookk;
use AppBundle\Model\BookkEntry;
use AppBundle\Model\Contract;

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
use AppBundle\Model\AccountQuery;
use AppBundle\Model\BookkQuery;
use AppBundle\Model\BookkEntryQuery;
use AppBundle\Model\ContractQuery;
use AppBundle\Model\TemplateQuery;
use AppBundle\Model\ParameterQuery;

use AppBundle\Form\Type\DocType;
use AppBundle\Form\Type\DocListType;
use AppBundle\Form\Type\ProjectType;
use AppBundle\Form\Type\IncomeType;
use AppBundle\Form\Type\CostType;

class CostController extends Controller
{  
    public function editAction($project_id, $cost_id, Request $request)  {

		$msg = array('errors' => array(), 'warnings' => array(), 'messages' => array());

		$Project = ProjectQuery::create()->findPk($project_id);
		$CostFileCat = $Project->getCostFileCat();
		if(!($CostFileCat instanceOf FileCat)) {
			throw new \Exception('Projekt nie ma określonej kategorii kosztów.'); }
		$Year = $Project->getYear(); 
		$buttons = array('cancel','save');
		
		if($cost_id == 0) {
			$Cost = new Cost();
			$Cost ->setProject($Project);	
		} else {
			$Cost = CostQuery::create()->findPk($cost_id); 
			$buttons[] = 'delete';
		}
		$securityContext = $this->get('security.context');
		$disable_accepted_docs = ParameterQuery::create()->getOneByName('disable_accepted_docs');
		//$this->container->getParameter('disable_accepted_docs');
 		$form = $this->createForm(new CostType($Year, $CostFileCat, true, 
			$securityContext, $disable_accepted_docs), $Cost);
        $form->handleRequest($request); 
		
		$return = 'cost';
		if ($form->isSubmitted()){
			$return = 'project';
			if ($form->get('delete')->isClicked()) { $Cost->delete(); }	
			if (($form->get('save')->isClicked())  && ($form->isValid())) {
					
				if($cost_id == 0) {
					foreach ($Project->getIncomes() as $Income) {
						$CostIncome = new CostIncome();
						$CostIncome->setValue(0);
						$CostIncome->setIncome($Income);
						$Cost->addCostIncome($CostIncome);				
					}							
				}
				$Cost->save();
			}
		}
		if($return == 'project') {
			return $this->redirect($this->generateUrl('oppen_project',array(
				'year_id' => $Year->getId(),
				'tab_id' => 3,
				'project_id' => $Project->getId())));	
		} else {		
			return $this->render('AppBundle:Cost:edit.html.twig',array(
				'Year' => $Year,		
				'form' => $form->createView(),
				'buttons' =>$buttons) );
		}
	}
	
    public function moveAction($cost_id, $dir, Request $request) {
		$Cost = CostQuery::create()->findPk($cost_id); 
		$Project = $Cost->getProject();
		$Year = $Project->getYear(); 
						
		if ($dir == 'up')   { $Cost->moveUp(); }		
		if ($dir == 'down') { $Cost->moveDown(); }
		
		$Cost->save();
		
		return $this->redirect($this->generateUrl('oppen_project',array(
			'year_id' => $Year->getId(),
			'tab_id' => 3,
			'project_id' => $Project->getId())));	
	}	
	
    public function addDocAction($cost_id, $month_id, $doc_cat_id, Request $request) {			

		$msg = array('errors' => array(), 'warnings' => array(), 'messages' => array());
		
		$Cost = CostQuery::create()->findPk($cost_id); 
		$Project = $Cost->getProject();
		$project_id = $Project->getId();
		$as_doc_select = true; 
		$as_bookk_accept = false;	
			
		if ($request->isMethod('POST')) {
			$DocListR = $request->request->get('doc_list');
			$Year = YearQuery::create()->findPk($DocListR['Year']);
			$Month = MonthQuery::create()->findPk($DocListR['Month']);
			$DocCat = DocCatQuery::create()->findPk($DocListR['DocCat']);				
			$page = $DocListR['page'];
			$desc = $DocListR['desc'];
			$showBookks = $DocListR['showBookks'];
		}
		else { 
			$DocListR = array();
			$Year = $Project->getYear();		
			$Month = MonthQuery::create()->findPk($month_id);
			$DocCat = DocCatQuery::create()->findPk($doc_cat_id);
			$name = '*';
			$page = 1;			
			$desc = '*';	
			$showBookks = -1;				
		}

		$DocList = DocController::newDocList($Year, $Month, $DocCat, 
			$showBookks, $desc, $page, $as_doc_select, $as_bookk_accept);										

		$securityContext = $this->get('security.context');
		$disable_accepted_docs = ParameterQuery::create()->getOneByName('disable_accepted_docs');
		//$this->container->getParameter('disable_accepted_docs');
		$form = $this->createForm(new DocListType($Year, null, false, true, 
			$securityContext, $disable_accepted_docs), $DocList);	
		$return = 'doc_list';
		
		if(array_key_exists('cancel',$DocListR)) {
			$return = 'project';
		}

		if(array_key_exists('search',$DocListR)) {
			$return = 'doc_list';
		}
			
		if(array_key_exists('selectDocs',$DocListR) && array_key_exists('Docs',$DocListR)) {					
			foreach($DocListR['Docs'] as $DocR) {
				if(array_key_exists('select',$DocR)) {	
				
					$Doc = DocQuery::create()->findPk($DocR['id']);
					if($Doc instanceOf Doc) {
						$CostDoc = new CostDoc();
						
						$maxValue = BookkEntryQuery::create()
										->select('value')
										->useBookkQuery()
											->filterByDoc($Doc)
										->endUse()
										->orderByValue('desc')
										->findOne();
										
						$CostDoc->setValue($maxValue);
						$CostDoc->setDoc($Doc);
						$CostDoc->setCost($Cost);
						$Cost->addCostDoc($CostDoc);
					}
					foreach ($Project->getIncomes() as $Income) {
						$CostDocIncome = new CostDocIncome();
						$CostDocIncome->setValue(0);
						$CostDocIncome->setIncome($Income);
						$CostDoc->addCostDocIncome($CostDocIncome);				
					}
				}
			}
			$Cost->save();
			$return = 'project';
		}	
		
		if($return == 'project') {
			return $this->redirect($this->generateUrl('oppen_project',array(
				'year_id' => $Year->getId(),
				'tab_id' => 3,
				'project_id' => $Project->getId(),) ));	
		} 
		if($return == 'doc_list') {	
			return $this->render('AppBundle:Doc:list.html.twig',array(
				'Year' => $Year,
				'Month' => $Month,
				'DocCat' => $DocCat,
				'form' => $form->createView(),
				'buttons' => array('cancel'),
				'project_id' => $project_id,
				'errors' => $msg['errors'],			

				'return' => 'project',
				'id1' => $project_id,
				'id2' => 3,				
				'subtitle' => ' > '.$Cost->getName().' / '.$Project->getName() ));	
		}
	}	
	
    public function removeDocAction($cost_doc_id, Request $request)  {
		$CostDoc  = CostDocQuery::create()->findPk($cost_doc_id); 
		$Cost     = $CostDoc->getCost();
		$Project  = $Cost->getProject();
		$Year = $Project->getYear();
				
		$CostDoc->delete();
		
		return $this->redirect($this->generateUrl('oppen_project',array(
			'year_id' => $Year->getId(),
			'tab_id' => 3,
			'project_id' => $Project->getId())));		
	}	

	static public function removeDocsfromCost($form, $msg) {
					   
		foreach ($form->get('Costs') as $FCost) {
						
			foreach ($FCost->get('CostDocs') as $FCostDoc) {
			
				if($FCostDoc->get('select')->getData()) {
					
					$CostDoc = $FCostDoc->getData();
					$CostDoc->delete();
				}
			}
		}
		return $msg;							
	}
	
	static public function generateData($form, $msg) {

		$Project = $form->getData();
		
		$Data[0]=array('SUM'=>array(), 
					   'IBA'=>array(), 
					   'IF' =>array());
		$msg['Data'] = $Data;
					   
		foreach ($form->get('Costs') as $FCost) {
			
			$Cost = $FCost->getData();
			$CostAcc = current(array_filter(array($Cost->getCostAcc(),$Project->getCostAcc())));
			$CostFile = $Cost->getFile();
			
			foreach ($FCost->get('CostDocs') as $FCostDoc) {
			
				if(($FCostDoc->get('select')->getData()) && 
					($FCostDoc->getData()->getValue() > 0)) {
					
					$CostDoc = $FCostDoc->getData();
					$gross = $CostDoc->getValue();
					$Doc = $CostDoc->getDoc();
					$D = $Doc->getId();
					if(!($Doc instanceOf Doc)) { 
						$msg['errors'][] = 'Brak dokumentu w grupie kosztów '.$Cost->getName(); 
						return $msg; 
					}
						
					$DocFile = $Doc->getFile();
					if(!($DocFile instanceOf File)) {
						$msg['errors'][] = 'Dokument (id '.$D.') nie ma kartoteki'; 
						return $msg;
					}							
					$DocCat = $Doc->getDocCat();
					if(!($DocCat instanceOf DocCat)) {
						$msg['errors'][] = 'Dokument (id '.$D.') nie ma kategorii'; 
						return $msg;
					}
					$CommitAcc = $DocCat->getCommitmentAcc();
					$TaxCommitAcc = $DocCat->getTaxCommitmentAcc();
						
					$Contract = $Doc->getContracts()->getFirst();
					if($Contract instanceOf Contract) {
						$Cgross = $Contract->getGross();
						$netto = round($gross/$Cgross * $Contract->getNetto(),0);
						$tax =   round($gross/$Cgross * $Contract->getTax(),0);
					} else { 
						//$msg['errors'][] = 'Dokument (id '.$D.') nie ma umowy'; return $errors;
						$netto = $gross; 
						$tax = 0;
					}
					
					if(!array_key_exists(0,$Data[0]['SUM'])) { 
						$Data[0]['SUM'][0] = array('gross'=>0,'netto'=>0,'tax'=>0, 'Doc'=> null);}
					$Data[0]['SUM'][0]['gross']    += $gross;
					$Data[0]['SUM'][0]['netto']    += $netto;
					$Data[0]['SUM'][0]['tax']      += $tax;
					$Data[0]['SUM'][0]['TaxCommitAcc']= $TaxCommitAcc;
					
					$Data[$D]=array('SUM'=>array(), 
									'IBA'=>array(), 
									'IF' =>array());
									
					$Data[$D]['SUM'][0] = array();				
					$Data[$D]['SUM'][0]['gross']     = $gross;
					$Data[$D]['SUM'][0]['netto']     = $netto;
					$Data[$D]['SUM'][0]['tax']       = $tax;						
					$Data[$D]['SUM'][0]['Doc']       = $Doc;
					$Data[$D]['SUM'][0]['GroupAcc']  = $CostAcc;
					$Data[$D]['SUM'][0]['CommitAcc']   = $CommitAcc;
					$Data[$D]['SUM'][0]['TaxCommitAcc']= $TaxCommitAcc;
					$Data[$D]['SUM'][0]['GroupFile']   = $CostFile;
					$Data[$D]['SUM'][0]['SelDocFile']  = $DocFile;
					$Data[$D]['SUM'][0]['GroupDoc_Desc']= $CostDoc->getDesc();
																
					$CDIs = array();
					foreach($CostDoc->getCostDocIncomes() as $CostDocIncome) {
						if($CostDocIncome->getValue() > 0) {
								$CDIs[] = $CostDocIncome;
								$LastCDI = $CostDocIncome;
						}
					}
					
					$nettoSum = 0;
					$taxSum = 0;							
					foreach($CDIs as $CDI) {
						$Income = $CDI->getIncome();
						$IncomeFile = $Income->getFile();
						if(!($IncomeFile instanceOf File)) {
							$msg['errors'][] = 'Brak kartoteki dla grupy przychodów '.$Income->getName(); 
							return $msg;
						}
						
						$IncomeAcc = $Income->getIncomeAcc();
						if(!($IncomeAcc instanceOf Account)) {
							$msg['errors'][] = 'Brak konta dla grupy przychodów '.$Income->getName(); 
							return $msg;
						}						
						$IF = $IncomeFile->getId();
						
						$CDIgross = $CDI->getValue();
						$CDIBankAcc = current(array_filter(array($Income->getBankAcc(),$Project->getBankAcc())));
						//$IBA = $CDIBankAcc->getId();
						$IBA = $CDIBankAcc->getAccNo();

						if($CDI !== $LastCDI) {
							$CDInetto = round($CDIgross / $gross * $netto , 0);
							$nettoSum += $CDInetto;
							$CDItax = $CDIgross - $CDInetto;
							$taxSum += $CDItax;								
						}
						else {
							$CDInetto = $netto - $nettoSum;
							$CDItax = $tax - $taxSum;
						}
					
						if(!array_key_exists($IBA,$Data[$D]['IBA'])) { 
							$Data[$D]['IBA'][$IBA] = array(
								'gross'=>0, 'netto'=>0, 'tax'=>0, 'IncomeBankAcc' => $CDIBankAcc);}							
						$Data[$D]['IBA'][$IBA]['gross'] += $CDIgross;
						$Data[$D]['IBA'][$IBA]['netto'] += $CDInetto;
						$Data[$D]['IBA'][$IBA]['tax']   += $CDItax;
						
						if(!array_key_exists($IF,$Data[$D]['IF'])) { 
							$Data[$D]['IF'][$IF] = array(
								'gross'=>0,'netto'=>0,'tax'=>0, 
								'IncomeFile' => $IncomeFile, 
								'IName' => $Income->getName(),
								'IAAccNo' => $IncomeAcc->getAccNo(),);}						
						$Data[$D]['IF'][$IF]['gross'] += $CDIgross;
						$Data[$D]['IF'][$IF]['netto'] += $CDInetto;
						$Data[$D]['IF'][$IF]['tax']   += $CDItax;
								
												
						if(!array_key_exists($IBA,$Data[0]['IBA'])) { 
							$Data[0]['IBA'][$IBA] = array(
								'gross'=>0,'netto'=>0,'tax'=>0, 'IncomeBankAcc' => $CDIBankAcc); }												
						$Data[0]['IBA'][$IBA]['gross'] += $CDIgross;
						$Data[0]['IBA'][$IBA]['netto'] += $CDInetto;
						$Data[0]['IBA'][$IBA]['tax']   += $CDItax;
											
						if(!array_key_exists($IF,$Data[0]['IF'])) { 
							$Data[0]['IF'][$IF] = array(
								'gross'=>0,'netto'=>0,'tax'=>0, 'IncomeFile' => $IncomeFile);}							
						$Data[0]['IF'][$IF]['gross']  += $CDIgross;
						$Data[0]['IF'][$IF]['netto']  += $CDInetto;
						$Data[0]['IF'][$IF]['tax']    += $CDItax;
					}					
				}
			}
		}		
		
		$msg['Data'] = $Data;
		return $msg;
	}	
	
	static public function generateBookks($form, $BookingTemplates, $msg) {
			
		$msg = CostController::generateData($form, $msg);
		
		if(empty($msg['errors'])) {
			return BookkController::generateBookks($form, $BookingTemplates, $msg);
		} else {
			return $msg;
		}
	}	
	
	static public function downloadTransfers($form, $desc_temp, $transfer_temp, $msg) {
		$env = new \Twig_Environment(new \Twig_Loader_String());
						
		$Project = $form->getData();
		$Year = $Project->getYear();		
		
		$i=0;
		$contents = '';		
		foreach ($form->get('Costs') as $FCost) {		
			foreach ($FCost->get('CostDocs') as $FCostDoc) {
				if(($FCostDoc->get('select')->getData()) && 
					($FCostDoc->getData()->getValue() > 0)) {
						
					$i++;
					
					$Cost = $FCost->getData();
					$CostDoc = $FCostDoc->getData();
						
					$Doc = $CostDoc->getDoc();
					if($Doc){
						$doc_no = $Doc->getDocNo();}
						
					$Contract = ContractQuery::create()->findOneByDoc($Doc);
					if($Contract == null) {	
						$msg['errors'][] = 'Brak Umowy przypisanej do Dokumentu';
						return $msg; 
					}
					$value = $Contract->getNetto();	
										
					$File = $Doc->getFile();					
					if($File == null) {
						$msg['errors'][] = 'Brak kartoteki przypisanej do Dokumentu';
						return $msg;
					}
					
					$desc = $desc_temp;			
					$desc = str_replace('__SelDocFile_Name__', $File->getName(), $desc);
					$desc = str_replace('__SelDoc_No__',       $doc_no, $desc);
					$desc = str_replace('__Project_Name__',    $Project->getName(), $desc);
									
					$contents .= $env->render($transfer_temp,
							  array('i' => $i,
									'account' => $File->getBankAccount(), 
									'name' =>    $File->getName(), 
									'street' =>  SettingsController::street_prefix($File->getStreet()).$File->getStreet(),
									'house' => $File->getHouse(),
									'flat' => SettingsController::flat_prefix($File->getFlat()).$File->getFlat(),
									'code' =>    $File->getCode(), 
									'city' =>    $File->getCity(),	
									'desc' =>    $desc,
									'value' =>   $value)).PHP_EOL;					

				}
			}
		}
		$msg['contents'] = $contents;
		return $msg;
	}			
	
}
