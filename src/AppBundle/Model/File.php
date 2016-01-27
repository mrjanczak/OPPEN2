<?php

namespace AppBundle\Model;

use AppBundle\Model\om\BaseFile;

class File extends BaseFile
{
    public function __AccNo( $d = 3 )
    {		
        if(in_array($this->getFileCat()->getSymbol(), array('US'))) {
			$d = 4;
		}
		
        return (string) substr(str_repeat('0',$d).$this->getAccNo(),-$d);
    }
 
    public function __toString()
    {	
		$FCsym = $this->getFileCat()->getSymbol();
        return (string) $FCsym.'|'.$this->__AccNo().' - '.$this->getName();
    }	

    public function getPfxStreet()
    {	
		$street = $this->getStreet();
		return (string) (in_array(strtolower(substr($street,0,3)),array('ul.','al.','pl.','os.'))?'':'ul.').$street; 
    }

    public function getPfxFlat()
    {    
		$flat = $this->getFlat();
		return ($flat == NULL || $flat == '' )?'':', m.'; 
	}
	
    public function getAddress1()
    {    
		return $this->getPfxStreet().' '.$this->getHouse().$this->getPfxFlat().$this->getFlat();
	}	

    public function getAddress2()
    {    
		return $this->getCode().' '.$this->getCity();
	}
}
