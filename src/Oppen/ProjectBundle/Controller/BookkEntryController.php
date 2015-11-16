<?php

namespace Oppen\ProjectBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Oppen\ProjectBundle\Model\Year;
use Oppen\ProjectBundle\Model\Month;
use Oppen\ProjectBundle\Model\Account;
use Oppen\ProjectBundle\Model\AccountFiles;
use Oppen\ProjectBundle\Model\Bookk;
use Oppen\ProjectBundle\Model\BookkEntry;
use Oppen\ProjectBundle\Model\BookksList;
use Oppen\ProjectBundle\Model\File;
use Oppen\ProjectBundle\Model\FileCat;
use Oppen\ProjectBundle\Model\Doc;
use Oppen\ProjectBundle\Model\DocCat;
use Oppen\ProjectBundle\Model\DocsList;
use Oppen\ProjectBundle\Model\Project;
use Oppen\ProjectBundle\Model\Income;
use Oppen\ProjectBundle\Model\IncomeDoc;
use Oppen\ProjectBundle\Model\Cost;
use Oppen\ProjectBundle\Model\CostIncome;
use Oppen\ProjectBundle\Model\CostDoc;
use Oppen\ProjectBundle\Model\CostDocIncome;

use Oppen\ProjectBundle\Model\YearQuery;
use Oppen\ProjectBundle\Model\MonthQuery;
use Oppen\ProjectBundle\Model\BookkQuery;
use Oppen\ProjectBundle\Model\BookkEntryQuery;
use Oppen\ProjectBundle\Model\AccountQuery;
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

use Oppen\ProjectBundle\Form\Type\AccountType;
use Oppen\ProjectBundle\Form\Type\AccountFilesType;
use Oppen\ProjectBundle\Form\Type\BookkType;
use Oppen\ProjectBundle\Form\Type\BookksListType;
use Oppen\ProjectBundle\Form\Type\BookkEntryType;
use Oppen\ProjectBundle\Form\Type\ProjectType;
use Oppen\ProjectBundle\Form\Type\IncomeType;
use Oppen\ProjectBundle\Form\Type\CostType;

class BookkEntryController extends Controller
{		
    public function editAction($bookk_id, $bookk_entry_id, Request $request)
    {
		$Bookk = BookkQuery::create()->findPk($bookk_id);
		$Month = $Bookk->getMonth();
		$Year  = $Month->getYear();
		$buttons = array('cancel','save');
		
		if($bookk_entry_id == 0) {
			$BookkEntry = new BookkEntry();
			$BookkEntry->setBookk($Bookk);
		} else {
			$BookkEntry = BookkEntryQuery::create()->findPk($bookk_entry_id);
			$buttons[] = 'delete';
		}
				
 		$form = $this->createForm(new BookkEntryType($Year), new BookkEntry()); 			
        $form->handleRequest($request);  
        		
		if ($form->isSubmitted()) {
			if ($form->get('cancel')->isClicked()) {}
			if ($form->get('delete')->isClicked()) { $BookkEntry->delete(); }						
			if (($form->get('save')->isClicked()) && ($form->isValid())) { $BookkEntry->save();}	
			
			//return new Response('clicked');
		}	
									
		return $this->render('OppenProjectBundle:BookkEntry:edit.html.twig',array(
			'Bookk' => $Bookk,	
			'buttons' => $buttons,		
			'form' => $form->createView() ));
	}  		 
	
    //not used
	public function accountFilesAction($year_id, $account_id, $file_lev1_id, $file_lev2_id, $file_lev3_id, Request $request) {
		
		$Year = YearQuery::create()->findPk($year_id); 
		
		if($account_id > 0) { $Account = AccountQuery::create()->findPk($account_id); }
		else{ $Account = AccountQuery::create()->filterByYear($Year)->orderByAccNo()->findOne(); }
		if(!($Account instanceOf Account)) {
			throw new \exception('No valid Account in BookkEntryController.accountFilesAction');}	
				
		$FileLev1 = FileQuery::create()->findPk($file_lev1_id);
		$FileLev2 = FileQuery::create()->findPk($file_lev2_id);
		$FileLev3 = FileQuery::create()->findPk($file_lev3_id);

		$AccountFiles = new AccountFiles($Account, $FileLev1, $FileLev2, $FileLev3 );
		$form = $this->createForm(new AccountFilesType($Year, $Account), $AccountFiles); 			

		return $this->render('OppenProjectBundle:BookkEntry:AFform.html.twig',array('form' => $form->createView() ));
	}

	public function repairAction($year_id) {
		
		$Year = YearQuery::create()->findPk($year_id); 
		//if(!($Year instanceOf Year)) {
		//	throw new \exception('No valid Year in BookkEntryController.repairAction');}	
			
		/*
		$BookkEntries  = BookkEntryQuery::create()->find();

			->useBookkQuery()
				->useDocQuery()
					->useDocCatQuery()
						->filterByYear($Year)
						->filterBySymbol('WB')
					->endUse()
					->orderByDocNo()
				->endUse()
			->endUse()
			->useAccountQuery()
				//->filterByAccNo(200)
			->endUse()
			//->filterBySide(1)
		*/ 
					 
		/*
		foreach($BookkEntries as $BookkEntry) {	 			
				
			$FileLev[1] = $BookkEntry->getFileLev1();
			$FileLev[2] = $BookkEntry->getFileLev2();
			$FileLev[3] = $BookkEntry->getFileLev3();

			$BookkEntry->setBE($BookkEntry->getBookk(), $BookkEntry->getSide(), $BookkEntry->getValue(), $Account, $FileLev);
			
			if ($BookkEntry->getBookk() == null) {
				$BookkEntry->delete();
			} else {
				$BookkEntry->setValue(round($BookkEntry->getValue(),2))->save();
			}
			
		}
		*/
		
		$BookkEntries  = BookkEntryQuery::create()->find();
		
		return $this->render('OppenProjectBundle:BookkEntry:list.html.twig',array('Year' => $Year, 'BookkEntries' => $BookkEntries ));
	}	

}
