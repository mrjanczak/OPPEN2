<?php

namespace AppBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Parser;

use AppBundle\Model\Year;
use AppBundle\Model\Month;
use AppBundle\Model\File;
use AppBundle\Model\FileCat;
use AppBundle\Model\Doc;
use AppBundle\Model\DocCat;
use AppBundle\Model\Cost;
use AppBundle\Model\CostDoc;
use AppBundle\Model\CostDocIncome;
use AppBundle\Model\Contract;
use AppBundle\Model\TempContract;

use AppBundle\Model\YearQuery;
use AppBundle\Model\MonthQuery;
use AppBundle\Model\FileQuery;
use AppBundle\Model\FileCatQuery;
use AppBundle\Model\CostQuery;
use AppBundle\Model\ContractQuery;
use AppBundle\Model\TempContractQuery;
use AppBundle\Model\DocQuery;
use AppBundle\Model\DocCatQuery;
use AppBundle\Model\ParameterQuery;

use AppBundle\Form\Type\ContractType;

class ContractController extends Controller
{  
    public function editAction($contract_id, $cost_id,  Request $request)
    {
		$Cost = CostQuery::create()->findPk($cost_id);
		$Project = $Cost->getProject();
		$Year = $Project->getYear();
		$buttons = array('cancel','save');
		
		$cost_coef = ParameterQuery::create()->getOneByName('default_cost_coef');
		$tax_coef =  ParameterQuery::create()->getOneByName('default_tax_coef');
		
		// create new one
		if($contract_id == 0) {
			$Contract = new Contract();
			$Contract->setCost($Cost);
			$Contract->setContractNo('');
			$Contract->setCostCoef($cost_coef);
			$Contract->setTaxCoef($tax_coef);
			$Contract->setPaymentPeriod('+2 weeks');		
		}
		// copy existing contract
		elseif ($request->attributes->get('_route') == 'oppen_contract_copy') {
			$Contract = ContractQuery::create()->findPk($contract_id);
			$Month = $Contract->getMonth();
			$Contract = $Contract->copy();
			$Contract->setContractNo(null);
			$Contract->setFile(null);
			$Contract->setDoc(null);
			
			if($Month instanceOf Month) {
				$contract_no = ContractQuery::create()->filterByMonth($Month)->count() + 1;
				$Contract->setContractNo('UoD '.$Year->getName().'/'.$Month->getName().'/'.$contract_no);
			} else { $Contract->setContractNo(''); }			
		}
		else { 
			$Contract = ContractQuery::create()->findPk($contract_id);
			$Month = $Contract->getMonth();
			$buttons[] = 'delete';
		}
				
 		$form = $this->createForm(new ContractType($Year, true), $Contract);
        $form->handleRequest($request); 
		
		$return = 'contract';
		
		if ($form->isSubmitted()){
			$return = 'project';
			
			if ($form->get('cancel')->isClicked()) {  }	
			if ($form->get('delete')->isClicked()) { 
				$Contract->delete(); 
			}		
			if (($form->get('save')->isClicked()) && ($form->isValid())) { 
				
				$Month = $Contract->getMonth();
				
				$ContractDate =  $Contract->getContractDate();
				$File = $Contract->getFile();
				if(!is_null($File)) { 
					$NAME = substr($File->getFirstName(),0,3).substr($File->getLastName(),0,3); }
				else {$NAME='';}
				$Contract->setContractNo('UoD '.$ContractDate->format('Y-m-d').'/'.$NAME);
				/*	
				if(($form->get('contract_no')->getData() == '') && ($Month instanceOf Month)) {
					$contract_no = ContractQuery::create()->filterByMonth($Month)->count() + 1;
					$Contract->setContractNo('UoD '.$Year->getName().'/'.$Month->getName().'/'.$contract_no);
				} 
				*/
				
				$gross = $form->get('gross')->getData();
				$cost_coef = $form->get('cost_coef')->getData();
				$tax_coef = $form->get('tax_coef')->getData();
				$income_cost = round( $gross*$cost_coef/100, 2);
				$tax = round( ($gross - $income_cost)*$tax_coef/100, 0);
				
				$Contract->setIncomeCost( $income_cost );
				$Contract->setTax( $tax );
				$Contract->setNetto( $gross - $tax );
				
				$Contract->save(); 
			}		
 		}
 		
		if ( $return == 'project') {
			return $this->redirect($this->generateUrl('oppen_project', 
				array('project_id' =>$Project->getId(),
					  'tab_id'  => 4,
					  'year_id' => $Year->getId() )));				
		} 	
					
		return $this->render('AppBundle:Contract:edit.html.twig',
			array('form' => $form->createView(),		
				'buttons' => $buttons));
	}

