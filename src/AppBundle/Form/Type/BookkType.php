<?php

namespace AppBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface; 
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\SecurityContext;

use AppBundle\Model\Year;
use AppBundle\Model\DocCat;
use AppBundle\Model\Bookk;

use AppBundle\Model\MonthQuery;
use AppBundle\Model\ProjectQuery;
use AppBundle\Model\DocQuery;
use AppBundle\Model\DocCatQuery;
use AppBundle\Model\FileQuery;
use AppBundle\Model\FileCatQuery;

class BookkType extends AbstractType
{
	protected $Year;
	protected $show_details;
	protected $security_context;
	protected $disable_accepted_docs;
	
	protected $disabled;

	public function __construct (Year $Year, 
								 $show_details, 
								 SecurityContext $security_context, 
								 $disable_accepted_docs)
	{
		$this->Year = $Year;
		$this->show_details = (bool) $show_details;
		$this->security_context = $security_context;
		$this->disable_accepted_docs = (bool) $disable_accepted_docs;
	}	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {		
		$builder->add('id', 'text', array('required' => false));
				//->add('select', 'checkbox', array('required'  => false, 'mapped' => false))
				
		if ($this->show_details) {
			$builder	
				->add('cancel', 'submit', array('label' => 'Anuluj'))        
				->add('save', 'submit', array('label' => 'Zapisz'))
				->add('delete', 'submit', array('label' => 'Usuń',
					  'attr' => array('class' => 'confirm', 'data-confirm' => 'Czy chcesz usunąć dokument?'))) ;
		}
		
		$builder->addEventListener(FormEvents::POST_SET_DATA, function(FormEvent $event) {
			$form = $event->getForm();
			$Bookk = $event->getData();
			
			if($Bookk instanceOf Bookk) { 
				$this->disabled = ($Bookk->getIsAccepted() && $this->disable_accepted_docs) 
					|| !$this->security_context->isGranted('ROLE_ADMIN'); }
			else { 
				$this->disabled = false; }			
				
			$form 
				->add('is_accepted', 'checkbox', array('label' => 'Zatw. ','required'  => false, 'disabled' => $this->disabled) );				
			
			if ($this->show_details) {
				$form
					->add('desc', 'text', array('label' => 'Opis','required' => false, 'disabled' => $this->disabled))
					->add('bookking_date', 'date', array('label' => 'Data księgowania', 'required' => false, 'widget' => 'single_text', 'disabled' => $this->disabled ))				 
					->add('Project', 'model', array(
						'label' => 'Projekt',
						'disabled' => $this->disabled,
						'required' => false,
						'class' => 'AppBundle\Model\Project',
						'empty_value' => 'Brak projektu',
						'query' => ProjectQuery::create()->filterByYear($this->Year)->orderByName() ));				
				
			}			
		});					
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Model\Bookk',
		));
	}

    public function getName()
    {
        return 'bookk';
    }
}
