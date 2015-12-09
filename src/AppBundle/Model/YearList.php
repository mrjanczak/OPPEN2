<?php

namespace AppBundle\Model;

class YearList {
	
	public $Years;	
	public $FileCats;
	public $DocCats;	
	
	public $copy_accounts;
	public $copy_reports;
	
	public function __construct ( $Years, $FileCats, $DocCats)
	{		
		$this->Years = $Years;
	    $this->FileCats = $FileCats;
	    $this->DocCats = $DocCats;		
	    
	    $this->copy_accounts = true;
	    $this->copy_reports = true;

	}
	

}
