<?php

namespace AppBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Model\Year;
use AppBundle\Model\Month;
use AppBundle\Model\Account;
use AppBundle\Model\AccountFiles;
use AppBundle\Model\Bookk;
use AppBundle\Model\BookkEntry;
use AppBundle\Model\BookksList;
use AppBundle\Model\File;
use AppBundle\Model\FileCat;
use AppBundle\Model\Doc;
use AppBundle\Model\DocCat;
use AppBundle\Model\DocsList;
use AppBundle\Model\Project;
use AppBundle\Model\Income;
use AppBundle\Model\IncomeDoc;
use AppBundle\Model\Cost;
use AppBundle\Model\CostIncome;
use AppBundle\Model\CostDoc;
use AppBundle\Model\CostDocIncome;

use AppBundle\Model\YearQuery;
use AppBundle\Model\MonthQuery;
use AppBundle\Model\BookkQuery;
use AppBundle\Model\BookkEntryQuery;
use AppBundle\Model\AccountQuery;
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

use AppBundle\Form\Type\AccountType;
use AppBundle\Form\Type\AccountFilesType;
use AppBundle\Form\Type\BookkType;
use AppBundle\Form\Type\BookksListType;
use AppBundle\Form\Type\BookkEntryType;
use AppBundle\Form\Type\ProjectType;
use AppBundle\Form\Type\IncomeType;
use AppBundle\Form\Type\CostType;

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
									
		return $this->render('AppBundle:BookkEntry:edit.html.twig',array(
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

		return $this->render('AppBundle:BookkEntry:AFform.html.twig',array('form' => $form->createView() ));
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
		
		return $this->render('AppBundle:BookkEntry:list.html.twig',array('Year' => $Year, 'BookkEntries' => $BookkEntries ));
	}	

}
