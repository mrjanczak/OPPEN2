<?php

namespace AppBundle\Model;

class ProjectList {

	public $Year;
	
	public $search_name;

	public function __construct ($Year, $search_name)
	{
		$this->Year = $Year;
		$this->search_name = $search_name;		
	}
}
