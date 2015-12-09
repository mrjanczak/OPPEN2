<?php

namespace AppBundle\Model;

class AccountList {
	
	public $Year;
	
	public $Accounts;	
	
	public function __construct ($Year, $Accounts)
	{
		$this->Year = $Year;		
		$this->Accounts = $Accounts;
	}
	

}
