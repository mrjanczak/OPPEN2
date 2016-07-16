<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MonthType extends AbstractType
{	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {		
		$builder
			->add('id', 'text', array('required' => false))
			->add('select', 'checkbox', array('required'  => false, 'mapped' => false))	
						
			->add('name', 'text', array('label' => 'Opis', 'required' => false,'attr' => array('size' => 4)))
			->add('is_active', 'checkbox', array('label' => 'Aktywny','required'  => false, 'disabled' => true))
			->add('is_closed', 'checkbox', array('label' => 'Zamknięty','required'  => false, 'disabled' => true))			
			->add('from_date', 'date', array('label' => 'Od', 'required' => false, 'widget' => 'single_text'))
			->add('to_date', 'date', array('label' => 'Do', 'required' => false, 'widget' => 'single_text'));           
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Model\Month',
		));
	}

    public function getName()
    {
        return 'month';
    }
}
