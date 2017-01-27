<?php

namespace AppBundle\Model;

class ReportList {
	
	public $Year;
	public $Month;	
	public $FromDate;
	public $ToDate;
	public $reportMethod;
	
	public $accNo;
	public $Account;
	public $FileLev1;
	public $FileLev2;
	public $FileLev3;

	
	public $Reports;	
	
	public function __construct ($Year, $Month, $FromDate, $ToDate, $reportMethod, $Reports)
	{
		$this->Year = $Year;	
		$this->Month = $Month;	
		$this->FromDate = $FromDate;		
		$this->ToDate = $ToDate;
		$this->reportMethod = $reportMethod;
				
		$this->Reports = $Reports;
	}
	

}
