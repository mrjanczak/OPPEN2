<?php

namespace AppBundle\Model;

use \PropelPDO;
use AppBundle\Model\om\BaseCost;

class Cost extends BaseCost
{
	public $SortedCostIncomes;
	
	public $SortedCostDocs;
	
	public $SortedContracts;

    public function getSortedCostIncomes($criteria = null, PropelPDO $con = null)
    {
        $query = CostIncomeQuery::create(null, $criteria)
					->useIncomeQuery()
						->orderBySortableRank()
					->endUse()                
                    ->filterByCost($this);

        return $this->getCostIncomes($query, $con);
    } 

    public function getSortedCostDocs($criteria = null, PropelPDO $con = null)
    {
        $query = CostDocQuery::create(null, $criteria)
					->useDocQuery()
						->useMonthQuery()	
							->orderBySortableRank()
						->endUse()	
						->useDocCatQuery()
							->orderBySymbol()
						->endUse()
						->orderByDocIdx()
					->endUse()
                    ->filterByCost($this);

        return $this->getCostDocs($query, $con);
    } 	

    public function getSortedContracts($criteria = null, PropelPDO $con = null)
    {
        $query = ContractQuery::create(null, $criteria)
					->useMonthQuery()	
						->orderBySortableRank()
					->endUse()
					->useFileQuery()
						->orderByName()
					->endUse()                 
                    ->filterByCost($this);

        return $this->getContracts($query, $con);
    } 
 
}
