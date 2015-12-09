<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

use AppBundle\Model\Year;

use AppBundle\Model\AccountQuery;
use AppBundle\Model\FileCatQuery;

class DocCatType extends AbstractType
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
			
			->add('as_income', 'checkbox',   array('label' => 'Przychód','required' => false))   
			->add('as_cost', 'checkbox',     array('label' => 'Koszt','required' => false))   
			->add('as_bill', 'checkbox', array('label' => 'Umowa','required' => false)); 
            
        if ($this->full) {
			
			$builder  
				->add('cancel', 'submit', array('label' => 'Anuluj'))        
				->add('save',   'submit', array('label' => 'Zapisz'))
				->add('delete', 'submit', array('label' => 'Usuń',
					'attr' => array('class' => 'confirm', 'data-confirm' => 'Czy chcesz usunąć kategorię dokumentu?')))  
				
				->add('doc_no_tmp', 'text', array('label' => 'Szablon numeracji','required' => false))
           			 													
				->add('FileCat', 'model', array(
					'label' => 'Kategoria kartoteki',
					'required' => false,
					'class' => 'AppBundle\Model\FileCat',
					'empty_value' => 'Brak',
					'query' => FileCatQuery::create()
								->filterByYear($this->Year)					
								->orderByName() ))
								
				->add('CommitmentAcc', 'model', array(
					'label' => 'Konto zobowiązań',
					'required' => false,
					'class' => 'AppBundle\Model\Account',
					'empty_value' => 'Brak konta',
					'query' => AccountQuery::create()
								->filterByTreeLevel(array('min' => 1))
								->filterByYear($this->Year)										
								->orderByAccNo() ))
								
				->add('TaxCommitmentAcc', 'model', array(
					'label' => 'Konto zobowiązań podatkowych',
					'required' => false,
					'class' => 'AppBundle\Model\Account',
					'empty_value' => 'Brak konta',
					'query' => AccountQuery::create()
								->filterByTreeLevel(array('min' => 1))
								->filterByYear($this->Year)					
								->orderByAccNo() ));	
								
		}
		$builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
			$form = $event->getForm();
			$DocCat = $event->getData();			
			if ($this->full) {
				$form->add('symbol', 'text', array('label' => 'Symbol', 'disabled' => $DocCat->getIsLocked()));
			} else {
				$form->add('select', 'checkbox',	array('required' => false, 'disabled' => $DocCat->getIsLocked()))
					 ->add('select2', 'checkbox',	array('required' => false));
			}
		});		
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Model\DocCat',
		));
	}

    public function getName()
    {
        return 'doc_cat';
    }
}
