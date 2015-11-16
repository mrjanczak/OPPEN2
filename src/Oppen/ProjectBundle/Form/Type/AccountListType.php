<?php

namespace Oppen\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Oppen\ProjectBundle\Model\Year;
use Oppen\ProjectBundle\Model\YearQuery;

class AccountListType extends AbstractType
{
	public $Year;
	
	public function __construct (Year $Year) 
	{
		$this->Year = $Year;		
	}
		
    public function buildForm(FormBuilderInterface $builder, array $options)
    {		
        $builder
            ->add('cancel', 'submit', array('label' => 'Anuluj')) 
            ->add('search', 'submit', array('label' => 'Szukaj'))    
                              
			->add('Year', 'model', array(
				'label' => 'Rok',
				'empty_value' => false,
				'required'  => false,
				'class' => 'Oppen\ProjectBundle\Model\Year',
				'query' => YearQuery::create()
							->orderByFromDate() ))		

			->add('Accounts', 'collection', array(
				'type'          => new AccountType($this->Year, false),
				'allow_add'     => false,
				'allow_delete'  => false,
				'by_reference'  => false))
				;           
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Oppen\ProjectBundle\Model\AccountList',
		));
	}

    public function getName()
    {
        return 'account_list';
    }
}
