<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

use AppBundle\Model\Year;

use AppBundle\Model\MonthQuery;
use AppBundle\Model\FileQuery;
use AppBundle\Model\CostQuery;
use AppBundle\Model\TemplateQuery;

class ContractType extends AbstractType
{
	public function __construct ( Year $Year,  $full)
	{
		$this->Year = $Year;
		$this->full = (bool) $full;
	}	
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {		
        $builder
			->add('select', 'checkbox', array('required'  => false, 'mapped' => false))         
			->add('id', 'text', array('required' => false)) 
			->add('comment', 'textarea', array('label' => 'Komentarz', 'required' => false)) 
			->add('gross', 'number', array('label' => 'Brutto', 'precision' => 2, 'required' => true))
			->add('netto', 'number', array('label' => 'Netto', 'precision' => 2, 'required' => true,'disabled'=>true))
			->add('tax',   'number', array('label' => 'PDOF', 'precision' => 2, 'required' => true,'disabled'=>true)); 
			
        if ($this->full) {
			$builder			       
				->add('cancel', 'submit', array('label' => 'Anuluj'))        
				->add('save', 'submit', array('label' => 'Zapisz'))
				->add('delete', 'submit', array('label' => 'Usuń',
				'attr' => array('class' => 'confirm', 'data-confirm' => 'Czy chcesz usunąć umowę?')))          
												 
				->add('contract_no', 'text', array('label' => 'Nr umowy', 'required' => false))
				->add('contract_date', 'date', array('label' => 'Data umowy', 'required' => false, 'widget' => 'single_text'))
				->add('contract_place', 'text', array('label' => 'Miejsce umowy', 'required' => false))
				
				->add('event_desc', 'textarea', array('label' => 'Przedmiot umowy', 'required' => false)) 
				->add('event_date', 'date', array('label' => 'Data imprezy', 'required' => false, 'widget' => 'single_text'))
				->add('event_name', 'text', array('label' => 'Nazwa imprezy', 'required' => false))
				->add('event_place','text', array('label' => 'Miejsce imprezy', 'required' => false))
				->add('event_role', 'text', array('label' => 'Udział w imprezie jako...', 'required' => false)) 
				  
				->add('cost_coef', 'number', array('label' => 'Koszt uzys. przych. [%]', 'precision' => 0,'required' => false))    		 
				->add('tax_coef', 'number', array('label' => 'PDOF [%]', 'precision' => 0,'required' => false)) 
				
				->add('payment_period', 'text', array('label' => 'Okres płatności', 'empty_data'=> '+2w', 'required' => false))
				->add('payment_method', 'choice', array(
					'label' => 'Forma płatności',
					'choices' => array( '1' => 'przelew', '2' => 'gotówka',) ))
				//->add('doc_id', 'text', array('label' => 'ID Rachunku', 'required' => false)) 
																	
				->add('File', 'model', array(
					'label' => 'Zleceniobiorca',
					'required' => false,
					'class' => 'AppBundle\Model\File',
					'empty_value' => 'Wybierz Zleceniobiorcę',
					'query' => FileQuery::create()
								->useFileCatQuery()
									->filterByAsContractor(1)
									->filterByYear($this->Year)
								->endUse()						
								->orderByName() ))	

				->add('Month', 'model', array(
					'label' => 'Miesiąc',
					'class' => 'AppBundle\Model\Month',
					'query' => MonthQuery::create()
								->filterByYear($this->Year)
								->orderByFromDate() ))  

				->add('Template', 'model', array(
					'label' => 'Szablon',
					'required' => false,
					'class' => 'AppBundle\Model\Template',
					'empty_value' => 'Wybierz szablon',
					'query' => TemplateQuery::create()
								->filterByAsContract(1)
								->orderByName() ))	;  
								
			$builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
				$form = $event->getForm();
				
				$Contract = $event->getData();	
				if(!($Contract instanceOf Contract)) {  
					//throw $this->createNotFoundException(':-( The Contract does not exist'); 
					}
					
				$Cost = $Contract->getCost();							
				if(!($Cost instanceOf Cost)) {	
					//throw $this->createNotFoundException(':-( The Cost does not exist'); 
					}	
								
				$Project = $Cost->getProject();
				if(!($Project instanceOf Project)) {	
					//throw $this->createNotFoundException(':-( The Project does not exist'); 
					}	

				$form->add('Cost', 'model', array(
					'label' => 'Grupa kosztów',
					'required' => true,				
					'class' => 'AppBundle\Model\Cost',
					'empty_value' => 'Wybierz grupę',				
					'query' => CostQuery::create()->filterByProject($Project)->orderBySortableRank() ));
			});	
		}						
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Model\Contract',
		));
	}

    public function getName()
    {
        return 'contract';
    }
}
