<?php

namespace AppBundle\Model;

use AppBundle\Model\om\BaseMonthQuery;

class MonthQuery extends BaseMonthQuery
{
	public static function getFirstActive($Year) {
		$Month = MonthQuery::create()->filterByYear($Year)->orderByFromDate('desc')->findOneByIsActive(1);    
		if (!($Month instanceOf Month)) {
			$Month = MonthQuery::create()->filterByYear($Year)->orderByFromDate('desc')->findOne();	}
		return $Month;	
	}
}
