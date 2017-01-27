<?php

namespace AppBundle\Model;

use \PropelPDO;
use AppBundle\Model\om\BaseReport;

class Report extends BaseReport
{
	public $data;
	
	public $Items;
	public $ItemColls;
	
	// Optional
    public function getSortedReportEntries($criteria = null, PropelPDO $con = null)
    {	
		$query = ReportEntryQuery::create(null, $criteria)
                    ->filterByReport($this)
                    ->orderByTreeLeft();
	
        return $this->getReportEntries($query, $con);
    }
}
