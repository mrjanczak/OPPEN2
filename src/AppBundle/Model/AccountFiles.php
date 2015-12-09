<?php

namespace AppBundle\Model;

class AccountFiles 
{
	public $Account;
	public $FileLev1;
	public $FileLev2;
	public $FileLev3;
	
	public function __construct ( $Account = null, $FileLev1 = null, $FileLev2 = null, $FileLev3 = null)
	{
		$this->Account = $Account;		
		$this->FileLev1 = $FileLev1;		
		$this->FileLev2 = $FileLev2;		
		$this->FileLev3 = $FileLev3;		
	}
}
