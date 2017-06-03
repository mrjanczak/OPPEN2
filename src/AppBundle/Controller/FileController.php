<?php

namespace AppBundle\Controller;

use \PropelObjectCollection;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Controller\ContractController;

use AppBundle\Model\Month;
use AppBundle\Model\File;
use AppBundle\Model\FileCat;
use AppBundle\Model\FileList;

use AppBundle\Model\ProjectQuery;
use AppBundle\Model\IncomeQuery;
use AppBundle\Model\CostQuery;
use AppBundle\Model\ContractQuery;
use AppBundle\Model\DocQuery;
use AppBundle\Model\FileQuery;
use AppBundle\Model\FileCatQuery;
use AppBundle\Model\YearQuery;
use AppBundle\Model\BookkEntryQuery;
use AppBundle\Model\ParameterQuery;

use AppBundle\Form\Type\FileListType;
use AppBundle\Form\Type\FileType;

class FileController extends Controller
{  
	
	public function listAction($file_cat_id, $return, $id1, $id2, $id3, Request $request)
	{
		$Params = ParameterQuery::create()->getAll();		
		$errors = array();
		
		if ($request->isMethod('POST')) {
			$FileListR = $request->request->get('file_list');
			$Year = YearQuery::create()->findPk($FileListR['Year']);
			$FileCat = FileCatQuery::create()->findPk($FileListR['FileCat']);				
			$name = $FileListR['name'];
			$page = $FileListR['page'];
		}
		else { 
			$FileCat = FileCatQuery::create()->findPk($file_cat_id);
			$Year  = $FileCat->getYear(); 			
			$name = '*';
			$page = 1;			
		}

		if($return == 'project') {
			$as_file_select = true;
		} else {$as_file_select = false;}
		
		$FileList = $this->newFileList($Year, $FileCat, $name, $page, $as_file_select);								
		$form = $this->createForm(new FileListType($Year), $FileList);	
		
		$buttons = array('cancel');
		$redirect = false;
		
		// selection of ZB for massive contract generation
		if ($request->isMethod('POST')) {
			if(array_key_exists('selectFiles',$FileListR) && array_key_exists('Files',$FileListR)) {					
				foreach($FileListR['Files'] as $FileR) {
					if(array_key_exists('select',$FileR)) {	
						$File = FileQuery::create()->findPk($FileR['id']);
						$Contract = ContractQuery::create()->findPk($id3);
						$Month = $Contract->getMonth();
						$Contract = $Contract->copy();
						$Contract->setFile($File);
						$Contract->setEventRole($File->getProfession());
						
						$ContractDate =  $Contract->getContractDate();
						$NAME = substr($File->getFirstName(),0,3).substr($File->getLastName(),0,3); 
						$Contract->setContractNo('UoD '.$ContractDate->format('Y-m-d').'/'.$NAME);
						/*
						if($Month instanceOf Month) {
							$contract_no = ContractQuery::create()->filterByMonth($Month)->count() + 1;
							$Contract->setContractNo('UoD '.$Year->getName().'/'.$Month->getName().'/'.$contract_no);
						} else { $Contract->setContractNo(''); }	
						*/			
						$Contract->save(); 
					}
				}						
			}
			if(array_key_exists('cancel',$FileListR)) {}
			$redirect = true; 
		}
	
		if ($redirect) {
			switch ($return) {
				case 'files' : 
					return $this->redirect($this->generateUrl('oppen_files', array(
						'file_cat_id'   => $FileCat->getId(),
						'return' => $return,
						'id1' => $id1,
						'id2' => $id2, 
						'id3' => $id3 ))); 
						
				case 'project' : 
					return $this->redirect($this->generateUrl('oppen_project', array(
					'project_id'   => $id1,
					'tab_id' => $id2,
					'year_id' => $Year->getId()) )); 			
			}
		}

        return $this->render('AppBundle:File:list.html.twig',array(
			'Year' => $Year,
			'FileCat' => $FileCat,
			'FileList' => $FileList,
			'form' => $form->createView(),
			'buttons' => $buttons,
			'errors' => $errors,
			'Params' => $Params,
			'return' => $return,
			'subtitle' => $FileCat->getName(), ));
    }	

