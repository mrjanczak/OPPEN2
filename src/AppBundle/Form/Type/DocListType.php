<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;

use AppBundle\Model\Year;
use AppBundle\Model\Project;
use AppBundle\Model\DocCat;

use AppBundle\Model\YearQuery;
use AppBundle\Model\MonthQuery;
use AppBundle\Model\DocCatQuery;

class DocListType extends AbstractType
{	
	public $Year;
	public $Project;
	
	public $as_income_docs;
	public $as_cost_docs;
	protected $securityContext;
	protected $disable_accepted_docs;
	
	public function __construct (Year $Year, $Project, $as_income_docs, $as_cost_docs, 
		SecurityContext $securityContext, $disable_accepted_docs) 
	{
		$this->Year = $Year;		// always defined to correctly create Docs collection
									// however available months (assigned to project) are not limited to this year					
		$this->Project = $Project;	// defined only for Project > Documents tab
									// in this case Month has to be selected (no option for all months)
		$this->as_income_docs = $as_income_docs;	
		$this->as_cost_docs = $as_cost_docs;	
		$this->securityContext = $securityContext;
		$this->disable_accepted_docs = $disable_accepted_docs;
	}	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {			
        $builder
            ->add('cancel', 'submit', array('label' => 'Anuluj')) 
            ->add('search', 'submit', array('label' => 'Szukaj'))   
            ->add('selectDocs', 'submit', array('label' => 'Wybierz'))                                       
            ->add('acceptBookks', 'submit', array('label' => 'Zatwierdź Dekretacje'))
			->add('deleteBookks', 'submit', array('label' => 'Usuń Dekretacje'))            

			->add('showBookks','choice', array( 'label' => 'Pokaż dekretacje', 'empty_value' => false, 'choices'   => array(
					'-2' => 'Żadne','-1' => 'Wszystkie', '0' => 'Nie zatw.', '1' => 'Zatw.'), 'required'  => false))            
			->add('desc', 'text', array('label' => 'Opis','required' => false))
			->add('page', 'integer', array('required' => false))
			
			->add('Year', 'model', array(
				'label' => 'Rok',
				'empty_value' => false,
				'required'  => false,
				'class' => 'AppBundle\Model\Year',
				'query' => YearQuery::create()
							->orderByFromDate() ))

			->add('Month', 'model', array(
				'label' => 'Miesiąc',
				'empty_value' => 'Wszystkie',
				'empty_data'  => -1,
				'required'  => false,
				'class' => 'AppBundle\Model\Month',
				'query' => MonthQuery::create()
							->filterByYear($this->Year)
							->orderByFromDate() ))
					
			->add('DocCat', 'model', array(
				'label' => 'Kategoria',
				'empty_value' => 'Wszystkie',
				'empty_data'  => -1,
				'required'  => false,
				'class' => 'AppBundle\Model\DocCat',			
				'query' => DocCatQuery::create()
							->filterByYear($this->Year)
							->_if($this->as_income_docs == 1) 
								->filterByAsIncome(1)	
							->_elseif($this->as_cost_docs == 1) 
								->filterByAsCost(1)
							->_endif()
							->orderById()  ))

			->add('Docs', 'collection', array(
				'type'          => new DocType($this->Year, false, false, $this->securityContext, $this->disable_accepted_docs),
				'allow_add'     => false,
				'allow_delete'  => false,
				'by_reference'  => false))
			
			;        
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Model\DocList'));
	}

    public function getName()
    {
        return 'doc_list';
    }
}