    public function deleteAction($contract_id, Request $request)
    {
		$Contract  = ContractQuery::create()->findPk($contract_id); 
		$Cost     = $Contract->getCost();
		$Project  = $Cost->getProject();
		$Year = $Project->getYear();
				
		$Contract->delete();
		
		return $this->redirect($this->generateUrl('oppen_project',array(
			'year_id' => $Year->getId(),
			'tab_id' => 4,
			'project_id' => $Project->getId())));		
	}
	
	static public function generateDocs($form, $msg) {
		
		$Project = $form->getData();
		$ProjectFile = $Project->getFile();
		$DocMonth = $form->get('DocMonth')->getData();
		$DocYear = $DocMonth->getYear();
		$DocCat  = DocCatQuery::create()->filterByAsBill(1)->findOneByYear($DocYear);
		
		$receipt_date = $form->get('receipt_date')->getData();
		if(!$receipt_date) {$receipt_date = new \DateTime('now');}
		$document_date = $form->get('document_date')->getData();
		$operaion_date = $form->get('operation_date')->getData();
		if(!$operaion_date) {$operaion_date = new \DateTime('now');}		
		
		foreach ($form->get('SortedCosts') as $FCost) {
			foreach ($FCost->get('SortedContracts') as $FContract) {
				if($FContract->get('select')->getData() == 1) {
					
					$Contract = $FContract->getData();
					$File = $Contract->getFile();
					$Cost = $Contract->getCost();
					$contract_date = $Contract->getContractDate();
					$payment_period = $Contract->getPaymentPeriod();
					
					$event_name = $Contract->getEventName() == '' ? $Project->getName() : $Contract->getEventName();
					$Doc = new Doc();
					$Doc->setDesc('honorarium '.$File->getName().' - '.$event_name);
					$Doc->setFile($File);
					$Doc->setDocCat($DocCat);
					$Doc->setMonth($DocMonth);
					$Doc->setPaymentMethod($Contract->getPaymentMethod());
					
					// comment $RegIdx= NULL; $RegNo = NULL:
					$Doc->setNewDocIdx(); 
					$Doc->setNewDocNo($ProjectFile->getAccNo()); 
					
					$Doc->setReceiptDate($receipt_date );
					$Doc->setDocumentDate($document_date ? $contract_date : $document_date);
					$Doc->setOperationDate($operaion_date );
					$Doc->setBookkingDate(null);
					$Doc->setPaymentDeadlineDate($receipt_date->modify($payment_period) );
					$Doc->save();
					
					$Contract->setDoc($Doc)->save();
					
					$CostDoc = new CostDoc();
					$CostDoc->setValue($Contract->getGross());
					$CostDoc->setCost($Cost);
					$CostDoc->setDoc($Doc);
					$Cost->addCostDoc($CostDoc);
					
					foreach ($Project->getIncomes() as $Income) {
						$CostDocIncome = new CostDocIncome();
						$CostDocIncome->setValue(0);
						$CostDocIncome->setIncome($Income);
						$CostDoc->addCostDocIncome($CostDocIncome);				
					}
					$Cost->save();					
				}
			}
		}
		return $msg;
	}	

