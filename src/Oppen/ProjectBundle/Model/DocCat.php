<?php

namespace Oppen\ProjectBundle\Model;

use Oppen\ProjectBundle\Model\om\BaseDocCat;

class DocCat extends BaseDocCat
{
	public $select;
	public $select2;

	public function __construct () {		
		$this->select = true;
		$this->select2 = true;
	}	
}
