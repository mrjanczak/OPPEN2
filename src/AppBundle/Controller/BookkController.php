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
use AppBundle\Model\Doc;
use AppBundle\Model\File;
use AppBundle\Model\Bookk;
use AppBundle\Model\BookkEntry;

use AppBundle\Model\YearQuery;
use AppBundle\Model\AccountQuery;
use AppBundle\Model\DocQuery;
use AppBundle\Model\FileQuery;
use AppBundle\Model\BookkQuery;
use AppBundle\Model\ParameterQuery;

use AppBundle\Form\Type\BookkType;

class BookkController extends Controller
{
   
    public function editAction($bookk_id, $doc_id, $return, $id1, $id2, Request $request) {
		
		$buttons = array('cancel','save');
		$msg = array('errors' => array(), 'warnings' => array());
				
		$Doc = DocQuery::create()->findPk($doc_id); 
		if(!($Doc instanceOf Doc)) 
			{ throw $this->createNotFoundException('The Doc (id '.$doc_id.') does not exist'); }
			
		$Month =$Doc->getMonth();
		$Year  = $Month->getYear();
		
		if($bookk_id == 0) {	
			$Bookk = new Bookk();
			$Bookk->setDoc($Doc);
			$Bookk->setIsAccepted(false);
		} else {
			$Bookk = BookkQuery::create()->findPk($bookk_id);
			$buttons[] = 'delete';
		}
		
		$security_context = $this->get('security.context');
		$disable_accepted_docs = ParameterQuery::create()->getOneByName('disable_accepted_docs');
										
 		$form = $this->createForm(new BookkType($Year, true,$security_context,$disable_accepted_docs), $Bookk); 
        $form->handleRequest($request);  
		
		$params = array(
			'Doc' => $Doc,
			'Bookk'=> $Bookk,
			'bookk_id' => $bookk_id,
			'form' => $form->createView(),
			'errors' => $msg['errors'],
			'buttons' => $buttons,			
			'return' => $return,
			'id1' => $id1,
			'id2' => $id2, );
			
		$redirect = true;
		$html = '';
		
		if ( (!$form->isSubmitted()) || (($form->get('save')->isClicked()) && (!$form->isValid())) ) { 	
			
			$params['twig'] ='AppBundle:Bookk:edit.html.twig';
			$js = 'REFRESH_FORM'; 	
			
			$redirect = false;
		} 
						
		if ($form->isSubmitted()) 
		{	
			$params['twig'] = 'AppBundle:Bookk:view.html.twig';	
			
			if (($form->get('save')->isClicked()) && ($form->isValid())) 
			{ 	
				$Bookk->save();
				$js = $bookk_id == 0 ? 'APPEND' : 'REPLACE'; 				
			}						
			
			if ($form->get('delete')->isClicked()) 
			{ 	
				$Bookk->delete(); 
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
		
		if (!$redirect) {			
			return $this->render('AppBundle:Template:content.html.twig', $params); 
			
		} else {
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

	static public function generateBookks($form, $BookingTemplates, $msg) {

		$Data = $msg['Data'];
		
		$Project = $form->getData();
		$ProjectFile = $Project->getFile();
		if(!($ProjectFile instanceOf File)) {
			$msg['errors'][] = 'Brak kartoteki projektu'; }
		$Project_Name = $Project->getName();
		$Project_Year =  $Project->getYear();
		
		$payment_DCsymbol = $form->get('payment_DocCat_symbol')->getData();
		$payment_date = $form->get('payment_date')->getData();
		if(!($payment_date instanceOf \DateTime)) {
			$msg['errors'][] = 'Brak daty płatności'; }
					
		// find payment_Doc by date and docCat
		$payment_Doc = DocQuery::create()
			->useDocCatQuery()
				->filterBySymbol($payment_DCsymbol)
			->endUse()
			->useMonthQuery()
				->filterByFromDate(array('max'=>$payment_date))
				->filterByToDate(array('min'=>$payment_date))
			->endUse()
			->findOne();		
									
		$DocByNo = DocQuery::create()->findOneByDocNo($form->get('doc_no')->getData());			
				
		$BookksToSave = array();
		foreach($BookingTemplates as $TmpSymbol => $TmpB) {
			if($form->get($TmpSymbol)->getData()) {
				foreach($Data as $D => $B) {
					if(($TmpB['rows'] == 'SUM' && $D == 0) || 
					   ($TmpB['rows'] != 'SUM' && $D > 0)) {
						$Data2 = array();
						$sfx = ' |'.$TmpSymbol.'|doc_id'.$D;
						
						$SelDoc_No = '';
						
						//Document
						$Data2['Doc'] = array();
						$Data2['Doc']['PaymentDoc']  = $payment_Doc;						
						$Data2['Doc']['DocByNo']     = $DocByNo;	
											
						if(array_key_exists('Doc', $B['SUM'][0])) {
							$SelDoc = $B['SUM'][0]['Doc'];
							$Data2['Doc']['SelectedDoc'] = $SelDoc;
							if($SelDoc instanceOf Doc) {
								$SelDoc_No = $SelDoc->getDocNo();}
						}
											   
						if(!array_key_exists($TmpB['Doc'], $Data2['Doc'])) {
							$msg['errors'][] = 'Dokument '.$TmpB['Doc'].' jest niedostepny'.$sfx; 
							return $msg;
						}
						
						$Doc = $Data2['Doc'][$TmpB['Doc']]; 
						if(!($Doc instanceOf Doc)) {
							$msg['errors'][] = 'Dokument '.$TmpB['Doc'].' nie istnieje'.$sfx; 
							return $msg;
						}
						$DocYear = $Doc->getMonth()->getYear();
						
						$SelDocFile_Name = '';
						if(array_key_exists('SelDocFile', $B['SUM'][0])) {
							$SelDocFile = $B['SUM'][0]['SelDocFile'];
							if($SelDocFile instanceOf File) {
								$SelDocFile_Name = $SelDocFile->getName();
							}
						}
						
							
						$GroupFile_Name = '';	
						if(array_key_exists('GroupFile', $B['SUM'][0])) {
							$GroupFile = $B['SUM'][0]['GroupFile'];
								if($GroupFile instanceOf File) {
									$GroupFile_Name = $GroupFile->getName();}
						}

						$ID_desc = '';	
						if(array_key_exists('ID_desc', $B['SUM'][0])) {
							$ID_desc = $B['SUM'][0]['ID_desc'];
						}

						$GroupDoc_Desc = '';	
						if(array_key_exists('GroupDoc_Desc', $B['SUM'][0])) {
							$GroupDoc_Desc = $B['SUM'][0]['GroupDoc_Desc'];}
																
						$desc = $TmpB['desc'];
						$desc = str_replace('__Doc_desc__',        $Doc->getDesc(), $desc);
						$desc = str_replace('__Doc_no__',          $Doc->getDocNo(), $desc);
						$desc = str_replace('__SelDocFile_Name__', $SelDocFile_Name, $desc);
						$desc = str_replace('__SelDoc_No__',       $SelDoc_No, $desc);
						$desc = str_replace('__GroupFile_Name__',  $GroupFile_Name, $desc);
						$desc = str_replace('__GroupDoc_Desc__',   $GroupDoc_Desc, $desc);
						$desc = str_replace('__YY__',              $payment_date->format('y'), $desc);
						$desc = str_replace('__MM__',              $payment_date->format('m'), $desc);
						$desc = str_replace('__Project_Name__',    $Project_Name, $desc);
						$desc = str_replace('__ID_desc__',         $ID_desc, $desc);
						
						$Bookk = new Bookk();	
						$Bookk->setDoc($Doc);
						$Bookk->setDesc($desc);						
						$Bookk->setProject($Project);
						
						if($payment_date != null) {
							$bookking_date = $payment_date; }
						else {
							$bookking_date = $Doc->getBookkingDate();
							if($bookking_date == null) {
								$msg['errors'][] = 'Dokument '.$TmpB['Doc'].' nie posiada daty księgowania';}
						}
						$Bookk->setBookkingDate($bookking_date);
						
						foreach($TmpB['BEs'] as $TmpBEid => $TmpBE) {
							if(!array_key_exists($TmpBE['cols'],$B)) {
								$msg['errors'][] = 'Metoda '.$TmpBE['cols'].' jest niedostępna'.$sfx; 
								return $msg;}
							
							foreach($B[$TmpBE['cols']] as $BEid => $BE) {	
								if(($TmpBE['cols'] == 'SUM' && $BEid == 0) || 
								   ($TmpBE['cols'] != 'SUM' && $BEid > 0)) {
									
									$sfx2 = $sfx.' |TmpBEi'.$TmpBEid.'|BE_id'.$BEid;
									
									//side
									$side = $TmpBE['side'];
									//value
									$Data2['value'] = array();
									foreach(array('gross','netto','tax') as $value) {
										if(array_key_exists($value, $BE)) {
											$Data2['value'][$value] = $BE[$value];}}
									if(!array_key_exists($TmpBE['value'], $Data2['value'])) {
										$msg['errors'][] = 'Wartość '.$TmpBE['value'].' jest niedostępna'.$sfx2; 
										return $msg;
									}	
									$value = $Data2['value'][$TmpBE['value']];
									
									// Account
									$Data2['Account'] = array();
 									if(array_key_exists('GroupAcc', $B['SUM'][0])) {
										$Data2['Account']['GroupAcc'] = $B['SUM'][0]['GroupAcc'];
									}							
									
									// CommitAcc & TaxCommitAcc is searched in year of Doc
									if(array_key_exists('CommitAcc', $B['SUM'][0])) {
										$CommitAcc = $B['SUM'][0]['CommitAcc'];
										if(($CommitAcc instanceOf Account) && 
										   ($CommitAcc->getYear() != $DocYear)) {		
											$CommitAcc = AccountQuery::create()
												->filterByYear($DocYear )
												->filterByAccNo($CommitAcc->getAccNo())
												->findOne();
										}
										$Data2['Account']['CommitAcc'] = $CommitAcc;
									}	

									if(array_key_exists('TaxCommitAcc', $B['SUM'][0])) {
										$TaxCommitAcc = $B['SUM'][0]['TaxCommitAcc'];
										if(($TaxCommitAcc instanceOf Account) && 
										   ($TaxCommitAcc->getYear() != $DocYear)) {		
											$TaxCommitAcc = AccountQuery::create()
												->filterByYear($DocYear )
												->filterByAccNo($TaxCommitAcc->getAccNo())
												->findOne();
										}
										$Data2['Account']['TaxCommitAcc'] = $TaxCommitAcc;
									}	
									
									if(array_key_exists('IncomeBankAcc',$BE)) {
										$Data2['Account']['IncomeBankAcc'] = $BE['IncomeBankAcc'];
									}
									
									$Data2['Account']['IncomeAcc'] = $Project->getIncomeAcc();
									$Data2['Account']['CostAcc'] = $Project->getCostAcc();
									
									if(substr($TmpBE['Account'],0,1) == '#') {
										$Account = AccountQuery::create()
											->filterByYear($DocYear)
											->findOneByAccNo(substr($TmpBE['Account'],1));										
									}
									elseif(array_key_exists($TmpBE['Account'], $Data2['Account'])) {
										$Account = $Data2['Account'][$TmpBE['Account']]; }
									else {
										$msg['errors'][] = 'Konto '.$TmpBE['Account'].' jest niedostępne'.$sfx2; 
										return $msg;
									}																	
									
									if(!($Account instanceOf Account)) {
										$msg['errors'][] = 'Konto '.$TmpBE['Account'].' nie istnieje'.$sfx2; 
										return $msg;
									} 	
									
									if($Account->getYear() != $DocYear) {
										$msg['errors'][] = 'Różny Rok Konta '.$TmpBE['Account'].' i Dokumentu'.$sfx2; 
										return $msg;
									}									
									//Files	
									$Data2['File'] = array();
										$Data2['File']['ProjectFile'] = $ProjectFile;																			
 									if(array_key_exists('GroupFile', $B['SUM'][0])) {
										$Data2['File']['GroupFile'] = $B['SUM'][0]['GroupFile'];}	
 									if(array_key_exists('SelDocFile', $B['SUM'][0])) {
										$Data2['File']['SelDocFile'] = $B['SUM'][0]['SelDocFile'];}
 									if(array_key_exists('IncomeFile',$BE)) {
										$Data2['File']['IncomeFile'] = $BE['IncomeFile'];}									
																											
									for($lev = 1; $lev <=3; $lev++ ){
										if(!array_key_exists('FileLev'.$lev,$TmpBE) || $TmpBE['FileLev'.$lev] == null) {
											$FileLev[$lev] = null;} 
										elseif(array_key_exists($TmpBE['FileLev'.$lev], $Data2['File'])) {
											$FileLev[$lev] = $Data2['File'][$TmpBE['FileLev'.$lev]]; 
											
											if(!($FileLev[$lev] instanceOf File)) {
												$msg['errors'][] = 'Kartoteka '.$TmpBE['FileLev'.$lev].' nie istnieje'.$sfx2; 
												return $msg;
											}
											elseif($FileLev[$lev]->getFileCat()->getYear() != $Project_Year) {
												
												$oldFileCat_symbol = $FileLev[$lev]->getFileCat()->getSymbol();
												$oldAccNo = $FileLev[$lev]->getAccNo();
												$newFile = FileQuery::create()
													->useFileCatQuery()
														->filterByYear($Project_Year)
														->filterBySymbol($oldFileCat_symbol)
													->endUse()
													->findOneByAccNo( $oldAccNo );
												if(!($newFile instanceOf File)) {
													$msg['errors'][] = 'Brak Kartoteki '.$TmpBE['FileLev'.$lev].'z roku Dokumentu w roku Projektu'.$sfx2; 
													return $msg;	
												} else {
													 $FileLev[$lev] = $newFile;
												}							
											} 
										}	
										else { $msg['errors'][] = 'Kartoteka '.$TmpBE['FileLev'.$lev].' jest niedostępna'.$sfx2;  
											return $msg;
										}
									}

									//BookkEntry
									if(empty($msg['errors'])) {
										$BookkEntry = new BookkEntry();
										$BookkEntry->setBE($Bookk, $side, $value, $Account, $FileLev);										
										$Bookk->addBookkEntry($BookkEntry);}
								}							   			   	   						
							}
						}
						$BookksToSave[] = $Bookk;
					}
				}
			}
		}
		
		if(empty($msg['errors'])) {
			foreach($BookksToSave as $BookkToSave) {
				$BookkToSave->save();}
		}		
		
		return $msg;
	}


    public function updateBookkingDateAction($year_id, Request $request) {
		$Year = YearQuery::create()->findPk($year_id);
		$Bookks = BookkQuery::create()
			->useDocQuery()
				->useMonthQuery()
					//->filterByYear($Year)
				->endUse()
			->endUse()
			->find();
			
		$i = 0;
		foreach($Bookks as $Bookk) {
			$i++;
			$bookking_date = $Bookk->getDoc()->getBookkingDate();
			if($bookking_date == null) {
				$bookking_date = $Bookk->getDoc()->getDocumentDate(); }
			
			$Bookk->setBookkingDate($bookking_date)->save();}
						
		return $this->render('AppBundle:Template:raw.html.twig',array(
				'contents' => 'UpdateBookkingDateAction >> '.$i.' bookks updated'));
	}  

    public function clearBookksAction(Request $request) {
		$Bookks = BookkQuery::create()->find();
			
		$i = 0;
		foreach($Bookks as $Bookk) {
			if($Bookk->getDoc() == null) {
				$i++;
				$Bookk->delete();}
		}
						
		return $this->render('AppBundle:Template:raw.html.twig',array(
				'contents' => 'clearBookksAction >> '.$i.' bookks w/o doc deleted'));
	}  

	
}
