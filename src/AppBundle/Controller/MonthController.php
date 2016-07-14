<?php

namespace AppBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Model\Year;
use AppBundle\Model\Month;
use AppBundle\Model\Project;

use AppBundle\Model\YearQuery;
use AppBundle\Model\MonthQuery;
use AppBundle\Model\ProjectQuery;
use AppBundle\Model\DocCatQuery;

use AppBundle\Form\Type\YearType;

use AppBundle\Form\Type\YearsFilterType;

class MonthController extends Controller
{  
    public function updateAction($month_id) {
		$Month = MonthQuery::create()->findPk($month_id);

		$dates = array();
				
		$response = new JsonResponse();
		$response->setData(array($Month->getFromDate(),$Month->getToDate()));
		return $response;
	}
	
    public function updateDocListAction($month_id, $project_id, $as_income_docs, $as_cost_docs) {
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
					
					->_if($as_income_docs == 1) 
						->filterByAsIncome(1)	
					->_elseif($as_cost_docs == 1) 
						->filterByAsCost(1)
					->_endif()
					
					->groupById() 										
					->orderById() 
					->find();
		 
		$doc_cats = array('<option value>Wszystkie</option>');
		foreach ($DocCats as $DocCat) {
			$doc_cats[] = '<option value="'.$DocCat->getId().'">'.$DocCat->getName().'</option>'; }
				
		$response = new JsonResponse();
		$response->setData(array($doc_cats));
		return $response;
	}	
}
