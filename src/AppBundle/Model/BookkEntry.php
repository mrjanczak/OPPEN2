<?php

namespace AppBundle\Model;

use AppBundle\Model\om\BaseBookkEntry;
use AppBundle\Model\Account;
use AppBundle\Model\File;
use AppBundle\Model\Bookk;

class BookkEntry extends BaseBookkEntry
{
    public $account_id;
    public $file_lev1_id;
    public $file_lev2_id;
    public $file_lev3_id;


	
	public function createAccNo($Account, $FileLev)
	{	
		$AccNo = '';
		if ($Account instanceOf Account) {
			$AccNo .= $Account->getAccNo(); }
			
		if($FileLev[1] instanceOf File) {
			$AccNo .= '-'.substr('00'.$FileLev[1]->getAccNo(),-3);}
		if($FileLev[2] instanceOf File) {
			$AccNo .= '-'.substr('00'.$FileLev[2]->getAccNo(),-3);}			
		if($FileLev[3] instanceOf File) {
			$AccNo .= '-'.substr('00'.$FileLev[3]->getAccNo(),-3);}	
					
		return $AccNo;		
	}
		
	public function prepareToSave()
	{	
		$Account = $this->getAccount();
		$FileLev = array();
		$FileLev[1] = $this->getFileLev1();		
		$FileLev[2] = $this->getFileLev2();		
		$FileLev[3] = $this->getFileLev3();
		
		$this->setAccNo($this->createAccNo($Account, $FileLev));
		
		return $this;			
	}	
	
	public function setBE(Bookk $Bookk, $side, $value, $Account, $FileLev)
	{
		$this->setBookk($Bookk);
		$this->setSide($side);
		$this->setValue($value);
		$this->setAccount($Account);
		$this->setFileLev1($FileLev[1]);		
		$this->setFileLev2($FileLev[2]);		
		$this->setFileLev3($FileLev[3]);		
		$this->setAccNo($this->createAccNo($Account, $FileLev));		
	}	
}
