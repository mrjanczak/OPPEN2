<?php

namespace Oppen\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;

use Oppen\ProjectBundle\Model\Year;

use Oppen\ProjectBundle\Model\FileQuery;
use Oppen\ProjectBundle\Model\FileCatQuery;
use Oppen\ProjectBundle\Model\AccountQuery;
use Oppen\ProjectBundle\Model\YearQuery;

class IncomeType extends AbstractType
{
	public function __construct ( Year $Year,$as_form, 
		SecurityContext $securityContext, $disable_accepted_docs)
	{
		$this->Year = $Year;
		$this->as_form = (bool) $as_form;
		$this->securityContext = $securityContext;	
		$this->disable_accepted_docs = $disable_accepted_docs;	
	}	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {	
        $builder
			->add('select', 'checkbox', array('required'  => false, 'mapped' => false))
            ->add('id', 'text', array('required' => false)) 
            ->add('value', 'number', array('label' => 'Wartość', 'required' => false));             
                        
        if ($this->as_form) {
			$builder 		
            ->add('cancel', 'submit', array('label' => 'Anuluj'))        
            ->add('save', 'submit', array('label' => 'Zapisz'))
            ->add('delete', 'submit', array('label' => 'Usuń',
				'attr' => array('class' => 'confirm', 'data-confirm' => 'Czy chcesz usunąć kategorię przychodu?')))        
         
            ->add('name', 'text', array('label' => 'Nazwa', 'required' => false))
            ->add('shortname', 'text', array('label' => 'Skrót', 'required' => false))
            ->add('comment', 'textarea', array('label' => 'Opis', 'required' => false))           
			->add('show', 'checkbox', array('label'=> 'Pokaż w kosztach', 'required'  => false))
            
			->add('File', 'model', array(
				'label' => 'Kartoteka (Sponsor/Odbiorca)',
				'required' => false,
				'class' => 'Oppen\ProjectBundle\Model\File',
				'empty_value' => 'Brak konta',
				'query' => FileQuery::create()
					->useFileCatQuery()
						->filterByAsIncome(1)
						->filterByYear($this->Year)
						->orderByName('asc')						
					->endUse()	
					->orderByAccNo() ))
					
			->add('BankAcc', 'model', array(
				'label' => 'Konto rachunku bankowego',
				'required' => false,
				'class' => 'Oppen\ProjectBundle\Model\Account',
				'empty_value' => 'Brak konta',
				'query' => AccountQuery::create()
							->filterByAsBankAcc(1)
							->filterByTreeLevel(array('min' => 1))
							->filterByYear($this->Year)										
							->orderByAccNo() ))
												
			->add('IncomeAcc', 'model', array(
				'label' => 'Konto przychodów',
				'required' => false,
				'class' => 'Oppen\ProjectBundle\Model\Account',
				'empty_value' => 'Brak konta',
				'query' => AccountQuery::create()
							->filterByAsIncome(1)				
							->filterByTreeLevel(array('min' => 1))
							->filterByYear($this->Year)										
							->orderByAccNo() ));
		} else {
			$builder			
				->add('IncomeDocs', 'collection', array(
					'type'          => new IncomeDocType($this->Year, 
						$this->securityContext, $this->disable_accepted_docs),
					'by_reference'  => false));
		}
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Oppen\ProjectBundle\Model\Income',
		));
	}

    public function getName()
    {
        return 'income';
    }
}
