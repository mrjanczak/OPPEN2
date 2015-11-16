<?php

namespace Oppen\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Oppen\ProjectBundle\Model\MonthQuery;

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
				'class' => 'Oppen\ProjectBundle\Model\Month',
				'query' => MonthQuery::create()->findByYear($Year) ))
				;        
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Oppen\ProjectBundle\Model\MonthsFilter'));
	}

    public function getName()
    {
        return 'months_filter';
    }
}
