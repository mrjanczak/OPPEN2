<?php

namespace Oppen\ProjectBundle\Model;

class MonthList {
	
	public $Year;
	
	public $Months;	
	
	public function __construct ($Year, $Months)
	{
		$this->Year = $Year;		
		$this->Months = $Months;
	}
	

}
