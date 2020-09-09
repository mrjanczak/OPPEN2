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
        return (string) $FCsym.'|'.$this->__AccNo().' - '.substr($this->getName(),0,35);
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
	
	
	# Global flag by request
	$__disable_iiban_gmp_extension=false;

	public function iban_checksum_string_replace($s) {
	 $iban_replace_chars = range('A','Z');
	 foreach (range(10,35) as $tempvalue) { $iban_replace_values[]=strval($tempvalue); }
	 return str_replace($iban_replace_chars,$iban_replace_values,$s);
	}

	public function iban_to_machine_format($iban) {
	 # Uppercase and trim spaces from left
	 $iban = ltrim(strtoupper($iban));
	 # Remove IIBAN or IBAN from start of string, if present
	 $iban = preg_replace('/^I?IBAN/','',$iban);
	 # Remove all non basic roman letter / digit characters
	 $iban = preg_replace('/[^a-zA-Z0-9]/','',$iban);
	 return $iban;
	}

	public function iban_find_checksum($iban) {
	 $iban = iban_to_machine_format($iban);
	 # move first 4 chars to right
	 $left = substr($iban,0,2) . '00'; # but set right-most 2 (checksum) to '00'
	 $right = substr($iban,4);
	 # glue back together
	 $tmp = $right . $left;
	 # convert letters using conversion table
	 $tmp = iban_checksum_string_replace($tmp);
	 # get mod97-10 output
	 $checksum = iban_mod97_10_checksum($tmp);
	 # return 98 minus the mod97-10 output, left zero padded to two digits
	 return str_pad((98-$checksum),2,'0',STR_PAD_LEFT);
	}

	public function iban_to_human_format($iban) {
	 # Remove all spaces
	 $iban = str_replace(' ','',$iban);
	 # Add spaces every four characters
	 return wordwrap($iban,4,' ',true);
	}

	public function iban_mod97_10_checksum($numeric_representation) {
	 $checksum = intval(substr($numeric_representation, 0, 1));
	 for ($position = 1; $position < strlen($numeric_representation); $position++) {
	  $checksum *= 10;
	  $checksum += intval(substr($numeric_representation,$position,1));
	  $checksum %= 97;
	 }
	 return $checksum;
	}

	public function iban_set_checksum($iban) {
	 $iban = iban_to_machine_format($iban);
	 return substr($iban,0,2) . iban_find_checksum($iban) . substr($iban,4);
	}

	public function iban_mod97_10($numeric_representation) {
	 global $__disable_iiban_gmp_extension;
	 # prefer php5 gmp extension if available
	 if(!($__disable_iiban_gmp_extension) && function_exists('gmp_intval') && $numeric_representation!='') { return gmp_intval(gmp_mod(gmp_init($numeric_representation, 10),'97')) === 1; }

	 # new manual processing (~3x slower)
	 $length = strlen($numeric_representation);
	 $rest = "";
	 $position = 0;
	 while ($position < $length) {
		$value = 9-strlen($rest);
		$n = $rest . substr($numeric_representation,$position,$value);
		$rest = $n % 97;
		$position = $position + $value;
	 }
	 return ($rest === 1);
	}

	public function getTaxAccount() {
	  $IBAN = 'PL00101000712221' . $this->getPESEL() . '0';
	  return iban_to_human_format(iban_set_checksum($IBAN));
	}	
	
	
	
}
