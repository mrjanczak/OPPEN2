<?php

namespace AppBundle\Model;

use \PropelPDO;
use AppBundle\Model\om\BaseYear;

class Year extends BaseYear
{
	public $SortedMonths;
	
    public function getSortedMonths($criteria = null, PropelPDO $con = null)
    {	
		$query = MonthQuery::create(null, $criteria)
                    ->filterByYear($this)
                    ->orderBySortableRank();
	
        return $this->getMonths($query, $con);
    }
}
