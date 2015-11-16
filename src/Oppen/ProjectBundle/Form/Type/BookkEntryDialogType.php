<?php

namespace Oppen\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Oppen\ProjectBundle\Model\Year;
use Oppen\ProjectBundle\Model\Account;
use Oppen\ProjectBundle\Model\FileCat;

use Oppen\ProjectBundle\Model\FileQuery;
use Oppen\ProjectBundle\Model\AccountQuery;

class BookkEntryDialogType extends AbstractType
{
	public function __construct (Year $Year, $Account)
	{
		$this->Year = $Year;
		$this->Account = $Account;				
	}	
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {					
		$builder->add('Account', 'model', array(
							'label' => 'Konto',
							'required' => false,
							'class' => 'Oppen\ProjectBundle\Model\Account',
							'empty_value' => 'Wybierz konto',				
							'query' => AccountQuery::create()
										->filterByYear($this->Year)
										->filterByTreeLevel(array('min'=>1))
										->orderByAccNo() ));
								
		if($this->Account instanceOf Account) {		
			$FileCat = array();	
			$FileCat[1] = $this->Account->getFileCatLev1();
			$FileCat[2] = $this->Account->getFileCatLev2();
			$FileCat[3] = $this->Account->getFileCatLev3();
		}
		else {
				$FileCat[1] = null;
				$FileCat[2] = null;
				$FileCat[3] = null;			
		}
		for ($lev=1; $lev<=3; $lev++) {
			
			if($FileCat[$lev] instanceOf FileCat) { 
				$label = $FileCat[$lev]->getName();
				$query = FileQuery::create()->filterByFileCat($FileCat[$lev])->orderByAccNo();
				$required = true;
			}	
			else {
				$label = '';
				$query = FileQuery::create()->filterById(0);
				$required = false;						
			}	
				
			$builder->add('FileLev'.$lev, 'model', array(
				'label' => $label,
				'required' => false,				
				'class' => 'Oppen\ProjectBundle\Model\File',
				'empty_value' => 'Wybierz kartotekę',				
				'query' => $query ));
		}
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Oppen\ProjectBundle\Model\BookkEntry',
		));
	}

    public function getName()
    {
        return 'bookk_entry_dialog';
    }
}
