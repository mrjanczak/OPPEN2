<?php

namespace Oppen\ProjectBundle\Model;

use Oppen\ProjectBundle\Model\om\BaseDoc;
use \PropelObjectCollection;
use \Criteria;

use Oppen\ProjectBundle\Model\ParameterQuery;

class Doc extends BaseDoc
{
    protected $Bookks;

    public function __construct()
    {
        $this->Bookks = new PropelObjectCollection();
    }	
    
	public function setNewDocIdx() {
		$Month = $this->getMonth();
		$Year = $Month->getYear();
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
		
		if($DocIdx === null) {
			$DocIdx = 1;
		} else { 
			$DocIdx += 1;	
		}
		return $this->setDocIdx($DocIdx);
	}

	public function setNewDocNo() {
		$DocIdx = $this->getDocIdx(); 
		$Month = $this->getMonth();
		$Year = $Month->getYear();			
		$DocCat = $this->getDocCat();
		
		$DocNoTmp = $DocCat->getDocNoTmp();
		if(strpos($DocNoTmp,'|') == false ) {
			$DocNoTmp = ParameterQuery::create()->getOneByName('default_doc_no_tmp'); }
		list($type, $tmp) = explode('|',$DocNoTmp);
			 				
		$DCsymbol = $DocCat->getSymbol();
		$M = $Month->getName();
		$Y = $Year->getName();
		$tmp = str_replace('#S',   $DCsymbol,   $tmp);
		$tmp = str_replace('#i',   $DocIdx, $tmp);
		$tmp = str_replace('#M',   $M,     $tmp);
		$tmp = str_replace('#Y',   $Y,     $tmp);
		
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
	
    public function getBookks($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collBookksPartial && !$this->isNew();
        if (null === $this->collBookks || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collBookks) {
                // return empty collection
                $this->initBookks();
            } else {
                $collBookks = BookkQuery::create(null, $criteria)
                    ->filterByDoc($this)
                    ->orderByBookkingDate()
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collBookksPartial && count($collBookks)) {
                      $this->initBookks(false);

                      foreach ($collBookks as $obj) {
                        if (false == $this->collBookks->contains($obj)) {
                          $this->collBookks->append($obj);
                        }
                      }

                      $this->collBookksPartial = true;
                    }

                    $collBookks->getInternalIterator()->rewind();

                    return $collBookks;
                }

                if ($partial && $this->collBookks) {
                    foreach ($this->collBookks as $obj) {
                        if ($obj->isNew()) {
                            $collBookks[] = $obj;
                        }
                    }
                }

                $this->collBookks = $collBookks;
                $this->collBookksPartial = false;
            }
        }

        return $this->collBookks;
    }
	
    
}
