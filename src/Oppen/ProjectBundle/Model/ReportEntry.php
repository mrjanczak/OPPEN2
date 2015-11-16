<?php

namespace Oppen\ProjectBundle\Model;

use Oppen\ProjectBundle\Model\om\BaseReportEntry;

class ReportEntry extends BaseReportEntry
{
	public $value;
	
	public $Parent;
	
    /**
     * return the string representation of this object
     *
     * @return string The value of the 'name' column
     */
    public function __toString()
    {	
        return (string) str_repeat('_', abs($this->getLevel())).$this->getNo().' '.substr($this->getName(),0,50);
    }	
}
