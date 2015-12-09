<?php

namespace AppBundle\Model;

use AppBundle\Model\om\BaseMonth;

class Month extends BaseMonth
{
    public function __toString()
    {	
		$Year = $this->getYear();
        return (string) $Year->getName().'/'.$this->getName();
    }
	
}
