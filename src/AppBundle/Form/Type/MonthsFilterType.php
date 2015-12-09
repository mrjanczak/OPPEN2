<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use AppBundle\Model\MonthQuery;

class MonthsFilterType extends AbstractType
{	
	public function __construct (Year $Year)
	{
		$this->Year = $Year;
	}		
    public function buildForm(FormBuilderInterface $builder, array $options)
    {			
        $builder
            ->add('filter', 'submit', array('label' => 'Filtr'))                                      

			->add('Month', 'model', array(
				'label' => 'Rok',
				'class' => 'AppBundle\Model\Month',
				'query' => MonthQuery::create()->findByYear($Year) ))
				;        
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Model\MonthsFilter'));
	}

    public function getName()
    {
        return 'months_filter';
    }
}
