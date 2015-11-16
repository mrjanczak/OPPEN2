<?php

namespace Oppen\ProjectBundle\Model;

use Oppen\ProjectBundle\Model\om\BaseAccount;

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
	
	public function setFileCats($FileCats)
	{
		$this->setFileCatLev1($FileCats[1]);		
		$this->setFileCatLev2($FileCats[2]);		
		$this->setFileCatLev3($FileCats[3]);
        return $this;	
	}		
}
