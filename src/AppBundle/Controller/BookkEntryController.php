<?php

namespace AppBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Model\Year;
use AppBundle\Model\Account;
use AppBundle\Model\AccountFiles;
use AppBundle\Model\Doc;
use AppBundle\Model\Bookk;
use AppBundle\Model\BookkEntry;

use AppBundle\Model\YearQuery;
use AppBundle\Model\AccountQuery;
use AppBundle\Model\FileQuery;
use AppBundle\Model\BookkQuery;
use AppBundle\Model\BookkEntryQuery;
use AppBundle\Model\ParameterQuery;

use AppBundle\Form\Type\AccountType;
use AppBundle\Form\Type\AccountFilesType;
use AppBundle\Form\Type\BookkType;
use AppBundle\Form\Type\BookkEntryType;

class BookkEntryController extends Controller
{		
    public function editAction($bookk_entry_id, $bookk_id, $side, $return, $id1, $id2, Request $request)
    {
		$buttons = array('cancel','save');
		$msg = array('errors' => array(), 'warnings' => array());
				
		$Bookk = BookkQuery::create()->findPk($bookk_id);
		if(!($Bookk instanceOf Bookk)) {
			throw new \exception('No valid Bookk id '. $bookk_id);}	

		$Doc = $Bookk->getDoc();
		if(!($Doc instanceOf Doc)) {
			throw new \exception('No valid Doc for Bookk id '. $bookk_id);}		
				
		$Month = $Doc->getMonth();
		$Year  = $Month->getYear();
		
		if($bookk_entry_id == 0) {
			$BookkEntry = new BookkEntry();
			$BookkEntry->setBookk($Bookk);
			$BookkEntry->setSide($side);
		} else {
			$BookkEntry = BookkEntryQuery::create()->findPk($bookk_entry_id);
			if(!($BookkEntry instanceOf BookkEntry)) {
				throw new \exception('No valid BookkEntry id '. $bookk_entry_id);}	
			$buttons[] = 'delete';
		}
				
		$security_context = $this->get('security.context');
		$disable_accepted_docs = ParameterQuery::create()->getOneByName('disable_accepted_docs');
		
 		$form = $this->createForm(new BookkEntryType($Year,$security_context,$disable_accepted_docs), $BookkEntry); 			
        $form->handleRequest($request);  
        		
 		$params = array(
			'BookkEntry'=> $BookkEntry,
			'bookk_entry_id' => $bookk_entry_id,
			'form' => $form->createView(),
			'errors' => $msg['errors'],
			'buttons' => $buttons,			
			'return' => $return,
			'id1' => $id1,
			'id2' => $id2, );       		
        		
		$redirect = true;
		$html = '';
		
		if ( (!$form->isSubmitted()) || (($form->get('save')->isClicked()) && (!$form->isValid())) ) { 	
			
			$params['twig'] = 'AppBundle:BookkEntry:edit.html.twig';
			$params['js'] =   'AppBundle:BookkEntry:edit.js.twig';
			$js   = 'REFRESH_FORM'; 	
			
			$redirect = false;
		} 
						
		if ($form->isSubmitted()) {
			
			$params['twig'] = 'AppBundle:BookkEntry:view.html.twig';	
			
			if (($form->get('save')->isClicked()) && ($form->isValid())) 
			{ 	
				$BookkEntry->prepareToSave()->save();
				$js = $bookk_entry_id == 0 ? 'APPEND' : 'REPLACE'; 				
			}						
			
			if ($form->get('delete')->isClicked()) 
			{ 	
				$BookkEntry->delete(); 
				$js = 'REMOVE'; 
			}
			
			if ($form->get('cancel')->isClicked()) 
			{ 	
				$js = 'CANCEL';  
			}			
		}			
		
		if ($request->isXmlHttpRequest()) 
		{	
			$html = $this->renderView($params['twig'], $params); 
			return new JsonResponse(array('status'=>'success', 'html'=>$html, 'js'=>$js, ), 200); 	
		}
		
		if (!$redirect) 
		{			
			return $this->render('AppBundle:Template:content.html.twig', $params); 
		} 
		else 
		{
			return $this->redirect($this->generateUrl('oppen_doc',array(
				'doc_id' => $Doc->getId(),
				'month_id' => $Month->getId(),
				'doc_cat_id'=> $Doc->getDocCat()->getId(),					
				'return' => $return,
				'id1' => $id1,
				'id2' => $id2,
			) ));						
		}	

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
