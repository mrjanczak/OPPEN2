<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use AppBundle\Model\Year;
use AppBundle\Model\Month;
use AppBundle\Model\Bookk;
use AppBundle\Model\DocCat;
use AppBundle\Model\FileCat;

use AppBundle\Model\YearQuery;
use AppBundle\Model\MonthQuery;
use AppBundle\Model\FileQuery;
use AppBundle\Model\FileCatQuery;
use AppBundle\Model\AccountQuery;
use AppBundle\Model\DocCatQuery;
use AppBundle\Model\TemplateQuery;

use AppBundle\Form\Type\IncomeType;
use AppBundle\Form\Type\CostType;
use AppBundle\Form\Type\BookkType;
use AppBundle\Form\Type\DocListType;

class ProjectType extends AbstractType
{
	public function __construct ($tab_id, Year $Year, SecurityContext $securityContext, $disable_accepted_docs)
	{
		$this->tab_id = $tab_id;
		$this->Year = $Year;
		$this->securityContext = $securityContext;
		$this->disable_accepted_docs = $disable_accepted_docs;
	}	
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {	
		$builder
			->add('cancel', 'submit', array('label' => 'Anuluj'))
			->add('save', 'submit',   array('label' => 'Zapisz'))
			->add('delete', 'submit', array('label' => 'Usuń',
				'attr' => array('class' => 'confirm', 'data-confirm' => 'Czy chcesz usunąć Projekt?')))
						
			->add('id', 'text', array('required' => false))
			->add('name', 'text', array('label' => 'Nazwa','required' => false));
												   
		if ($this->tab_id == 1) {
			$builder
				->add('desc', 'textarea', array('label' => 'Opis', 'required' => false))
				->add('place', 'text', array('label' => 'Miejsce', 'required' => false))
				->add('from_date', 'date', array('label' => 'Od', 'required' => false, 'widget' => 'single_text'))
				->add('to_date', 'date', array('label' => 'Do', 'required' => false, 'widget' => 'single_text'))
				->add('comment', 'textarea', array('label' => 'Komentarz', 'required' => false))
			  
				->add('File', 'model', array(
					'label' => 'Kartoteka projektu',
					'required' => false,
					'class' => 'AppBundle\Model\File',
					'empty_value' => 'Brak kartoteki',
					'empty_data' => 0,
					'query' => FileQuery::create()
								->useFileCatQuery()
									->filterByAsProject(1)
									->filterByYear($this->Year)
								->endUse()						
								->orderByAccNo() ))

				->add('CostFileCat', 'model', array(
					'label' => 'Kategoria Kartoteki kosztów',
					'required' => false,
					'class' => 'AppBundle\Model\FileCat',
					'empty_value' => 'Brak kategorii',
					'empty_data' => 0,
					'query' => FileCatQuery::create()
								->filterByAsCost(1)
								->filterByYear($this->Year)					
								->orderByName() ))

				->add('status', 'choice', array(
					'label' => 'Status',
					'choices' => array('-1' => 'w przygotowaniu', '0' => 'rozpoczęty', '1' => 'zamknięty'), ))			

				->add('IncomeAcc', 'model', array(
					'label' => 'Konto przychodów',
					'required' => false,
					'class' => 'AppBundle\Model\Account',
					'empty_value' => 'Brak konta',
					'query' => AccountQuery::create()
								->filterByAsIncome(1)
								->filterByYear($this->Year)					
								->orderByAccNo() ))

				->add('CostAcc', 'model', array(
					'label' => 'Konto kosztów',
					'required' => false,
					'class' => 'AppBundle\Model\Account',
					'empty_value' => 'Brak konta',
					'query' => AccountQuery::create()
								->filterByAsCost(1)
								->filterByYear($this->Year)					
								->orderByAccNo() ))

				->add('BankAcc', 'model', array(
					'label' => 'Konto bankowe',
					'required' => false,
					'class' => 'AppBundle\Model\Account',
					'empty_value' => 'Brak konta',
					'query' => AccountQuery::create()
								->filterByAsBankAcc(1)
								->filterByYear($this->Year)					
								->orderByAccNo() ))
			/*					
				->add('Tasks', 'collection', array(
					'type' => new TaskType(),
					'label' => 'Zadania',
					'allow_add'     => true,
					'allow_delete'  => true )) */
			; 
		}   	  
								
		if (in_array($this->tab_id, array(2,3,4))) {
		  $builder    	  
			->add('Incomes', 'collection', array(
				'type'          => new IncomeType($this->Year, false, 
					$this->securityContext, $this->disable_accepted_docs) ))
				
			->add('Costs', 'collection', array(
				'type'          => new CostType($this->Year, null, false, 
					$this->securityContext, $this->disable_accepted_docs) ))
				
			->add('bookking_date', 'date', array(
				'label' => 'Data księgowania',   'required' => false, 'widget' => 'single_text', 'mapped' => false))
			  		  
			->add('payment_date', 'date', array(
				'label' => 'Data płatności',   'required' => false, 'widget' => 'single_text', 'mapped' => false,
				'data' => new \DateTime('now')))
			
			->add('payment_DocCat_symbol', 'choice', array(
				'label' => 'Kat. dok. płatności',
				'mapped' => false,
				'choices' => array( 'WB' => 'wyciąg bankowy', 'RK' => 'raport kasowy')))
							
			->add('payment_period', 'text', array(
				'label' => 'Okres płatności', 'empty_data' => '+2 weeks', 'required' => false, 'mapped' => false))
			
			->add('doc_no', 'text', array('label' => 'Nr dokumentu', 'required' => false, 'mapped'=>false))
				
			// buttons	
			->add('generateBookks', 'submit', array('label' => 'Utwórz Dekretacje'))
			
			->add('setBookkingDate', 'submit', array('label' => 'Ustaw datę księgowania'))
			
			->add('setPaymentDate', 'submit', array('label' => 'Ustaw datę płatności'))
			
			->add('setPaymentDeadlineDate', 'submit', array('label' => 'Ustaw termin płatności'))
						
			->add('downloadCosts', 'submit', array('label' => 'Koszty (csv)'))
			
			->add('removeDocs', 'submit', array('label' => 'Usuń dokumenty',
				'attr' => array('class' => 'confirm', 'data-confirm' => 'Czy chcesz usunąć Dokumenty z kat. kosztów?')))
			
			->add('downloadTransfers', 'submit', array('label' => 'Przelwy (csv)'));
		}	
		
		if (in_array($this->tab_id, array(2,3))) {			
			$Templates = TemplateQuery::create()
				->filterByAsBooking(true)
				->_or()
				->filterByAsTransfer(true)
				->find();
			foreach($Templates as $Tmp) {
				$builder->add($Tmp->getSymbol(), 'checkbox', array('label' => $Tmp->getName().' ('.$Tmp->getSymbol().')','required'  => false, 'mapped' => false));}
		}
		
		if ($this->tab_id == 4) {
		  $builder
			->add('receipt_date',   'date', array('label' => 'Data wpłynięcia', 'required' => false, 'widget' => 'single_text', 'mapped' => false))
			->add('document_date',  'date', array('label' => 'Data dokumentu',  'required' => false, 'widget' => 'single_text', 'mapped' => false))
			->add('operation_date', 'date', array('label' => 'Data operacji',   'required' => false, 'widget' => 'single_text', 'mapped' => false))
			->add('DocMonth', 'model', array('label' => 'Miesiąc dokumentu',	'class' => 'AppBundle\Model\Month', 'mapped' => false,
				  'query' => MonthQuery::create()->filterByYear($this->Year)->orderByFromDate() ))			  
			->add('event_desc', 'textarea', array('label' => 'Przedmiot umowy',   'required' => false, 'mapped' => false))

			->add('generateDocs', 'submit', array('label' => 'Utwórz Dokumenty'))	
			->add('printContracts', 'submit', array('label' => 'Drukuj Umowy', 
													   'attr' => array('onclick' => "this.form.target='_blank';return true;")))
													   
			->add('setEventDesc', 'submit', array('label' => 'Ustaw przedmiot umowy'))			   								              
			->add('setPaymentPeriod', 'submit', array('label' => 'Przypisz do Umów'));
		}	
		if ($this->tab_id == 5) {
			$builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
				$form = $event->getForm();
				$Project = $event->getData();			
				$form
					->add('DocList', new DocListType($this->Year, $Project, null, null, 
						$this->securityContext, $this->disable_accepted_docs ));
			});
		}
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Model\Project',
		));
	}

    public function getName()
    {
        return 'project';
    }
}
