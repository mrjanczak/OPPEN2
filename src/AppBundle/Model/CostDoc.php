<?php

namespace AppBundle\Model;

use \PropelPDO;
use AppBundle\Model\om\BaseCostDoc;

class CostDoc extends BaseCostDoc
{
	public $SortedCostDocIncomes;
	
    public function getSortedCostDocIncomes($criteria = null, PropelPDO $con = null)
    {
        $query = CostDocIncomeQuery::create(null, $criteria)
					->useIncomeQuery()
						->orderBySortableRank()
					->endUse()
                    ->filterByCostDoc($this);

        return $this->getCostDocIncomes($query, $con);
    } 		

}
