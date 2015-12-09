<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

use AppBundle\Model\Year;
use AppBundle\Model\FileCatQuery;

class FileCatType extends AbstractType
{	
	protected $Year;
	protected $full;
	
	public function __construct ( Year $Year,  $full)
	{
		$this->Year = $Year;
		$this->full = (bool) $full;
	}	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {		
        $builder
            ->add('id', 'text', array('required' => false))   
            ->add('name', 'text', array('label' => 'Nazwa'))
            ->add('symbol', 'text', array('label' => 'Symbol', 'required' => false))
               
            ->add('as_income', 'checkbox', array('label' => 'Przychód','required' => false))   
            ->add('as_cost', 'checkbox', array('label' => 'Koszt','required' => false))   
            ->add('as_contractor', 'checkbox', array('label' => 'Zleceniobiorca','required' => false)) ;         
       if($this->full) {
       $builder 
            ->add('save', 'submit', array('label' => 'Zapisz')) 
            ->add('cancel', 'submit', array('label' => 'Anuluj')) 
            ->add('delete', 'submit', array('label' => 'Usuń',
				'attr' => array('class' => 'confirm', 'data-confirm' => 'Czy chcesz usunąć kategorię kartoteki?'))) 
                                            
			->add('SubFileCat', 'model', array(
				'label' => 'Podkategoria',
				'required' => false,
				'class' => 'AppBundle\Model\FileCat',
				'empty_value' => '',
				'query' => FileCatQuery::create()
							->filterByYear($this->Year)
							->orderByName() ));	                     	
		}
		$builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
			$form = $event->getForm();
			$FileCat = $event->getData();	
			if ($this->full) {
				$form->add('symbol', 'text', array('label' => 'Symbol', 'disabled' => $FileCat->getIsLocked()));
			} else {					
				$form->add('select', 'checkbox',	array('required' => false, 'disabled' => $FileCat->getIsLocked()))
					 ->add('select2', 'checkbox',	array('required' => false));
			}
		});
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Model\FileCat',
		));
	}

    public function getName()
    {
        return 'file_cat';
    }
}
