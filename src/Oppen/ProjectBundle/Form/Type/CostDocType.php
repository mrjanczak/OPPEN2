<?php

namespace Oppen\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;

use Oppen\ProjectBundle\Model\Year;
use Oppen\ProjectBundle\Form\Type\CostDocIncomeType;

class CostDocType extends AbstractType
{
	public function __construct (Year $Year, 
		SecurityContext $securityContext, $disable_accepted_docs)
	{
		$this->Year = $Year;
		$this->securityContext = $securityContext;	
		$this->disable_accepted_docs = $disable_accepted_docs;	
	}	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {		
        $builder
			->add('select', 'checkbox', array('required'  => false, 'mapped' => false))
			
			->add('id', 'text', array('required' => false))                       
            ->add('value', 'number', array('required' => false)) 	    
            ->add('desc', 'text', array('label' => 'Opis', 'required' => false))
            			
			->add('CostDocIncomes', 'collection', array(
				'type'          => new CostDocIncomeType(),
				'by_reference'  => false))
            
			->add('Doc', new DocType($this->Year, false, false, 
				$this->securityContext, $this->disable_accepted_docs));   
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Oppen\ProjectBundle\Model\CostDoc',
		));
	}

    public function getName()
    {
        return 'cost_doc';
    }
}
