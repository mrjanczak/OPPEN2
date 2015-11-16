<?php

namespace Oppen\ProjectBundle\Controller;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \PropelObjectCollection;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Oppen\ProjectBundle\Controller\DcoController;

use Oppen\ProjectBundle\Model\Year;
use Oppen\ProjectBundle\Model\YearList;
use Oppen\ProjectBundle\Model\AccountList;
use Oppen\ProjectBundle\Model\CategoryList;
use Oppen\ProjectBundle\Model\ReportList;
use Oppen\ProjectBundle\Model\TemplateList;
use Oppen\ProjectBundle\Model\ParameterList;
use Oppen\ProjectBundle\Model\Doc;
use Oppen\ProjectBundle\Model\File;
use Oppen\ProjectBundle\Model\FileCat;
use Oppen\ProjectBundle\Model\DocCat;
use Oppen\ProjectBundle\Model\Account;

use Oppen\ProjectBundle\Model\YearQuery;
use Oppen\ProjectBundle\Model\MonthQuery;
use Oppen\ProjectBundle\Model\ProjectQuery;
use Oppen\ProjectBundle\Model\AccountQuery;
use Oppen\ProjectBundle\Model\DocQuery;
use Oppen\ProjectBundle\Model\DocCatQuery;
use Oppen\ProjectBundle\Model\FileQuery;
use Oppen\ProjectBundle\Model\FileCatQuery;
use Oppen\ProjectBundle\Model\BookkQuery;
use Oppen\ProjectBundle\Model\ReportQuery;
use Oppen\ProjectBundle\Model\TemplateQuery;
use Oppen\ProjectBundle\Model\ParameterQuery;

use Oppen\ProjectBundle\Form\Type\YearListType;
use Oppen\ProjectBundle\Form\Type\AccountListType;
use Oppen\ProjectBundle\Form\Type\CategoryListType;
use Oppen\ProjectBundle\Form\Type\ReportListType;
use Oppen\ProjectBundle\Form\Type\TemplateListType;
use Oppen\ProjectBundle\Form\Type\ParameterListType;

class SettingsController extends Controller
{
	public function homeAction()
	{
		$Year  = YearQuery::getFirstActive();

		return $this->redirect($this->generateUrl('oppen_projects', array(
			'year_id' => $Year->getId(),
			'search_name' => '*'	)));
	}

    public function menuAction()
    { 
		$Year  = YearQuery::getFirstActive();
		$Month = MonthQuery::getFirstActive($Year);
			
		$DocCat = DocCatQuery::create()->orderByName()->findOneByYear($Year);
		if (!($DocCat instanceOf DocCat)) { $DocCat = DocCatQuery::create()->findOne(); }

		$FileCat = FileCatQuery::create()->orderByName()->findOneByYear($Year);
		if (!($FileCat instanceOf FileCat)) { $FileCat = FileCatQuery::create()->findOne(); }
						
        return $this->render('OppenProjectBundle:Settings:menu.html.twig',array(
			'Year' => $Year,
			'Month' => $Month,
			'DocCat' => $DocCat,
			'FileCat' => $FileCat,
			'settings_tab_id' => 1));
    }
    
    public function editAction($tab_id, $year_id,  Request $request)
    {		
		if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
			throw new AccessDeniedException(); }		
		
		$Year = YearQuery::create()->findPk($year_id);
		if(!($Year instanceOf Year)) {
			throw new \Exception('Year not exists');}
							
		//$tabs = array('Okresy','Plan Kont','Kategorie','Raporty','Szablony','Parametry','Użytkownicy');    
		$tabs = array('periods','accounts','categories','reports','templates','parameters','users');    
		$buttons = array();
		$data = array();
		
