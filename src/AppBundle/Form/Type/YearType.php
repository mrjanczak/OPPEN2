<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use AppBundle\Model\Year;
use AppBundle\Model\YearQuery;

class YearType extends AbstractType
{	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {		
        $builder
            ->add('save', 'submit', array('label' => 'Zapisz'))
            ->add('cancel', 'submit', array('label' => 'Anuluj')) 
			
			->add('close_month_dialog', 'submit', array('label' => 'Zamknij okres'))
            ->add('close_month', 'submit', array('label' => 'Zamknij okres',
				'attr' => array('class' => 'confirm', 'data-confirm' => 'Czy chcesz zamknąć okres?'))) 
            ->add('activate_month', 'submit', array('label' => 'Aktywuj okres'))                      
			
			->add('id', 'text', array('required' => false))
			->add('select', 'checkbox', array('required'  => false, 'mapped' => false))			
			->add('name', 'text', array('label' => 'Opis', 'required' => false,'attr' => array('size' => 4)))
			->add('is_active', 'checkbox', array('label' => 'Aktywny','required'  => false, 'disabled' => true))
			->add('is_closed', 'checkbox', array('label' => 'Zamknięty','required'  => false, 'disabled' => true))			
			->add('from_date', 'date', array('label' => 'Od', 'required' => false, 'widget' => 'single_text'))
 			->add('to_date', 'date', array('label' => 'Do', 'required' => false, 'widget' => 'single_text'))
                        
			->add('Months', 'collection', array(
				'type'          => new MonthType(null, false),
				'allow_add'     => false,
				'allow_delete'  => false,
				'by_reference'  => false));           
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Model\Year',
		));
	}

    public function getName()
    {
        return 'year';
    }
}
