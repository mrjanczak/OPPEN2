<?php

namespace Oppen\ProjectBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Oppen\ProjectBundle\Model\Year;
use Oppen\ProjectBundle\Model\Month;
use Oppen\ProjectBundle\Model\Project;

use Oppen\ProjectBundle\Model\YearQuery;
use Oppen\ProjectBundle\Model\MonthQuery;
use Oppen\ProjectBundle\Model\ProjectQuery;
use Oppen\ProjectBundle\Model\DocCatQuery;

use Oppen\ProjectBundle\Form\Type\YearType;

use Oppen\ProjectBundle\Form\Type\YearsFilterType;

class MonthController extends Controller
{  
    public function updateAction($month_id) {
		$Month = MonthQuery::create()->findPk($month_id);

		$dates = array();
				
		$response = new JsonResponse();
		$response->setData(array($Month->getFromDate(),$Month->getToDate()));
		return $response;
	}
	
    public function updateDocListAction($month_id, $project_id) {
		$Project = ProjectQuery::create()->findPk($project_id);
		$Month = MonthQuery::create()->findPk($month_id);
		if($Month instanceOf Month) {
			$Year = $Month->getYear(); }

		$DocCats = DocCatQuery::create()
					->_if($Project instanceOf Project)
						->useDocQuery()
							->useBookkQuery()
								->filterByProject($Project)
							->endUse()
						->endUse()
					->_elseif($Year instanceOf Year)
						->filterByYear($Year)
					->_endif()
					
					->orderById() 
					->find();
		 
		$doc_cats = array('<option value>Wszystkie</option>');
		foreach ($Year->getDocCats() as $DocCat) {
			$doc_cats[] = '<option value="'.$DocCat->getId().'">'.$DocCat->getName().'</option>'; }
				
		$response = new JsonResponse();
		$response->setData(array($doc_cats));
		return $response;
	}	
}
