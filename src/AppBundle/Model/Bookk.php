<?php

namespace AppBundle\Model;

use \PropelPDO;
use \PropelObjectCollection;
use AppBundle\Model\om\BaseBookk;

class Bookk extends BaseBookk
{
	protected $BookkEntries;
	
	protected $SortedBookkEntries;

    public function __construct()
    {
        $this->BookkEntries = new PropelObjectCollection();        
    }	
	
	// Optional
    public function getSortedBookkEntries($criteria = null, PropelPDO $con = null)
    {	
		$query = BookkEntryQuery::create(null, $criteria)
                    ->filterByBookk($this)
                    ->orderById();
	
        return $this->getBookkEntries($query, $con);
    }

	public function setNewNo() {
		$No = BookkQuery::create()
			->filterByDoc($this->getDoc())
			->filterByIsAccepted(1)
			->count(); //this Bookk is already included
		return $this->setNo($No);
	}
}
