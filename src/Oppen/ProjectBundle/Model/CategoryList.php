<?php

namespace Oppen\ProjectBundle\Model;

class CategoryList {
	
	public $Year;
	
	public $FileCats;	
	
	public $DocCats;
	
	public function __construct ($Year, $FileCats, $DocCats)
	{
		$this->Year     = $Year;		
		$this->FileCats = $FileCats;
		$this->DocCats  = $DocCats;
	}
	

}
