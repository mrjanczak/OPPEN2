<?php

namespace AppBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

use AppBundle\Model\Year;
use AppBundle\Model\Month;
use AppBundle\Model\Account;
use AppBundle\Model\FileCat;
use AppBundle\Model\Bookk;
use AppBundle\Model\BookkEntry;

use AppBundle\Model\MonthQuery;
use AppBundle\Model\FileQuery;
use AppBundle\Model\DocCatQuery;
use AppBundle\Model\AccountQuery;


class BookkEntryType extends AbstractType
{	
	protected $Year;
	protected $security_context;
	protected $disable_accepted_docs;

	protected $disabled;
		
	public function __construct (Year $Year, $security_context, $disable_accepted_docs)  
	{
		$this->Year = $Year;
		$this->security_context = $security_context;
		$this->disable_accepted_docs = $disable_accepted_docs;		
		$this->disabled = !$this->security_context->isGranted('ROLE_ADMIN');	
	}	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {								
		$builder	
			->add('cancel', 'submit', array('label' => 'Anuluj'))  
			      
			->add('save', 'submit', array('label' => 'Zapisz','disabled' => $this->disabled))	
			
			->add('delete', 'submit', array('label' => 'Usuń','disabled' => $this->disabled,
				  'attr' => array('class' => 'confirm', 'data-confirm' => 'Czy chcesz usunąć dokument?')
			))  
			
			->add('side', 'choice', array(
				'label' => 'Strona', 'required' => true, 'disabled' => $this->disabled , 'choices' => array( 
					1 => 'Wn',
					2 => 'Ma',
				) 
			)) 				
			
			->add('value', 'number', array(
				'label' => 'Kwota', 'required' => true, 'precision' => 2, 'disabled' => $this->disabled 
			))

			->add('Account', 'model', array(
				'label' => 'Konto',
				'required' => true,
				'disabled' => $this->disabled,
				'class' => 'AppBundle\Model\Account',
				'empty_value' => 'Wybierz konto',				
				'query' => AccountQuery::create()
							->filterByYear($this->Year)
							->filterByTreeLevel(array('min'=>1))
							->orderByAccNo() 
			));
		;

        $formModifier = function (FormInterface $form, Account $Account = null) {

			for ($lev=1; $lev<=3; $lev++) {
				
				$FileCat = $Account instanceOf Account ? $Account->getFileCatLev($lev) : null;
				
				if($FileCat instanceOf FileCat) { 
					$label = $FileCat->getName();
					$Files = $FileCat->getFiles();
					$required = true;
					$query = FileQuery::create()
								->filterByFileCat($FileCat)
								->orderByName();
				}	
				else {
					$label = 'Brak kartoteki';
					$Files = array();
					$required = false;	
					$query = FileQuery::create()
								->filterByFileCat(new FileCat)
								->orderByName();					
				}	
					
				$form->add('FileLev'.$lev, 'model', array(
					'label' => $label,
					'required' => $required,	
					'disabled' => $this->disabled,			
					'class' => 'AppBundle\Model\File',
					'placeholder' => 'Wybierz kartotekę',
					//'choices' => $Files,	
					'query' => 	$query,			
				));
			}
		};
		
		$builder->addEventListener(
			FormEvents::PRE_SET_DATA, 
			function(FormEvent $event) use ($formModifier) {
			
				$data    = $event->getData();
				
				$Bookk   = $data->getBookk();			
				if($Bookk instanceof Bookk) {	
					$this->disabled = $this->disabled || ($Bookk->getIsAccepted() && $this->disable_accepted_docs); }
				
				$Account = $data->getAccount();
				$formModifier($event->getForm(), $Account );
			}
		);										
		
        $builder->get('Account')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
				
                $Account = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $Account);
            }
        );							

    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Model\BookkEntry',
		));
	}

    public function getName()
    {
        return 'bookk_entry';
    }
}
