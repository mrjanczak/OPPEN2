<?php

namespace AppBundle\Model;

class FileList {
	
	public $Year;
	public $FileCat;
	public $name;	
	public $page;	
	
	public $as_file_select;
	public $Files;	
	
	public function __construct ($Year, $FileCat, $name, $page, 
								 $as_file_select, $Files)
	{
		$this->Year = $Year;		
		$this->FileCat = $FileCat;
		$this->name = $name;
		$this->page = $page;
		$this->as_file_select = $as_file_select;
		$this->Files = $Files;
	}
	

}
