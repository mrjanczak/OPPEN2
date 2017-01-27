<?php

namespace AppBundle\Model;

use \PropelPDO;
use AppBundle\Model\om\BaseFileCat;

class FileCat extends BaseFileCat
{
	public $select;
	public $select2;

	public function __construct () {		
		$this->select = true;
		$this->select2 = true;
	}
	public function getNextAccNo() {
		return FileQuery::create()->filterByFileCat($this)->count() + 1;
	}

	public function getFilesByAccNo()
	{
		return FileQuery::create()
					->orderByAccNo('asc')
                    ->filterByFileCat($this)
                    ->find();
	}
	
	// Optional
    public function getSortedFiles($criteria = null, PropelPDO $con = null)
    {	
		$query = FileQuery::create(null, $criteria)
					->orderByName('asc')
                    ->filterByFileCat($this);
	
        return $this->getFiles($query, $con);
    }
}
