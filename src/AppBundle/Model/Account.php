<?php

namespace AppBundle\Model;

use AppBundle\Model\om\BaseAccount;

class Account extends BaseAccount
{
	public $Parent;

    public function __toString()
    {	
		$max_len = 40;
		if(strlen($this->getName()) > $max_len) {$sfx = '...';} 
		else {$sfx='';}
        return (string) $this->getAccNo().' | '.substr($this->getName(),0,$max_len).$sfx ;
    }	
	
	public function getFileCatLev($lev) 
	{
		switch ($lev) {
			case 1 : 
				return $this->getFileCatLev1(); 
				break;
			case 2 : 
				return $this->getFileCatLev2(); 
				break;
			case 3 : 
				return $this->getFileCatLev3(); 
				break;
		}
	}
	
	public function setFileCats($FileCats)
	{
		$this->setFileCatLev1($FileCats[1]);		
		$this->setFileCatLev2($FileCats[2]);		
		$this->setFileCatLev3($FileCats[3]);
		
        return $this;	
	}		
}