		switch ($tab_id) {
			case 1: 							
				$Years = YearQuery::create()->orderByRank('desc')->find();
				$FileCats = FileCatQuery::create()->orderByName()->findByYear($Year);
				foreach($FileCats as $k=>$FileCat) {
					if(in_array($FileCat->getSymbol(),array('PR'))) {
						$FileCats[$k]->select2 = false; }};
				$DocCats =  DocCatQuery::create()->orderByName()->findByYear($Year);
				foreach($DocCats as $k=>$DocCat) {
					if(in_array($DocCat->getSymbol(),array('FV','PK','Ra','RaW','Uch','Um'))) {
						$DocCats[$k]->select2 = false; }};				
				$form = $this->createForm(new YearListType($Year), new YearList($Years, $FileCats, $DocCats));
				$buttons[] = 'delete';			
				break;
			case 2: 
				$Root = AccountQuery::create()->findRoot($Year->getId());
				if(!($Root instanceOf Account)) {
					throw new \Exception('Accounts root not exists');}
				$Accounts = $Root->getBranch();							
				$form = $this->createForm(new AccountListType($Year), new AccountList($Year, $Accounts));		
				break;
			case 3: 
				$FileCats = FileCatQuery::create()->orderByName()->findByYear($Year);								
				$DocCats =  DocCatQuery::create()->orderByName()->findByYear($Year);
				$form = $this->createForm(new CategoryListType($Year), new CategoryList($Year, $FileCats, $DocCats));	
				break;
			case 4: 
				$Reports = ReportQuery::create()->orderByRank()->findByYear($Year);								
				$form = $this->createForm(new ReportListType($Year), new ReportList($Year, null, null, null, $Reports));	
				break;
			case 5:
				$Templates = TemplateQuery::create()->orderByName()->find();
				$form = $this->createForm(new TemplateListType(), new TemplateList($Templates));	
				break;
			case 6:
				$Parameters = ParameterQuery::create()->orderByRank()->find();
				$form = $this->createForm(new ParameterListType(), new ParameterList($Parameters));	
				$buttons = array('save','cancel');
				break;
			case 7:
		        $data['users'] = $this->container->get('fos_user.user_manager')->findUsers();
				$form = null;	
				$buttons = array('delete','save','cancel');
				break;
		}
		//*****************************************************
		if ($form) {$form->handleRequest($request);}
		//*****************************************************
		$errors = array();
		$refresh = false;
		
		if ($tab_id == 1 && $form->isSubmitted()) {
			
			if($form->get('add_next')->isClicked() || $form->get('add_prev')->isClicked()) {
			
				$Year = $this->newYear($form);
				$refresh = true;
			}
			
			if($form->get('delete')->isClicked() ) {
				foreach ($form->get('Years') as $FYear) {
					if($FYear->get('select')->getData()) {	
						$DYear = $FYear->getData();		
						$err = 'Nie można usunąć roku '.$DYear->getName().' - posiada ';				
						$count = ProjectQuery::create()->filterByYear($DYear)->count();
						if($count > 0){ $errors[] = $err.$count.' projekty'; }
						
						$count = BookkQuery::create()
							->filterByIsAccepted(1)
							->useDocQuery()
								->useMonthQuery()
									->filterByYear($DYear)
								->endUse()
							->endUse()
							->count();
						if($count > 0){ $errors[] = $err.$count.' księgowania'; }	
						if(count($errors) == 0) { 
							$DYear->delete(); }
					}
				}	
				$Year = YearQuery::create()->orderByFromDate('desc')->findOne();
				$refresh = true;		
			}
				
		}
		
		if (in_array($tab_id, array(2,3,4,5)) && $form->get('search')->isClicked()) {
			if(in_array($tab_id, array(2,3,4))) {
				$Year = $form->get('Year')->getData(); 
				return $this->redirect($this->generateUrl('oppen_settings', array(
						'tab_id'  => $tab_id,
						'year_id' => $Year->getId()) ));				
			}
			if($tab_id == 5 && $form->get('show_archived')->get_data() == 1) {
				$Templates = TemplateArchiveQuery::create()->orderByName()->find();
				$form = $this->createForm(new TemplateListType($Year), new TemplateList($Year, $Templates));
			}
		}
		
		if ($tab_id == 6 && $form->get('save')->isClicked()) {
			foreach($form->get('Parameters') as $FParam){
				$FParam->getData()->save();}
		}	

		if ($tab_id == 6 && $form->get('cancel')->isClicked()) {
			$refresh = true;}
		
		if($refresh) {
			return $this->redirect($this->generateUrl('oppen_settings', array(
				'tab_id' => $tab_id,
				'year_id' => $Year->getId()) )); 			
		}		
		
