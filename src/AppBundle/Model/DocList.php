<?php

namespace AppBundle\Model;

class DocList {
	
	public $Year;
	public $Month;
	public $DocCat;
	
	public $showBookks;
	public $desc;	
	public $page;
		
	public $as_doc_select;
	public $as_bookk_accept;
	
	public $Docs;	
	public $DocsPager;

	
	public function __construct ($Year, $Month, $DocCat, $showBookks, $desc, $page,
								 $as_doc_select, $as_bookk_accept,
								 $Docs, $DocsPager)
	{
		$this->Year = $Year;
		$this->Month = $Month;		
		$this->DocCat = $DocCat;
		$this->showBookks = $showBookks;
		$this->desc = $desc;
		$this->page = $page;
				
		$this->as_doc_select = $as_doc_select;
		$this->as_bookk_accept = $as_bookk_accept;
		
		$this->Docs = $Docs;
		$this->DocsPager = $DocsPager;
	}
	

}
