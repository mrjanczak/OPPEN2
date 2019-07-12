<?php

namespace AppBundle\Model;

use \PropelPDO;
use \PropelObjectCollection;
use \Criteria;
use AppBundle\Model\om\BaseDoc;

use AppBundle\Model\ParameterQuery;

class Doc extends BaseDoc
{
    protected $Bookks;
    
    public $SortedBookks;

    public function __construct()
    {
        $this->Bookks = new PropelObjectCollection();
    }	
    
	public function setNewDocIdx($DocIdx = 1) {
		/*
		$Month = $this->getMonth();
		$Year = $Month->getYear();
		$DocCat = $this->getDocCat();
		$DocCat = $this->getDocCat();
		
		$DocNoTmp = $DocCat->getDocNoTmp();
		if(strpos($DocNoTmp,'|') == false ) {
			$DocNoTmp = ParameterQuery::create()->getOneByName('default_doc_no_tmp'); }	
			 	
		list($type, $tmp) = explode('|',$DocNoTmp);
		$DocIdx = DocQuery::create()
			->select('DocIdx')
			->filterByDocCat($DocCat)
			->_if($type == 'byMonth')
				->filterByMonth($Month)
			->_elseif($type == 'byYear')
				->useMonthQuery()
					->filterByYear($Year)
				->enduse()						
			->_endif()
			->filterByDocIdx(null, Criteria::NOT_EQUAL) 
			->orderByDocIdx('desc')
			->findOne();
		*/
		if($DocIdx === null) {
			$DocIdx = 1;
		} else { 
			$DocIdx += 1;	
		}
		return $this->setDocIdx($DocIdx);
	}

	public function setNewDocNo($PROJ = '001',$COST = 1) {
		$DocIdx = $this->getDocIdx(); 
		$Month = $this->getMonth();
		$Year = $Month->getYear();			
		$DocCat = $this->getDocCat();
		$File = $this->getFile();
		
		$DocNoTmp = $DocCat->getDocNoTmp();
		if(strpos($DocNoTmp,'|') == false ) {
			$DocNoTmp = ParameterQuery::create()->getOneByName('default_doc_no_tmp'); }
		list($type, $tmp) = explode('|',$DocNoTmp);
			 				
		$DCsymbol = $DocCat->getSymbol();
		$M = $Month->getName();
		$Y = $Year->getName();
		
		$FirstName = strtr($File->getFirstName(), 'ĘÓĄŚŁŻŹĆŃęóąśłżźćń', 'EOASLZZCNeoaslzzcn');
		$LastName = strtr($File->getLastName(), 'ĘÓĄŚŁŻŹĆŃęóąśłżźćń', 'EOASLZZCNeoaslzzcn');
		$NAME = substr($FirstName,0,3).substr($LastName,0,3);
			
		$tmp = str_replace('#S',   $DCsymbol,   $tmp);
		$tmp = str_replace('#i',   $DocIdx, $tmp);
		$tmp = str_replace('#M',   $M,     $tmp);
		$tmp = str_replace('#Y',   $Y,     $tmp);
		$tmp = str_replace('#PROJ',$PROJ,  $tmp);
		$tmp = str_replace('#COST',$COST,  $tmp);
		$tmp = str_replace('#NAME',$NAME,  $tmp);
		
		return $this->setDocNo($tmp);
	}

	public function setNewRegIdxNo() {
		$Month = $this->getMonth();
		$Year = $Month->getYear();
		$DocCat = $this->getDocCat();
		
		$RegNoTmp = ParameterQuery::create()->getOneByName('default_reg_no_tmp'); 	
		list($type, $tmp) = explode('|',$RegNoTmp);

		$RegIdx = DocQuery::create()
			->select('RegIdx')
			->_if($type == 'byMonth')
				->filterByMonth($Month)
			->_elseif($type == 'byYear')
				->useMonthQuery()
					->filterByYear($Year)
				->enduse()						
			->_endif()				
			->filterByRegIdx(null, Criteria::NOT_EQUAL) 
			->orderByRegIdx('desc')
			->findOne();
			
		if($RegIdx === null) {
			$RegIdx = 1;
		} else { 
			$RegIdx += 1;	
		}			

		$M = $Month->getName();
		$Y = $Year->getName();
		$tmp = str_replace('#i',   $RegIdx, $tmp);
		$tmp = str_replace('#M',   $M,     $tmp);
		$tmp = str_replace('#Y',   $Y,     $tmp);
		
		$this->setRegIdx($RegIdx);
		return $this->setRegNo($tmp);
	}
	
    public function getSortedBookks($criteria = null, PropelPDO $con = null)
    {	
		$query = BookkQuery::create(null, $criteria)
                    ->filterByDoc($this)
                    ->orderByBookkingDate();
	
        return $this->getBookks($query, $con);
    }	
    
}
