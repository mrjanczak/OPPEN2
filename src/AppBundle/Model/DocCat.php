<?php

namespace AppBundle\Model;

use AppBundle\Model\om\BaseDocCat;

class DocCat extends BaseDocCat
{
	public $select;
	public $select2;

	public function __construct () {		
		$this->select = true;
		$this->select2 = true;
	}	
}