		return $this->render('OppenProjectBundle:Settings:edit.html.twig',
			array(	'Year' => $Year,
					'data' => $data,
					'form' => $form->createView(),
					'tabs' => $tabs,
					'tab_id' => $tab_id,
					'buttons' => $buttons,
					'errors' => $errors ));	
	}	
	
	public function newYear($form) {
		if($form->get('add_next')->isClicked()) {$sign = '+1';}
		if($form->get('add_prev')->isClicked()) {$sign = '-1';}
		$offset = $sign.' year';
				
		$Year = YearQuery::create()
			->orderByFromDate($sign == '+1' ? 'desc' : 'asc')
			->findOne();
			
		if(!($Year instanceOf Year)) {
			throw new \Exception('Year not exists');}
			
		$NewYear = $Year->copy();
		if(is_numeric($Year->getName())) {$NYname = intval($Year->getName()) + intval($sign);}
		else { $NYname = $Year->getToDate()->modify($offset)->format('Y');};
		
		$NewYear->setName($NYname)
				->setFromDate($Year->getFromDate()->modify($offset))
				->setToDate($Year->getToDate()->modify($offset))
				->setRank($Year->getRank() + intval($sign))
				->setIsClosed(false)
				->setIsActive(false)
				->save();
		if(!($NewYear instanceOf Year)) {
			throw new \Exception('NewYear not exists');}
										
		$Months = MonthQuery::create()->filterByYear($Year)->orderByRank()->find();			
		foreach($Months as $Month) {
			$NYMonth = $Month->copy()
				->setYear($NewYear)
				->setFromDate($Month->getFromDate()->modify($offset))
				->setToDate($Month->getToDate()->modify($offset))
				->setIsClosed(false)
				->setIsActive(false)
				->setRank($Month->getRank() + intval($sign) * 12)
				->save();	
		}							

		// copy selected FileCats
		$FCSymbols = array();	
		$FileCatsF = $form->get('FileCats');
		foreach ($FileCatsF as $FileCatF) {
			if($FileCatF->get('select')->getData())	{
				$FCSymbols[] = $FileCatF->getData()->getSymbol(); } }
		$FileCats = FileCatQuery::create()->filterByYear($Year)->filterBySymbol($FCSymbols)->find();
		foreach($FileCats as $FileCat) {
			$NYFileCat = $FileCat->copy()->setYear($NewYear);					
			$NYFileCat->save();
		}
		
		// update SubFileCats
		$NYFileCats = FileCatQuery::create()->filterByYear($NewYear)->find();
		foreach($NYFileCats as $NYFileCat) {
			$SubFileCat = $NYFileCat->getSubFileCat();
			if($SubFileCat instanceOf FileCat) {
				$NYSubFileCat = FileCatQuery::create()
					->filterByYear($NewYear)
					->filterBySymbol($SubFileCat->getSymbol())
					->findOne();
				$NYFileCat->setSubFileCat($NYSubFileCat)
					->save(); 
			}					
		}
		
		// copy Files from selected FileCats
		foreach ($FileCatsF as $FileCatF) {
			if($FileCatF->get('select')->getData() && $FileCatF->get('select2')->getData())	{
				$FileCat = $FileCatF->getData();
				if($FileCat instanceOf FileCat) {
					$NYFileCat = FileCatQuery::create()->filterByYear($NewYear)->findOneBySymbol($FileCat->getSymbol());
					$Files = $FileCat->getFiles();
					foreach($Files as $File) {
						$NYFile = $File->copy()->setFileCat($NYFileCat);
						$SubFile = $NYFile->getSubFile();
						if($SubFile instanceOf File) {
							$NYSubFile = FileQuery::create()
								->useFileCatQuery()
									->filterByYear($NewYear)
								->endUse()	
								->findOneByAccNo($SubFile->getAccNo());
							$NYFile->setSubFile($NYSubFile);
						}
						$NYFile->save();
					}
				}
			}
		}
		
		// copy DocCats
		$DCSymbols = array();	
		$DocCatsF = $form->get('DocCats');
		foreach ($DocCatsF as $DocCatF) {
			if($DocCatF->get('select')->getData())	{
				$DCSymbols[] = $DocCatF->getData()->getSymbol(); 
			} 
		}
		$DocCats = DocCatQuery::create()->filterByYear($Year)->filterBySymbol($DCSymbols)->find();
		foreach($DocCats as $DocCat) {
			$NYDocCat = $DocCat->copy()
				->setYear($NewYear)
				->save();
		}
						
		// copy Docs form selected DocCats	
		$DCSymbols = array();			
		foreach ($DocCatsF as $DocCatF) {
			if($DocCatF->get('select')->getData() && $DocCatF->get('select2')->getData())	{
				$DCSymbols[] = $DocCatF->getData()->getSymbol(); 
			} 
		}
		$DocCats = DocCatQuery::create()->filterByYear($Year)->filterBySymbol($DCSymbols)->find();
		foreach($DocCats as $DocCat) {
			$NYDocCat = DocCatQuery::create()
				->filterByYear($NewYear)
				->filterBySymbol($DocCat->getSymbol())
				->findOne();
				
			$Docs = $DocCat->getDocs();					
			foreach($Docs as $Doc) {						
				$Month = $Doc->getMonth();
				$NYMonth = MonthQuery::create()->filterByYear($NewYear)->findOneByName($Month->getName() );
				
				$Dates['DocumentDate'] = $Doc->getDocumentDate();
				$Dates['OperationDate'] = $Doc->getOperationDate();
				$Dates['ReceiptDate'] = $Doc->getReceiptDate();
				$Dates['BookkingDate'] = $Doc->getBookkingDate();
				$Dates['PaymentDeadlineDate'] = $Doc->getPaymentDeadlineDate();
				$Dates['PaymentDate'] = $Doc->getPaymentDate();
				
				foreach($Dates as $key => $Date) {
					if ($Date instanceOf \DateTime) {
						$Dates[$key] = $Date->modify($offset);}		
				}
																					
				$NYDoc = $Doc->copy();
				if(!($NYDoc instanceOf Doc)) {
					throw new \Exception('NYDoc not exists');}	
								
				$NYDoc->setDocCat($NYDocCat)
					->setMonth($NYMonth)
					->setRegIdx(NULL)
					->setRegNo(NULL)
					->setNewDocIdx()	
					->setNewDocNo()	
					->setDocumentDate($Dates['DocumentDate'])
					->setOperationDate($Dates['OperationDate'])
					->setReceiptDate($Dates['ReceiptDate'])
					->setBookkingDate($Dates['BookkingDate'])
					->setPaymentDeadlineDate($Dates['PaymentDeadlineDate'])
					->setPaymentDate($Dates['PaymentDate'])				
					
					->clearBookks()				
					->save(); 
			}
		}
		
		// copy Accounts
		if($form->get('copy_accounts')->getData()) {
			
			$Root = AccountQuery::create()->findRoot($Year->getId());
			if(!($Root instanceOf Account)) {
				throw new \Exception('Accounts root not exists in '.$Year->getName());}
				
			$Accounts = $Root->getBranch();	
			foreach($Accounts as $Account) {
				$NYAccount = $Account->copy();
				$FileLevels = array();
				for($lev = 1; $lev <=3; $lev++) {
					eval('$FileCat = $Account->getFileCatLev'.$lev.'();'); 
					if($FileCat instanceOf FileCat) {
						$symbol = $FileCat->getSymbol();
						$NYFileCats[$lev] = FileCatQuery::create()
							->filterByYear($NewYear)
							->filterBySymbol($symbol)
							->findOne();
					} else $NYFileCats[$lev] = null;
				}
				$NYAccount->setYear($NewYear)
					->setFileCats($NYFileCats)
					->save();
			}			
		}	
		
		// copy Reports
		if($form->get('copy_reports')->getData()) {
			$Reports = ReportQuery::create()->filterByYear($Year)->find();
			foreach($Reports as $Report) {
				$NYReport = $Report->copy()->setYear($NewYear);
				foreach($Report->getReportEntries() as $ReportEntry) {
					$NYReportEntry = $ReportEntry->copy()->setReport($NYReport); }
				$NYReport->save();
			}	
		}
		
		return $NewYear;
	}
