<?php

namespace Oppen\ProjectBundle\Model;

use Oppen\ProjectBundle\Model\om\BaseYearQuery;

class YearQuery extends BaseYearQuery
{
	public static function getFirstActive() {
		$Year = YearQuery::create()->orderByFromDate('asc')->findOneByIsActive(1);    
		if (!($Year instanceOf Year)) {
			$Year = YearQuery::create()->orderByFromDate('asc')->findOne();	}
		return $Year;	
	}
}
