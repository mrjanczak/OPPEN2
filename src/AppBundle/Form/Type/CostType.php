<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;

use AppBundle\Model\Year;
use AppBundle\Model\FileCat;

use AppBundle\Model\FileQuery;
use AppBundle\Model\AccountQuery;

use AppBundle\Form\Type\CostIncomeType;
use AppBundle\Form\Type\CostDocType;

class CostType extends AbstractType
{
	public function __construct ( Year $Year, $CostFileCat, $full, 
		SecurityContext $securityContext, $disable_accepted_docs)
	{
		$this->Year = $Year;
		$this->CostFileCat = $CostFileCat;
		$this->full =  (bool) $full;
		$this->securityContext = $securityContext;	
		$this->disable_accepted_docs = $disable_accepted_docs;
	}		
    public function buildForm(FormBuilderInterface $builder, array $options)
    {		
        $builder
			->add('select', 'checkbox', array('required'  => false, 'mapped' => false))        
            ->add('id', 'text', array('required' => false))   
            ->add('value', 'number', array('label' => 'Wartość','scale' => 2,  'required' => false)); 
            
        if ($this->full) {
			$builder  
				->add('cancel', 'submit', array('label' => 'Anuluj'))        
				->add('save', 'submit', array('label' => 'Zapisz'))
				->add('delete', 'submit', array('label' => 'Usuń',
				'attr' => array('class' => 'confirm', 'data-confirm' => 'Czy chcesz usunąć kategorię kosztów?')))  
											 
				->add('name', 'text', array('label' => 'Nazwa', 'required' => false))
				->add('comment', 'textarea', array('label' => 'Opis', 'required' => false))           
			 
				->add('File', 'model', array(
					'label' => 'Kartoteka kosztów',
					'required' => false,
					'class' => 'AppBundle\Model\File',
					'empty_value' => 'Brak konta',
					'query' => FileQuery::create()
							->filterByFileCat($this->CostFileCat)	
							->orderByAccNo() ))
	 
				->add('BankAcc', 'model', array(
					'label' => 'Konto rachunku bankowego',
					'required' => false,
					'class' => 'AppBundle\Model\Account',
					'empty_value' => 'Brak konta',
					'query' => AccountQuery::create()
								->filterByAsBankAcc(1)
								->filterByTreeLevel(array('min' => 1))
								->filterByYear($this->Year)										
								->orderByAccNo() ))
													
				->add('CostAcc', 'model', array(
					'label' => 'Konto kosztów',
					'required' => false,
					'class' => 'AppBundle\Model\Account',
					'empty_value' => 'Brak konta',
					'query' => AccountQuery::create()
								->filterByAsCost(1)
								->filterByTreeLevel(array('min' => 1))
								->filterByYear($this->Year)										
								->orderByAccNo()  ));			
		} else {
			$builder
				->add('CostIncomes', 'collection', array(
					'type'          => new CostIncomeType(),
					'by_reference'  => false))
					
				->add('CostDocs', 'collection', array(
					'type'          => new CostDocType($this->Year,
						$this->securityContext, $this->disable_accepted_docs),
					'by_reference'  => false))			

				->add('Contracts', 'collection', array(
					'type'          => new ContractType($this->Year, false),
					'by_reference'  => false));			
		}
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Model\Cost',
		));
	}

    public function getName()
    {
        return 'cost';
    }
}
