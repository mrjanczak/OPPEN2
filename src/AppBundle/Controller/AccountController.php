<?php
namespace AppBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Model\Year;
use AppBundle\Model\Account;
use AppBundle\Model\AccountsList;
use AppBundle\Model\File;
use AppBundle\Model\FileCat;
use AppBundle\Model\BookkEntry;

use AppBundle\Model\YearQuery;
use AppBundle\Model\AccountQuery;
use AppBundle\Model\FileQuery;
use AppBundle\Model\FileCatQuery;
use AppBundle\Model\BookkEntryQuery;
use AppBundle\Model\ProjectQuery;
use AppBundle\Model\CostQuery;
use AppBundle\Model\IncomeQuery;

use AppBundle\Form\Type\AccountType;
use AppBundle\Form\Type\AccountsListType;
use AppBundle\Form\Type\BookkEntryType;

use AppBundle\Model\Section;

class AccountController extends Controller
{
    public function editAction($account_id, $parent_id, Request $request)
    {
		$buttons = array('cancel','save');	
			
		if($account_id == 0) {
			$Parent = AccountQuery::create()->findPk($parent_id);
			$Year = $Parent->getYear();
			$Account = new Account();
			$Account->setYear($Year);
			$Account->insertAsLastChildOf($Parent);						
		}
		else {
			$Account = AccountQuery::create()->findPk($account_id);
			$Parent = $Account->getParent();
			$Year = $Parent->getYear();			
			$buttons[] = 'delete';
		}
		$Account->Parent = $Parent;
		
 		$form = $this->createForm(new AccountType($Year, true), $Account);
        $form->handleRequest($request); 
        $errors = array();
		$redirect = false;
		
		if ($form->isSubmitted()) {
			if  ($form->get('cancel')->isClicked()) { $redirect = true; }
			if (($form->get('save')->isClicked()) && ($form->isValid())) { 
				$Account->save(); 
				if($Account->getName() == '') {
					$Account->setName('Id'.$Account->getId())->save(); }
				$redirect = true;
			}
			if  ($form->get('delete')->isClicked()) { 
				$err = 'Nie można usunąć konta - jest użyte ';
				$count  = BookkEntryQuery::create()->filterByAccount($Account)->count();
				if($count > 0){$errors[] = $err.$count.' razy w dekretacjach)';}
				
				$count  = ProjectQuery::create()->filterByIncomeAcc($Account)->count();
				$count += ProjectQuery::create()->filterByCostAcc($Account)->count();
				$count += ProjectQuery::create()->filterByBankAcc($Account)->count();
				if($count > 0){$errors[] = $err.$count.' razy w projektach';}
				
				$count  = IncomeQuery::create()->filterByIncomeAcc($Account)->count();
				$count += IncomeQuery::create()->filterByBankAcc($Account)->count();
				$count += CostQuery::create()->filterByCostAcc($Account)->count();
				$count += CostQuery::create()->filterByBankAcc($Account)->count();
				if($count > 0){$errors[] = $err.$count.' razy w przychodach lub kosztach projektów';}
				
				if(count($errors) == 0) {		
					$Account->delete(); 
					$redirect = true;
				}
			}			
		}
		
		if($redirect) {
			return $this->redirect($this->generateUrl('oppen_settings', array(
				'tab_id' => 2,
				'year_id' => $Year->getId()) )); 
		}		
		
		return $this->render('AppBundle:Account:edit.html.twig',array(
			'Year' => $Year,
			'form' => $form->createView(),
			'buttons' => $buttons,
			'errors' => $errors	) );
	}		

    public function moveAction($account_id, $dir, Request $request)
    {
		$Account = AccountQuery::create()->findPk($account_id); 
		$Year = $Account->getYear(); 
				
		if ($dir == 'up')   { 
			$PrevSibling = $Account->getPrevSibling();
			if($PrevSibling instanceOf Account) {
				$Account->moveToPrevSiblingOf($PrevSibling);}
		}		
		if ($dir == 'down')   { 
			$NextSibling = $Account->getNextSibling();
			if($NextSibling instanceOf Account) {
				$Account->moveToNextSiblingOf($NextSibling);}
		}			
		return $this->redirect($this->generateUrl('oppen_settings', array(
			'tab_id' => 2,
			'year_id' => $Year->getId()) )); 
	}

    public function selectAction($year_id, $account_id)
    {
		$Year = YearQuery::create()->findPk($year_id);
		$Account = AccountQuery::create()->findPk($account_id);	
		
	    if($Account instanceOf Account) {
			$BookkEntry = new BookkEntry();
			$BookkEntry->setAccount($Account);
					
			$form = $this->createForm(new BookkEntryType($Year, $Account), $BookkEntry); 
			return $this->render('AppBundle:Account:select.html.twig',array(		
				'form' => $form->createView() ));
		}		
	}

	public function updateAction($account_id, Request $request) {
		
		$Account = AccountQuery::create()->findPk($account_id);
		
		$FilesLev1 = array();
		$FilesLev2 = array();
		$FilesLev3 = array();
		$Labels = array();
		
		if($Account instanceOf Account) {	
			$FileCatLev1 = $Account->getFileCatLev1();
			$FileCatLev2 = $Account->getFileCatLev2();
			$FileCatLev3 = $Account->getFileCatLev3();
		
			if($FileCatLev1 instanceOf FileCat) {
				$Labels[] = $FileCatLev1->getName();
				foreach ($FileCatLev1->getFiles() as $File) {
					$FilesLev1[] = '<option value="'.$File->getId().'">'.$FileCatLev1->getSymbol().'|'.
						$this->fileNo($File->getAccNo(),$File->getName()).'</option>'; }}
			
			if($FileCatLev2 instanceOf FileCat) {					
				$Labels[] = $FileCatLev2->getName();
				foreach ($FileCatLev2->getFiles() as $File) {
					$FilesLev2[] = '<option value="'.$File->getId().'">'.$FileCatLev2->getSymbol().'|'.
						$this->fileNo($File->getAccNo(),$File->getName()).'</option>'; }}
			
			if($FileCatLev3 instanceOf FileCat) {					
				$Labels[] = $FileCatLev3->getName();
				foreach ($FileCatLev3->getFiles() as $File) {
					$FilesLev3[] = '<option value="'.$File->getId().'">'.$FileCatLev3->getSymbol().'|'.
						$this->fileNo($File->getAccNo(),$File->getName()).'</option>'; }}		}

		$response = new JsonResponse();
		$response->setData(array(
			array($FilesLev1,$FilesLev2,$FilesLev3), $Labels ));

		return $response;
	}

	public function fileNo($AccNo, $Name) {
	
		return (string) substr('00'.$AccNo,-3).' - '.$Name;
	}
}	

