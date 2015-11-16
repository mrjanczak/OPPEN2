<?php

namespace Oppen\ProjectBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface; 
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

use Oppen\ProjectBundle\Model\Year;
use Oppen\ProjectBundle\Model\Bookk;
use Oppen\ProjectBundle\Model\ProjectQuery;

class BookkDialogType extends AbstractType
{
	public function __construct (Year $Year)
	{
		$this->Year = $Year;
	}	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {				
		$builder 				
			->add('desc', 'text', array('label' => 'Opis','required' => false))	 
			->add('bookking_date', 'date', array('label' => 'Data księgowania', 'required' => false, 'widget' => 'single_text'))				 
			->add('Project', 'model', array(
				'required' => false,
				'label' => 'Projekt',
				'class' => 'Oppen\ProjectBundle\Model\Project',
				'query' => ProjectQuery::create()
							->filterByYear($this->Year)
							->orderByFromDate() ));					
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Oppen\ProjectBundle\Model\Bookk',
		));
	}

    public function getName()
    {
        return 'bookk_dialog';
    }
}
