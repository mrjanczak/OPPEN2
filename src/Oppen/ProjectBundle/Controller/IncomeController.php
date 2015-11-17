<?php

namespace Oppen\ProjectBundle\Controller;

use \PropelObjectCollection;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Oppen\ProjectBundle\Controller\DocController;
use Oppen\ProjectBundle\Controller\BookkController;

use Oppen\ProjectBundle\Model\Year;
use Oppen\ProjectBundle\Model\Month;
use Oppen\ProjectBundle\Model\File;
use Oppen\ProjectBundle\Model\FileCat;
use Oppen\ProjectBundle\Model\Doc;
use Oppen\ProjectBundle\Model\DocCat;
use Oppen\ProjectBundle\Model\DocList;
use Oppen\ProjectBundle\Model\Project;
use Oppen\ProjectBundle\Model\Income;
use Oppen\ProjectBundle\Model\IncomeDoc;
use Oppen\ProjectBundle\Model\Cost;
use Oppen\ProjectBundle\Model\CostIncome;
use Oppen\ProjectBundle\Model\CostDoc;
use Oppen\ProjectBundle\Model\CostDocIncome;
use Oppen\ProjectBundle\Model\Bookk;
use Oppen\ProjectBundle\Model\BookkEntry;

use Oppen\ProjectBundle\Model\YearQuery;
use Oppen\ProjectBundle\Model\MonthQuery;
use Oppen\ProjectBundle\Model\FileQuery;
use Oppen\ProjectBundle\Model\FileCatQuery;
use Oppen\ProjectBundle\Model\DocQuery;
use Oppen\ProjectBundle\Model\DocCatQuery;
use Oppen\ProjectBundle\Model\ProjectQuery;
use Oppen\ProjectBundle\Model\IncomeQuery;
use Oppen\ProjectBundle\Model\IncomeDocQuery;
use Oppen\ProjectBundle\Model\CostQuery;
use Oppen\ProjectBundle\Model\CostIncomeQuery;
use Oppen\ProjectBundle\Model\CostDocQuery;
use Oppen\ProjectBundle\Model\CostDocIncomeQuery;
use Oppen\ProjectBundle\Model\AccountQuery;
use Oppen\ProjectBundle\Model\ParameterQuery;

use Oppen\ProjectBundle\Form\Type\DocType;
use Oppen\ProjectBundle\Form\Type\DocListType;
use Oppen\ProjectBundle\Form\Type\ProjectType;
use Oppen\ProjectBundle\Form\Type\IncomeType;
use Oppen\ProjectBundle\Form\Type\CostType;

class IncomeController extends Controller
{
    public function editAction($project_id, $income_id, Request $request)
    {
		$Project = ProjectQuery::create()->findPk($project_id);
		$Year = $Project->getYear();
		$buttons = array('cancel','save');
				
		if($income_id == 0) {
			$Income = new Income();
			$Income->setProject($Project);
		}
		else {		
			$Income = IncomeQuery::create()->findPk($income_id);
			$buttons[] = 'delete';
		}
		$securityContext = $this->get('security.context');
		$disable_accepted_docs = ParameterQuery::create()->getOneByName('disable_accepted_docs');
		//$this->container->getParameter('disable_accepted_docs');
 		$form = $this->createForm(new IncomeType($Year, true,
			$securityContext, $disable_accepted_docs), $Income);	
        $form->handleRequest($request); 

		$return = 'income';
		if ($form->isSubmitted()){	
			$return = 'project';	
			if ($form->get('delete')->isClicked()) { $Income->delete();}	
			if (($form->get('save')->isClicked()) && ($form->isValid())) {
				
				if($income_id == 0) {
					foreach ($Project->getCosts() as $Cost) {
						$CostIncome = new CostIncome();
						$CostIncome->setValue(0);
						$CostIncome->setCost($Cost);
						$Income->addCostIncome($CostIncome);	
						
						foreach($Cost->getCostDocs() as $CostDoc) {
							$CostDocIncome = new CostDocIncome();
							$CostDocIncome->setValue(0);
							$CostDocIncome->setCostDoc($CostDoc);
							$Income->addCostDocIncome($CostDocIncome);							
						}				
					}					
				}
				$Income->save();
			}
		}
		if($return == 'project') {	
			return $this->redirect($this->generateUrl('oppen_project',array(
				'year_id' => $Year->getId(),
				'tab_id' => 2,
				'project_id' => $Project->getId())));	
		} else {		
			return $this->render('OppenProjectBundle:Income:edit.html.twig', array(
				'Year' => $Year,
				'buttons' => $buttons,
				'form' => $form->createView()));	
		}		
	}

    public function moveAction($income_id, $dir, Request $request)
    {
		$Income = IncomeQuery::create()->findPk($income_id); 
		$Project = $Income->getProject();
		$Year = $Project->getYear(); 
						
		if ($dir == 'up')   { $Income->moveUp(); }		
		if ($dir == 'down') { $Income->moveDown(); }
		
		$Income->save();
		
		return $this->redirect($this->generateUrl('oppen_project',array(
			'year_id' => $Year->getId(),
			'tab_id' => 2,
			'project_id' => $Project->getId())));		
	}

