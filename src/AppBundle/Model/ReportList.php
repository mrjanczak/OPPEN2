<?php

namespace AppBundle\Model;

class ReportList {
	
	public $Year;
	public $Month;	
	public $FromDate;
	public $ToDate;
	PUBLIC $reportMethod;
	
	public $accNo;
	public $Account;
	public $FileLev1;
	public $FileLev2;
	public $FileLev3;

	
	public $Reports;	
	
	public function __construct ($Year, $Month, $FromDate, $ToDate, $Reports)
	{
		$this->Year = $Year;	
		$this->FromDate = $FromDate;		
		$this->ToDate = $ToDate;
				
		$this->Reports = $Reports;
	}
	

}
