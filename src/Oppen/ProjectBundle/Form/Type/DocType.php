<?php

namespace Oppen\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\SecurityContext;

use Oppen\ProjectBundle\Model\Year;
use Oppen\ProjectBundle\Model\DocCat;
use Oppen\ProjectBundle\Model\FileCat;

use Oppen\ProjectBundle\Model\MonthQuery;
use Oppen\ProjectBundle\Model\FileQuery;
use Oppen\ProjectBundle\Model\DocCatQuery;

class DocType extends AbstractType
{
	protected $Year;
	protected $full;
	protected $disabled;
	protected $securityContext;
	protected $disable_accepted_docs;
	
	public function __construct (Year $Year, $full, $disabled, 
		SecurityContext $securityContext, $disable_accepted_docs)
	{
		$this->Year = $Year;
		$this->full = (bool) $full;
		$this->disabled = (bool) ($disabled && $disable_accepted_docs);
		$this->securityContext = $securityContext;
		$this->disable_accepted_docs = $disable_accepted_docs;
	}	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {		
        $builder
			->add('select', 'checkbox', array('required'  => false, 'mapped' => false))        
			->add('id', 'text', array('required' => false))
				
			->add('Bookks', 'collection', array('label' => 'Dekretacje',
				'type'          => new BookkType($this->Year, $this->full, 
					$this->securityContext, $this->disable_accepted_docs),
				'allow_add'     => true,
				'allow_delete'  => true, 
				));   
			
		if ($this->full) {			
			$builder	
				->add('cancel', 'submit', array('label' => 'Anuluj'))        
				->add('save', 'submit', array('label' => 'Zapisz'))
				->add('save&new', 'submit', array('label' => 'Zapisz i Nowy')) 
				->add('delete', 'submit', array('label' => 'Usuń',
					  'attr' => array('class' => 'confirm', 'data-confirm' => 'Czy chcesz usunąć dokument?')))          
		 
				->add('document_date', 'date', array('label' => 'Data dokumentu', 'required' => false, 'widget' => 'single_text', 'disabled' => $this->disabled))
				->add('operation_date', 'date', array('label' => 'Data operacji', 'required' => false, 'widget' => 'single_text', 'disabled' => $this->disabled))
				->add('receipt_date', 'date', array('label' => 'Data otrzymania', 'required' => false, 'widget' => 'single_text', 'disabled' => $this->disabled))
				->add('bookking_date', 'date', array('label' => 'Data księgowania', 'required' => false, 'widget' => 'single_text', 'disabled' => $this->disabled ))
				->add('payment_deadline_date', 'date', array('label' => 'Termin płatności', 'required' => false, 'widget' => 'single_text', 'disabled' => $this->disabled ))
				->add('payment_date', 'date', array('label' => 'Data płatności', 'required' => false, 'widget' => 'single_text', 'disabled' => $this->disabled ))

				->add('payment_method', 'choice', array(
					'label' => 'Metoda płatności',
					'choices' => array( '1' => 'przelew', '2' => 'gotówka',) ))
														
				->add('Month', 'model', array(
					'label' => 'Miesiąc',
					'disabled' => $this->disabled,
					'class' => 'Oppen\ProjectBundle\Model\Month',
					'query' => MonthQuery::create()
								->filterByYear($this->Year)
								->orderByFromDate() ))  
											
				->add('DocCat', 'model', array(
					'label' => 'Kategoria',
					'disabled' => $this->disabled,
					'class' => 'Oppen\ProjectBundle\Model\DocCat',
					'query' => DocCatQuery::create()
								->filterByYear($this->Year)
								->orderById() ))	
								
				->add('doc_idx', 'text', array('label' => 'Nr ewidencyjny', 'required' => false, 'disabled' => true))  
				->add('doc_no', 'text', array('label' => 'Nr dokumentu','required' => false, 'disabled' => $this->disabled))  	
								
				->add('desc', 'text', array('label' => 'Opis','required' => false, 'disabled' => $this->disabled))
				->add('comment', 'textarea', array('label' => 'Komentarz', 'required' => false)) ; 
								
			$builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
				$form = $event->getForm();
				$Doc = $event->getData();	
				$DocCat = $Doc->getDocCat();
				$FileCat = $DocCat->getFileCat();
				
				if($FileCat instanceOf FileCat) {  
					$label = $FileCat->getName();
					$q = FileQuery::create()->filterByFileCat($FileCat)->orderByAccNo();					
				}
				else {
					$label = 'Kartoteka';
					$q = FileQuery::create()->filterById(0);
				}					
				$form->add('File', 'model', array(
					'label' => $label,
					'disabled' => $this->disabled,
					'required' => false,
					'class' => 'Oppen\ProjectBundle\Model\File',
					'empty_value' => 'Brak kartoteki',
					'query' => $q));				
			});
		}
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Oppen\ProjectBundle\Model\Doc',
		));
	}

    public function getName()
    {
        return 'doc';
    }
}