    public function updateFilterAction($year_id)
    {
		$Year = YearQuery::create()->findPk($year_id);
	 
		$file_cats = array();
		foreach ($Year->getFileCats() as $FileCat) {
			$file_cats[] = '<option value="'.$FileCat->getId().'">'.$FileCat->getName().'</option>'; }
				
		$response = new JsonResponse();
		$response->setData(array( $file_cats));
		return $response;
	}
	
    public function editAction($file_cat_id, $file_id, Request $request)
    {
		$FileCat = FileCatQuery::create()->findPk($file_cat_id);
		$Year = $FileCat->getYear(); 
		$buttons = array('cancel','save');		
		$errors = array();
				
		if($file_id == 0) {
			$File = new File();
			$LastFile = FileQuery::create()->orderByAccNo('desc')->findOneByFileCat($FileCat);
			if(!$LastFile) { $AccNo = 1;}
			else { $AccNo = $LastFile->getAccNo() + 1;}
			
			$File->setFileCat($FileCat);
			$File->setAccNo($AccNo);
			$File->setCountry("PL");
			
		} else {	
			$File = FileQuery::create()->findPk($file_id); 
			$buttons[] = 'delete';			
		}
		
 		$form = $this->createForm(new FileType($FileCat->getSubFileCat(), true), $File);	
        $form->handleRequest($request); 
		
		if ($form->get('cancel')->isClicked()) {
			return $this->redirect($this->generateUrl('oppen_files', 
				array('file_cat_id' => $file_cat_id)));
		}			
		if ($form->get('save')->isClicked()) {
			//if ($form->isValid()) {
				
				switch ($FileCat->getSymbol()) {
					case 'ZB':				
						$File->setName($File->getFirstName().' '.$File->getLastName());
					break;
				}
		
				$File->save();
				return $this->redirect($this->generateUrl('oppen_files',array(
					'file_cat_id' => $FileCat->getId()) ));
			//}
		}
		if ($form->get('delete')->isClicked()) {
	
				$err = 'Nie można usunąć kartoteki - jest użyta ';
				$count  = ProjectQuery::create()->filterByFile($File)->count();
				if($count > 0){$errors[] = $err.'w projekcie';}
				
				$count = IncomeQuery::create()->filterByFile($File)->count();
				$count += CostQuery::create()->filterByFile($File)->count();
				if($count > 0){$errors[] = $err.$count.' razy w przychodach lub kosztach projektów';}

				$count += ContractQuery::create()->filterByFile($File)->count();
				if($count > 0){$errors[] = $err.$count.' razy w umowach';}

				$count = FileQuery::create()->filterBySubFile($File)->count();
				if($count > 0){$errors[] = $err.$count.' razy w innych kartotekach';}

				$count = DocQuery::create()->filterByFile($File)->count();
				if($count > 0){$errors[] = $err.$count.' razy w dokumentach';}

				$count = BookkEntryQuery::create()->filterByFileLev1($File)->count();
				$count += BookkEntryQuery::create()->filterByFileLev2($File)->count();
				$count += BookkEntryQuery::create()->filterByFileLev3($File)->count();
				if($count > 0){$errors[] = $err.$count.' razy w dekretacjach';}

			if(count($errors) == 0) {			
				$File->delete();
				return $this->redirect($this->generateUrl('oppen_files',array(
					'file_cat_id' => $FileCat->getId()) ));
			}
		}
 		
		return $this->render('AppBundle:File:edit.html.twig',array(
			'Year' => $Year,
			'buttons' => $buttons,
			'errors' => $errors,
			'FileCat' => $FileCat,
			'form' => $form->createView() )); 					
	}

	static public function newFileList($Year, $FileCat, $name, $page, $as_file_select) {
		$Files = new PropelObjectCollection();
		$FilesC = FileQuery::create()->orderByName();
		if($FileCat instanceOf FileCat) {
			$FilesC->filterByFileCat($FileCat); }
		if($name != '') {	
			$FilesC->filterByName($name); }	
		$Files = $FilesC->find();

		return new FileList($Year, $FileCat, $name,	$page, $as_file_select, $Files);
	}	
}
