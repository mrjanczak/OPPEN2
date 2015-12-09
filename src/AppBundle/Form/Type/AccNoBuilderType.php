<?php

namespace AppBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

use AppBundle\Model\Year;
use AppBundle\Model\Month;
use AppBundle\Model\Account;
use AppBundle\Model\FileCat;
use AppBundle\Model\BookkEntry;

use AppBundle\Model\MonthQuery;
use AppBundle\Model\FileQuery;
use AppBundle\Model\DocCatQuery;
use AppBundle\Model\AccountQuery;


class AccNoBuilderType extends AbstractType
{
	public function __construct (Year $Year, $Account)
	{
		$this->Year = $Year;
		$this->AccNo = $Account;				
	}	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {					
		$builder->add('Account', 'model', array(
					'label' => 'Konto',
					'disabled' => $isAccepted,
					'required' => false,
					'class' => 'AppBundle\Model\Account',
					'empty_value' => 'Wybierz konto',				
					'query' => AccountQuery::create()
								->filterByYear($this->Year)
								->filterByTreeLevel(array('min'=>1))
								->orderByAccNo() ));
											
			if($Account instanceOf Account) {		
				$FileCat = array();	
				$FileCat[1] = $Account->getFileCatLev1();
				$FileCat[2] = $Account->getFileCatLev2();
				$FileCat[3] = $Account->getFileCatLev3();
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
					'class' => 'AppBundle\Model\File',
					'empty_value' => 'Wybierz kartotekę',				
					'query' => $query ));
			}
		});
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Model\BookkEntry',
		));
	}

    public function getName()
    {
        return 'bookk_entry';
    }
}
