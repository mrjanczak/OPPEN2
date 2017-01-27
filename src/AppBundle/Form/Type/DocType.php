<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\SecurityContext;

use AppBundle\Model\Year;
use AppBundle\Model\DocCat;
use AppBundle\Model\File;
use AppBundle\Model\FileCat;

use AppBundle\Model\MonthQuery;
use AppBundle\Model\FileQuery;
use AppBundle\Model\DocCatQuery;

class DocType extends AbstractType
{
	protected $Year;
	protected $show_details;
	protected $disabled;
	protected $securityContext;
	protected $disable_accepted_docs;
	
	public function __construct (Year $Year, 
								 $show_details, 
								 $disabled, 
								 SecurityContext $security_context, 
								 $disable_accepted_docs)
	{
		$this->Year = $Year;
		$this->show_details = (bool) $show_details;
		$this->disabled = (bool) ($disabled && $disable_accepted_docs);
		$this->security_context = $security_context;
		$this->disable_accepted_docs = (bool) $disable_accepted_docs;
	}	
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {		
        $builder
			->add('select', 'checkbox', array('required'  => false, 'mapped' => false))        
			->add('id', 'text', array('required' => false));

		if ($this->show_details) 
		{		
			$builder	
				->add('cancel', 'submit', array('label' => 'Anuluj'))        
				->add('save', 'submit', array('label' => 'Zapisz'))
				->add('save&new', 'submit', array('label' => 'Zapisz i Nowy')) 
				->add('delete', 'submit', array('label' => 'Usuń',
					  'attr' => array('class' => 'confirm', 'data-confirm' => 'Czy chcesz usunąć dokument?')
				))          
		 
				->add('document_date', 'date', array('label' => 'Data dokumentu', 'required' => false, 'widget' => 'single_text', 'disabled' => $this->disabled))
				->add('operation_date', 'date', array('label' => 'Data operacji', 'required' => false, 'widget' => 'single_text', 'disabled' => $this->disabled))
				->add('receipt_date', 'date', array('label' => 'Data otrzymania', 'required' => false, 'widget' => 'single_text', 'disabled' => $this->disabled))
				->add('bookking_date', 'date', array('label' => 'Data księgowania', 'required' => false, 'widget' => 'single_text', 'disabled' => $this->disabled ))
				->add('payment_deadline_date', 'date', array('label' => 'Termin płatności', 'required' => false, 'widget' => 'single_text', 'disabled' => $this->disabled ))
				->add('payment_date', 'date', array('label' => 'Data płatności', 'required' => false, 'widget' => 'single_text', 'disabled' => $this->disabled ))

				->add('payment_method', 'choice', array(
					'label' => 'Metoda płatności',
					'choices' => array( '1' => 'przelew', '2' => 'gotówka',) 
				))
														
				->add('Month', 'model', array(
					'label' => 'Miesiąc',
					'disabled' => $this->disabled,
					'class' => 'AppBundle\Model\Month',
					'query' => MonthQuery::create()
								->filterByYear($this->Year)
								->orderByFromDate() 
				))  
											
				->add('DocCat', 'model', array(
					'label' => 'Kategoria',
					'disabled' => $this->disabled,
					'class' => 'AppBundle\Model\DocCat',
					'query' => DocCatQuery::create()
								->filterByYear($this->Year)
								->orderById() 
				))	
								
				->add('doc_idx', 'text', array('label' => 'Nr ewidencyjny', 'required' => false, 'disabled' => true))  
				->add('doc_no', 'text', array('label' => 'Nr dokumentu','required' => false, 'disabled' => $this->disabled))  	
								
				->add('desc', 'text', array('label' => 'Opis','required' => false, 'disabled' => $this->disabled))
				->add('comment', 'textarea', array('label' => 'Komentarz', 'required' => false)) 
			; 
		}
		else
		{
			$builder
				->add('SortedBookks', 'collection', array('label' => 'Dekretacje',
					'type' => new BookkType(
									$this->Year, 
									false,	
									$this->security_context, 
									$this->disable_accepted_docs),
				))
			;   
		}							
								
        $formModifier = function (FormInterface $form, DocCat $DocCat = null) {
			
            if ($DocCat instanceOf DocCat)
            {	
				$FileCat = $DocCat->getFileCat();
				
				if($FileCat instanceOf FileCat)
				{
					$Files = $FileCat->getFiles();
					$label = $FileCat->getName();
					$query = FileQuery::create()
								->filterByFileCat($FileCat)
								->orderByName(); 
				} else {
					$Files = array();
					$label = 'Brak kartoteki';
					$query = FileQuery::create()
								->filterByFileCat(new FileCat)
								->orderByName(); 					
				}
			}

			$form->add('File', 'model', array(
				'label' => $label,
				'disabled' => $this->disabled,
				'required' => false,
				'class' => 'AppBundle\Model\File',
				'placeholder' => 'Brak kartoteki',
				//'choices' => $Files,
				'query' => 	$query,
			));

        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {

                $data = $event->getData();
                $formModifier($event->getForm(), $data->getDocCat());
            }
        );

		if ($this->show_details) 
		{
			$builder->get('DocCat')->addEventListener(
				FormEvents::POST_SUBMIT,
				function (FormEvent $event) use ($formModifier) {

					$DocCat = $event->getForm()->getData();
					$formModifier($event->getForm()->getParent(), $DocCat);
				}
			);
        }
    }								

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Model\Doc',
		));
	}

    public function getName()
    {
        return 'doc';
    }
}
