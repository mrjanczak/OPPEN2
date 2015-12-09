<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

use AppBundle\Model\Year;
use AppBundle\Model\Account;

use AppBundle\Model\AccountQuery;
use AppBundle\Model\YearQuery;
use AppBundle\Model\FileCatQuery;
use AppBundle\Model\DocCatQuery;

class AccountType extends AbstractType
{
	public function __construct (Year $Year, $full)
	{
		$this->Year = $Year;
		$this->full = (bool) $full;
	}	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {		
        $builder
			->add('id', 'text',				array('required' => false));         
 
		if ($this->full) {       
        $builder
            ->add('cancel'   , 'submit', array('label' => 'Anuluj'))        
            ->add('save'     , 'submit', array('label' => 'Zapisz'))
            ->add('delete'   , 'submit', array('label' => 'Usuń',
					'attr' => array('class' => 'confirm', 'data-confirm' => 'Czy chcesz usunąć dokument?')))         
			->add('select'   , 'checkbox',array('required'  => false, 'mapped' => false))

            ->add('name'     , 'text',    array('label' => 'Nazwa', 'required' => false, 'attr' => array('size' => 80)))
            ->add('accNo'    , 'text',	  array('label' => 'Numer', 'required' => false))
            ->add('asBankAcc', 'checkbox',array('label' => 'Konto rach.bankowego', 'required' => false))
            ->add('asIncome' , 'checkbox',array('label' => 'Konto przychów', 'required' => false))    
            ->add('asCost'   , 'checkbox',array('label' => 'Konto kosztów',  'required' => false))       	
            ->add('incOpenB' , 'checkbox',array('label' => 'Ujęte w bilansie otwarcia',  'required' => false))       	
            ->add('incCloseB', 'checkbox',array('label' => 'Ujęte w bilansie zamknięcia','required' => false))       	
            ->add('asCloseB', 'checkbox',array('label' =>  'Wynik finansowy','required' => false))       	

			->add('FileCatLev1', 'model', array(
				'label' => 'Poziom 1',
				'required' => false,				
				'class' => 'AppBundle\Model\FileCat',
				'empty_value' => 'Brak kartoteki',				
				'query' => FileCatQuery::create()
							->filterByYear($this->Year)
							->orderByName() ))	

			->add('FileCatLev2', 'model', array(
				'label' => 'Poziom 2',
				'required' => false,
				'class' => 'AppBundle\Model\FileCat',
				'empty_value' => 'Brak kartoteki',
				'query' => FileCatQuery::create()
							->filterByYear($this->Year)
							->orderByName() ))	
														
			->add('FileCatLev3', 'model', array(
				'label' => 'Poziom 3',
				'required' => false,				
				'class' => 'AppBundle\Model\FileCat',
				'empty_value' => 'Brak kartoteki',				
				'query' => FileCatQuery::create()
							->filterByYear($this->Year)
							->orderByName() ))	;
		
			$builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
				$form = $event->getForm();
				$Account = $event->getData();
				$q = AccountQuery::create()->orderByTreeLeft();
				
				if($Account instanceOf Account) {
					$Year = $Account->getYear(); 
					if($Year instanceOf Year) {
						$q->filterByYear($Year);}
					if($Account->hasChildren()) {
						$descendant_ids = array();
						foreach($Account->getDescendants() as $Descendant) {
							$descendant_ids[] = $Descendant->getId();}
						$q->where('account.id NOT IN ?', $descendant_ids );
					}
				}
														
				$form->add('Parent', 'model', array(
					'label' => 'Poziom wyżej',
					'required' => false,
					'empty_value' => false,
					'class' => 'AppBundle\Model\Account',
					'query' => $q )); 
			});	
		}
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Model\Account',
		));
	}

    public function getName()
    {
        return 'account';
    }
}
