<?php

namespace AppBundle\Form\Type;

use \Criteria;
use \ModelCriteria;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

use AppBundle\Model\Report;
use AppBundle\Model\ReportEntry;

use AppBundle\Model\ReportEntryQuery;

class ReportEntryType extends AbstractType
{	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {		
        $builder 
			->add('select', 'checkbox', array('required'  => false, 'mapped' => false))	
			->add('cancel', 'submit', array('label' => 'Anuluj'))        
			->add('save', 'submit', array('label' => 'Zapisz'))
			->add('delete', 'submit', array('label' => 'Usuń', 
				'attr' => array('class' => 'confirm', 'data-confirm' => 'Czy chcesz usunąć pozycję raportu?')))
			
			->add('id', 'text'    ,	array('required' => false))
			->add('name', 'textarea'  , array('label' => 'Nazwa','required' => false ))
			->add('no','text'     , array('label' => 'Numer','required' => false ))
			->add('symbol','text' , array('label' => 'Symbol','required' => false ))
			->add('formula','textarea'   , array('label' => 'Formuła','required' => false ));
			
		$builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
			$form = $event->getForm();
			$ReportEntry = $event->getData();
			$q = ReportEntryQuery::create()->orderByTreeLeft();
			
			if($ReportEntry instanceOf ReportEntry) {
				$Report = $ReportEntry->getReport(); 
				if($Report instanceOf Report) {
					$q->filterByReport($Report);}
				if($ReportEntry->hasChildren()) {
					$descendant_ids = array();
					foreach($ReportEntry->getDescendants() as $Descendant) {
						$descendant_ids[] = $Descendant->getId();}
					$q->where('report_entry.id NOT IN ?', $descendant_ids );
				}
			}
													
			$form->add('Parent', 'model', array(
				'label' => 'Poziom wyżej',
				'required' => false,
				'empty_value' => false,
				'class' => 'AppBundle\Model\ReportEntry',
				'query' => $q )); 
		});	
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Model\ReportEntry',
		));
	}

    public function getName()
    {
        return 'report_entry';
    }
}
