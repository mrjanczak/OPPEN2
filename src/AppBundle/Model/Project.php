<?php

namespace AppBundle\Model;

use \PropelPDO;
use \Criteria;
use AppBundle\Model\om\BaseProject;


class Project extends BaseProject
{
    public $DocList;
   
    public $SortedIncomes;
   
    public $SortedCosts;
    
    public $SortedTasks;
    
    public function getSortedIncomes($criteria = null, PropelPDO $con = null)
    {
        $query = IncomeQuery::create(null, $criteria)
                    ->filterByProject($this)
                    ->orderBySortableRank();

        return $this->getIncomes($query, $con);
    }   
    

    public function getSortedCosts($criteria = null, PropelPDO $con = null)
    {
        $query = CostQuery::create(null, $criteria)
                    ->filterByProject($this)
                    ->orderBySortableRank();

        return $this->getCosts($query, $con);
    } 


    public function getSortedTasks($criteria = null, PropelPDO $con = null)
    {
        $query = TaskQuery::create(null, $criteria)
                    ->filterByProject($this)
                    ->orderBySortableRank();

        return $this->getTaskss($query, $con);
    } 	
}