	static public function printContracts($form) {
		
		$Project = $form->getData();
		$Year = $Project->getYear();		
        $Params = ParameterQuery::create()->getAll();
		$Parser = new Parser;
		
		/*
		// delete all temporary contracts - this table in db can be used by external programs to generate contracts
		foreach(TempContractQuery::create()->find() as $TempContract) {
			$TempContract->delete();}
		*/
		
		$pages = array();
		foreach ($form->get('SortedCosts') as $FCost) {
			foreach ($FCost->get('SortedContracts') as $FContract) {
				if($FContract->get('select')->getData() == 1) {
					$Contract = $FContract->getData();
					$Template = $Contract->getTemplate();
					$File = $Contract->getFile();
					$Doc = $Contract->getDoc();					
					
					$comment = $Contract->getComment();					
					/*
					$instalments = $comment.'';
					if(substr($comment,1,strlen('instalments:')) == 'instalments:') {
						$items = $Parser->parse($comment);
						foreach ($items as $item) {
							$instalments .=  $item['amount'].' PLN do '.$item['date'].',';
						}	
					}
					*/
					
					mb_internal_encoding('UTF-8');					
					$c = $Template->getContents();					
					$c = str_replace('__contract_no__',$Contract->getContractNo(), $c);
					$c = str_replace('__contract_date__',$Contract->getContractDate()->format('d-m-Y'), $c); 
					$c = str_replace('__contract_place__',$Contract->getContractPlace(), $c);
					
					$c = str_replace('__organization_name__',$Params['organization_name'], $c);
					$c = str_replace('__organization_address1__',$Params['organization_address1'], $c);
					$c = str_replace('__organization_address2__',$Params['organization_address2'], $c);
					$c = str_replace('__organization_KRS__',$Params['organization_KRS'], $c);
					$c = str_replace('__organization_REGON__',$Params['organization_REGON'], $c);	
												
					$c = str_replace('__comment__',$comment, $c);								
					//$c = str_replace('__instalments__',$instalments, $c);								
					
					$contractor_name = $File->getFirstName().' '.$File->getLastName();
					$approver_name = $contractor_name != $Params['organization_board2'] ? 
						$Params['organization_board2'] : $Params['organization_board1']; 
					$c = str_replace('__approver_name__' ,$approver_name, $c);	
									
					$c = str_replace('__contractor_name__',$contractor_name, $c);
					
					//$st = SettingsController::street_prefix($File->getStreet());
					//$f = SettingsController::flat_prefix($File->getFlat());					
					//$contractor_address1 = $st.$File->getStreet().' '.$File->getHouse().$f.$File->getFlat();
					//$contractor_address2 = $File->getCode().' '.$File->getCity().', '.$File->getCountry();
										
					$contractor_ID = ($File->getPesel() == NULL )?'______________':$File->getPesel(); 					
					$contractor_birth_date = ($File->getBirthDate() == NULL )?'______________':date_format($File->getBirthDate(), 'd-m-Y');
					$contractor_birth_place = ($File->getBirthPlace() == NULL )?'______________':$File->getBirthPlace();
					$contractor_address1 = $File->getAddress1();
					$contractor_address2 = $File->getAddress2();
					$contractor_email = ($File->getEmail() == NULL )?'______________':$File->getEmail();
					$contractor_phone = ($File->getPhone() == NULL )?'______________':$File->getPhone();
					$contractor_US = ($File->getSubFile() == NULL )?'___________________________':$File->getSubFile()->getName(); 
					$contractor_country = $File->getCountry(); 
										
					$c = str_replace('__contractor_ID__',$contractor_ID, $c);
					$c = str_replace('__contractor_birth_date__',$contractor_birth_date, $c);
					$c = str_replace('__contractor_birth_place__',$contractor_birth_place, $c);
					$c = str_replace('__contractor_address1__',$contractor_address1, $c);
					$c = str_replace('__contractor_address2__',$contractor_address2, $c);					
					$c = str_replace('__contractor_phone__',$contractor_phone, $c);
					$c = str_replace('__contractor_email__',$contractor_email, $c);
					$c = str_replace('__contractor_US__',$contractor_US, $c);
					$c = str_replace('__contractor_country__',$contractor_country, $c);
					
					$c = str_replace('__project_name__' ,$Project->getName(), $c);
					$c = str_replace('__event_desc__' ,$Contract->getEventDesc(), $c);		
					$c = str_replace('__event_name__' ,$Contract->getEventName(), $c);
					$c = str_replace('__event_place__' ,$Contract->getEventPlace(), $c);
					
					$event_date = $Contract->getEventDate();
					if($event_date instanceOf Date) {
						$event_date = $event_date->format('d-m-Y'); }
					else {$event_date = '';}
					$c = str_replace('__event_date__',$event_date, $c);
						
					$event_role = $Contract->getEventRole();
					if($event_role=='') {
						$event_role = $File->getProfession();}
					if($event_role!='') {
						$event_role = ' jako '.$event_role ; }
					$c = str_replace('__event_role__' ,$event_role, $c);						
										
					$c = str_replace('__bank_name__',$File->getBankName(), $c);
					$c = str_replace('__bank_account__',$File->getBankAccount(), $c);

					if($Contract->getTax() == 0) 
					{
						$c = preg_replace('/(--document--(.|\n)*--document--)/' ,' ', $c, 1);}
					elseif ($Doc instanceOf Doc) 
					{
						$c = str_replace('__document_no__',$Doc->getDocNo(), $c); 
						
						if ($Doc->getDocumentDate() instanceOf DateTime) {
							$c = str_replace('__document_date__',$Doc->getDocumentDate()->format('d-m-Y'), $c); }
					} else {
						$c = str_replace('__document_no__','', $c); 
						$c = str_replace('__document_date__','', $c); 						
					}
					
					$c = str_replace('--document--' ,'', $c);
					
					$c = str_replace('__gross__' ,      number_format($Contract->getGross(), 2, ',', ' '), $c);
					$c = str_replace('__income_cost__' ,number_format($Contract->getIncomeCost(), 2, ',', ' '), $c);
					$c = str_replace('__tax__' ,        number_format($Contract->getTax(), 2, ',', ' '), $c);
					$c = str_replace('__netto__',       number_format($Contract->getNetto(), 2, ',', ' '), $c);
					$c = str_replace('__gross_text__' ,AmountInWords::get($Contract->getGross()), $c);
					
					$pages[] = $c;
					/*
					// add temporary contract
					$TempContract = new TempContract;

					$TempContract->setContractNo(   $Contract->getContractNo());     
					$TempContract->setContractDate( $Contract->getContractDate());  
					$TempContract->setContractPlace($Contract->getContractPlace());
					
					$TempContract->setEventDesc( $Contract->getEventDesc());  
					$TempContract->setEventDate( $Contract->getEventDate());  
					$TempContract->setEventPlace($Contract->getEventPlace());  
					$TempContract->setEventName( $Contract->getEventName());          
					$TempContract->setEventRole( $Contract->getEventRole());  
											
					$TempContract->setGross(     $Contract->getGross());   
					$TempContract->setIncomeCost($Contract->getIncomeCost());   
					$TempContract->setTax(       $Contract->getTax());  
					$TempContract->setNetto(     $Contract->getNetto());  

					$TempContract->setFirstName( $File->getFirstName());         
					$TempContract->setLastName(  $File->getLastName());       
					$TempContract->setPESEL(     $File->getPESEL());        
					$TempContract->setNIP(       $File->getNIP());             
						  
					$TempContract->setStreet(    $File->getStreet());  
					$TempContract->setHouse(     $File->getHouse()); 
					$TempContract->setFlat(      $File->getFlat());  
					$TempContract->setCode(      $File->getCode());
					$TempContract->setCity(      $File->getCity());  
					$TempContract->setDistrict(  $File->getDistrict());  
					$TempContract->setCountry(   $File->getCountry());
					 
					$TempContract->setBankAccount($File->getBankAccount());  
					$TempContract->setBankName(   $File->getBankName());
					
					$TempContract->save(); 
					*/
				}
			}
		}
		return $pages;
	}

	static public function setPaymentPeriod($form, $msg) {
			
		$payment_period =  $form->get('payment_period')->getData();
		
		foreach ($form->get('SortedCosts') as $FCost) {
			foreach ($FCost->get('SortedContracts') as $FContract) {
				if($FContract->get('select')->getData() == 1) {
					$Contract = $FContract->getData();	
					$Contract->setPaymentPeriod($payment_period)->save();
				}
			}
		}
		return $msg;
	}

	static public function setEventDesc($form, $msg) {
			
		$event_desc =  $form->get('event_desc')->getData();
		
		foreach ($form->get('SortedCosts') as $FCost) {
			foreach ($FCost->get('SortedContracts') as $FContract) {
				if($FContract->get('select')->getData() == 1) {
					$Contract = $FContract->getData();	
					$Contract->setEventDesc($event_desc)->save();
				}
			}
		}
		return $msg;
	}		
}
