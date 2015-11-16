<?php

namespace Oppen\ProjectBundle\Model;

use Oppen\ProjectBundle\Model\om\BaseParameterQuery;

class ParameterQuery extends BaseParameterQuery
{
	public function getOneByName($name) {
		$P = $this::create()->findOneByName($name);
		eval('$Param = $P->getValue'.ucfirst($P->getFieldType()).'();');	
		if($Param == '_') {
			$Param = ' ';}
		return $Param;
	}	

	public function getAll() {
		$Parameters = $this::create()->find();
        $Params = array();
        foreach($Parameters as $P) {
			eval('$Param = $P->getValue'.ucfirst($P->getFieldType()).'();');	
			if($Param == '_') {
				$Param = ' ';}			
			$Params[$P->getName()] = $Param; }
		return $Params;
	}	
}
