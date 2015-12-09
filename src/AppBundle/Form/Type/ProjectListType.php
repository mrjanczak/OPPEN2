<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use AppBundle\Model\Year;
use AppBundle\Model\YearQuery;
use AppBundle\Model\Project;

class ProjectListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {	
		$builder
			->add('Year', 'model', array(
					'label' => 'Rok',
					'class' => 'AppBundle\Model\Year',
					'required' => false,
					'empty_value' => 'Wszystkie lata',
					'empty_data' => -1,
					'query' => YearQuery::create()
								->orderByFromDate() ))
			->add('search_name', 'text', array('label' => 'Nazwa','required' => false))
			->add('search', 'submit', array('label' => 'Szukaj'));	
			
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Model\ProjectList',
		));
	}

    public function getName()
    {
        return 'project_list';
    }
}
