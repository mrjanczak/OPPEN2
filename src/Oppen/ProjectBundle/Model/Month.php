<?php

namespace Oppen\ProjectBundle\Model;

use Oppen\ProjectBundle\Model\om\BaseMonth;

class Month extends BaseMonth
{
    public function __toString()
    {	
		$Year = $this->getYear();
        return (string) $Year->getName().'/'.$this->getName();
    }
	
}
