<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use AppBundle\Model\Year;
use AppBundle\Model\YearQuery;

class MonthListType extends AbstractType
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
                              
			->add('Year', 'model', array(
				'label' => 'Rok',
				'empty_value' => false,
				'required'  => false,
				'class' => 'AppBundle\Model\Year',
				'query' => YearQuery::create()
							->orderByFromDate() ))		

			->add('Months', 'collection', array(
				'type'          => new MonthType(null, false),
				'allow_add'     => false,
				'allow_delete'  => false,
				'by_reference'  => false))
				;           
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Model\MonthList',
		));
	}

    public function getName()
    {
        return 'month_list';
    }
}