    public function addDocAction($income_id, $month_id, $doc_cat_id, Request $request)
    {			
		$msg = array('errors' => array(), 'warnings' => array(), 'messages' => array());
		
		$Income = IncomeQuery::create()->findPk($income_id); 
		$Project = $Income->getProject();
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
		$form = $this->createForm(new DocListType($Year, null, true, false, 
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
						$IncomeDoc = new IncomeDoc();
						$IncomeDoc->setValue(0);
						$IncomeDoc->setDoc($Doc);
						$IncomeDoc->setIncome($Income);
					}
					$Income->addIncomeDoc($IncomeDoc);
				}
			}
			$Income->save();
			$return = 'project';
		}	
		
		if($return == 'project') {
			return $this->redirect($this->generateUrl('oppen_project',array(
				'year_id' => $Year->getId(),
				'tab_id' => 2,
				'project_id' => $project_id)));	
		} else {	
			return $this->render('OppenProjectBundle:Doc:list.html.twig',array(
				'Year' => $Year,
				'Month' => $Month,
				'DocCat' => $DocCat,
				'form' => $form->createView(),
				'buttons' => array('cancel'),
				'project_id' => $project_id,
				'return' => 'project',
				'id1' => $project_id,
				'id2' => 2,				
				'subtitle' => ' > '.$Income->getName().' / '.$Project->getName() ));	
		}
	}	
	
    public function removeDocAction($income_doc_id, Request $request)
    {
		$IncomeDoc  = IncomeDocQuery::create()->findPk($income_doc_id); 
		$Income     = $IncomeDoc->getIncome();
		$Project    = $Income->getProject();
		$Year = $Project->getYear(); 
				
		$IncomeDoc->delete();
		
		return $this->redirect($this->generateUrl('oppen_project',array(
			'year_id' => $Year->getId(),
			'tab_id' => 2,
			'project_id' => $Project->getId())));		
	}

	static public function generateData($form, $msg) {
	
		$Project = $form->getData();
		$Data[0]=array('SUM'=>array());
		
		foreach ($form->get('Incomes') as $FIncome) {
			
			$Income = $FIncome->getData();
			$IncomeAcc = current(array_filter(array($Income->getIncomeAcc(),$Project->getIncomeAcc())));
			$IncomeBankAcc = current(array_filter(array($Income->getBankAcc(),$Project->getBankAcc())));
			$IncomeFile = $Income->getFile();
			if(!($IncomeFile instanceOf File)) {
				$msg['errors'][] = 'Brak kartoteki dla grupy przychodów '.$Income->getName(); 
				return $msg; 
			}
			$IncomeFileCat = $IncomeFile->getFileCat();
			
			foreach ($FIncome->get('IncomeDocs') as $FIncomeDoc) {
			
				if(($FIncomeDoc->get('select')->getData() ) && 
					($FIncomeDoc->getData()->getValue() > 0)){
					
					$IncomeDoc = $FIncomeDoc->getData();
					$gross = $IncomeDoc->getValue();
					$ID_desc = $IncomeDoc->getDesc();
					$Doc = $IncomeDoc->getDoc();
					if(!($Doc instanceOf Doc)) { 
						$msg['errors'][] = 'Brak dokumentu w grupie przychodów '.$Income->getName(); 
						return $msg; 
					}
					$D = $Doc->getId();
					
					$DocFile = $Doc->getFile();
					$DocCat = $Doc->getDocCat();
					if(!($DocCat instanceOf DocCat)) {
						$msg['errors'][] = 'Dokument (id '.$D.') nie ma kategorii'; 
						return $msg;
					}
					$CommitAcc = $DocCat->getCommitmentAcc();
							
					if(!array_key_exists(0,$Data[0]['SUM'])) { 
						$Data[0]['SUM'][0] = array('gross'=>0, 'IncomeBankAcc' => $IncomeBankAcc);}
					$Data[0]['SUM'][0]['gross']    += $gross;
					
					$Data[$D]=array('SUM'=>array());
					$Data[$D]['SUM'][0] = array();						
					$Data[$D]['SUM'][0]['gross']     = $gross;						
					$Data[$D]['SUM'][0]['ID_desc']     = $ID_desc;						
					$Data[$D]['SUM'][0]['Doc']       = $Doc;
					$Data[$D]['SUM'][0]['GroupAcc']  = $IncomeAcc;
					$Data[$D]['SUM'][0]['IncomeBankAcc']  = $IncomeBankAcc;
					$Data[$D]['SUM'][0]['CommitAcc'] = $CommitAcc;
					$Data[$D]['SUM'][0]['GroupFile'] = $IncomeFile;						
					$Data[$D]['SUM'][0]['SelDocFile']= $DocFile;						
				}
			}
		}
		
		$msg['Data'] = $Data;
		return $msg;	
	}
		
	static public function generateBookks($form, $BookingTemplates, $msg) {		
		
		$msg = IncomeController::generateData($form, $msg);
			
		if(empty($msg['errors'])) {
			return BookkController::generateBookks($form, $BookingTemplates, $msg);
		} else {
			return $msg;
		}
	}
		
}
