<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use AppBundle\Model\Year;
use AppBundle\Model\YearQuery;
use AppBundle\Model\MonthQuery;
use AppBundle\Model\AccountQuery;
use AppBundle\Model\FileQuery;

use AppBundle\Form\Type\ReportType;

class ReportListType extends AbstractType
{
	public $Year;
	
	public function __construct (Year $Year) 
	{
		$this->Year = $Year;		
	}
		
    public function buildForm(FormBuilderInterface $builder, array $options)
    {		
        $builder
            ->add('cancel', 'submit', array('label' => 'Anuluj')) 
            ->add('search', 'submit', array('label' => 'Szukaj'))   
             
            ->add('generateTurnOver', 'submit', array('label' => 'Obroty konta wg miesięcy')) 
            ->add('generateObligations', 'submit', array('label' => 'Obroty konta wg kartotek')) 
            ->add('generateRecords', 'submit', array('label' => 'Zapisy konta')) 
            ->add('generateRegister', 'submit', array('label' => 'Dziennik księgowań')) 
 
            ->add('generateOpenBalance', 'submit', array('label' => 'Bilans otwarcia')) 
            ->add('generateCloseBalance', 'submit', array('label' => 'Bilans zamknięcia')) 
                 
                              
			->add('Year', 'model', array(
				'label' => 'Rok',
				'empty_value' => false,
				'required'  => false,
				'class' => 'AppBundle\Model\Year',
				'query' => YearQuery::create()
							->orderByFromDate() ))		

			->add('Reports', 'collection', array(
				'type'          => new ReportType(null, false),
				'allow_add'     => false,
				'allow_delete'  => false,
				'by_reference'  => false))

			->add('reportMethod', 'choice', array(
				'label' => 'Metoda rozliczenia',
				'choices' => array( 
					'kasowa wg dok.'=>1, 
					'memor. wg dok.'=>2,
					'memor. wg dekret.'=>3,),
				'choices_as_values' => true,	))

				
			->add('FromDate', 'date', array('label' => 'Od', 'required' => false, 'widget' => 'single_text'))
			->add('ToDate', 'date', array('label' => 'Do', 'required' => false, 'widget' => 'single_text'))

			->add('Month', 'model', array(
				'label' => 'Miesiąc',
				'empty_value' => false,
				'required'  => false,
				'class' => 'AppBundle\Model\Month',
				'query' => MonthQuery::create()
							->filterByYear($this->Year)
							->orderByFromDate() ))

			->add('accNo', 'text', array('label' => 'Konto','required' => false))
 				
			->add('Account', 'model', array(
				'label' => 'Konto',
				'required' => false,
				'class' => 'AppBundle\Model\Account',
				'empty_value' => 'Wybierz konto',				
				'query' => AccountQuery::create()
							->filterByYear($this->Year)
							->filterByTreeLevel(array('min'=>1))
							->orderByAccNo() ));
										
		$FileCat[1] = null;
		$FileCat[2] = null;
		$FileCat[3] = null;			
			
		for ($lev=1; $lev<=3; $lev++) {
			$label = ' ';
			$query = FileQuery::create()->filterById(0);
			$required = false;						
			
			$builder->add('FileLev'.$lev, 'model', array(
				'label' => $label,
				'required' => false,				
				'class' => 'AppBundle\Model\File',
				'empty_value' => 'brak kartoteki',				
				'query' => $query ));
		}			
			          
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Model\ReportList',
		));
	}

    public function getName()
    {
        return 'report_list';
    }
}
