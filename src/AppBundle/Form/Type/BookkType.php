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
    private $securityContext;
    private $disable_accepted_docs;
    protected $disabled;

	public function __construct (Year $Year, $full, 
		SecurityContext $securityContext, $disable_accepted_docs)
	{
		$this->Year = $Year;
		$this->full = $full;
		$this->securityContext = $securityContext;
		$this->disable_accepted_docs = $disable_accepted_docs;
	}	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {		
		$isAccepted = false;
		$builder->addEventListener(FormEvents::POST_SET_DATA, function(FormEvent $event) {
			$form = $event->getForm();
			$Bookk = $event->getData();
			
			if($Bookk instanceOf Bookk) { 
				$this->disabled = ($Bookk->getIsAccepted() && $this->disable_accepted_docs) 
					|| !$this->securityContext->isGranted('ROLE_ADMIN'); }
			else { 
				$this->disabled = false; }			
				
			$form 
				->add('select', 'checkbox', array('required'  => false, 'mapped' => false))
				->add('id', 'text', array('required' => false))
				->add('is_accepted', 'checkbox', array('label' => 'Zatw. ','required'  => false, 'disabled' => $this->disabled) )				
				->add('desc', 'text', array('label' => 'Opis','required' => false, 'disabled' => $this->disabled))
				->add('bookking_date', 'date', array('label' => 'Data księgowania', 'required' => false, 'widget' => 'single_text', 'disabled' => $this->disabled ))				
				->add('project_id', 'number', array('required' => false)); 
			
			if($this->full) {
				$form	                
					->add('BookkEntries', 'collection', array(
						'type'          => new BookkEntryType($this->Year, $this->disable_accepted_docs),
						'allow_add'     => true,
						'allow_delete'  => true, 
						));
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
