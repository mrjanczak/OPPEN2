<?php

namespace Oppen\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;

use Oppen\ProjectBundle\Model\Year;

use Oppen\ProjectBundle\Model\MonthQuery;
use Oppen\ProjectBundle\Model\FileQuery;
use Oppen\ProjectBundle\Model\DocCatQuery;

use Oppen\ProjectBundle\Form\Type\DocType;

class IncomeDocType extends AbstractType
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
            ->add('value', 'number', array()) 
            ->add('desc', 'text', array('label' => 'Opis', 'required' => false))
                        
			->add('Doc', new DocType($this->Year, false, false, 
				$this->securityContext, $this->disable_accepted_docs));           
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Oppen\ProjectBundle\Model\IncomeDoc',
		));
	}

    public function getName()
    {
        return 'income_doc';
    }
}
