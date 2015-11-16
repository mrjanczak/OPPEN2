<?php

namespace Oppen\ProjectBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

use Oppen\ProjectBundle\Model\Year;
use Oppen\ProjectBundle\Model\Month;
use Oppen\ProjectBundle\Model\Account;
use Oppen\ProjectBundle\Model\FileCat;
use Oppen\ProjectBundle\Model\BookkEntry;

use Oppen\ProjectBundle\Model\MonthQuery;
use Oppen\ProjectBundle\Model\FileQuery;
use Oppen\ProjectBundle\Model\DocCatQuery;
use Oppen\ProjectBundle\Model\AccountQuery;


class BookkEntryType extends AbstractType
{	
	public function __construct (Year $Year, $disable_accepted_docs)  
	{
		$this->Year = $Year;
		$this->disable_accepted_docs = $disable_accepted_docs;			
	}	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {		
		$builder
				->add('id', 'text', array('required' => false))  
				->add('side', 'number', array('required' => true))
				
				->add('account_id', 'number', array('required' => false))  
				->add('file_lev1_id', 'number', array('required' => false))
				->add('file_lev2_id', 'number', array('required' => false)) 
				->add('file_lev3_id', 'number', array('required' => false)); 						
						
		$builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
			$form = $event->getForm();
			
			$BookkEntry = $event->getData();
			if($BookkEntry instanceof BookkEntry) {	
				$isAccepted = $BookkEntry->getBookk()->getIsAccepted(); 
				$Account = $BookkEntry->getAccount(); }
			else { 
				$isAccepted = false; 
				$Account = new Account; }
				
			$disabled = ($isAccepted && $this->disable_accepted_docs);
			
			$form
				->add('accNo', 'text', array('label' => 'Konto','required' => false, 'disabled' => $disabled))
				->add('value', 'number', array('label' => 'Kwota', 'required' => false, 'precision' => 2, 'disabled' => $disabled));
		});
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Oppen\ProjectBundle\Model\BookkEntry',
		));
	}

    public function getName()
    {
        return 'bookk_entry';
    }
}