/*	
	public static function street_prefix($street) {
		return in_array(strtolower(substr($street,0,3)),array('ul.','al.','pl.','os.'))?'':'ul.'; }

	public static function flat_prefix($flat) {
		return ($flat == NULL || $flat == '' )?'':' m.'; }
*/		
	public static function utf_win($text) {
		// map based on http://konfiguracja.c0.pl/iso02vscp1250en.html
		// utf-8 to iso88592 to windows1250
		$map = array(
			 chr(0xA9)	 =>	 chr(0x8A), 
			 chr(0xA6)	 =>	 chr(0x8C),
			 chr(0xAB)	 =>	 chr(0x8D), 
			 chr(0xAE)	 =>	 chr(0x8E), 
			 chr(0xAC)	 =>	 chr(0x8F), 
			 chr(0xB6)	 =>	 chr(0x9C), 
			 chr(0xBB)	 =>	 chr(0x9D), 
			 chr(0xB7)	 =>	 chr(0xA1), 
			 chr(0xA1)	 =>	 chr(0xA5), 
			 chr(0xA5)	 =>	 chr(0xBC), 
			 chr(0xBC)	 =>	 chr(0x9F), 
			 chr(0xB1)   =>	 chr(0xB9), 
			 chr(0xB9)	 =>	 chr(0x9A), 
			 chr(0xB5)	 =>	 chr(0xBE), 
			 chr(0xBE)	 =>	 chr(0x9E) 
		);

		return strtr(iconv('UTF-8','ISO-8859-2',$text), $map);
	}			
		
}
