<?php

namespace Oppen\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

use Oppen\ProjectBundle\Model\TemplateQuery;

use Oppen\ProjectBundle\Form\Type\ReportEntryType;

class ReportType extends AbstractType
{	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {		
      $builder 
			->add('save', 'submit', array('label' => 'Zapisz')) 
			->add('cancel', 'submit', array('label' => 'Anuluj')) 
			->add('delete', 'submit', array('label' => 'Usuń',
				'attr' => array('class' => 'confirm', 'data-confirm' => 'Czy chcesz usunąć raport?'))) 			        
  
			->add('id', 'text'    ,	array('required' => false))
            ->add('name', 'text'    ,	array('label' => 'Nazwa','required' => false))            
            ->add('objective', 'choice', array('label' => 'Cel złożenia deklaracji','data' => true, 'required'  => false, 'mapped' => false,
											  'choices' => array(1=> 'Złożenie', 2=> 'Korekta')))
			->add('comment',            'text', array('label' => 'Komentarz','required'  => false, 'mapped' => false))

            ->add('downloadZIP', 'submit', array('label' => 'Ściągnij dane'))  
			
			->add('mail_subject',         'text', array('label' => 'Tytuł','required'  => false, 'mapped' => false))
			->add('mail_body',            'textarea', array('label' => 'Treść','required'  => false, 'mapped' => false))
			->add('mail_attach_report',   'checkbox', array('label' => 'Załącz raport','empty_data' => true, 'required'  => false, 'mapped' => false))
			->add('mail_summary',         'checkbox', array('label' => 'Wyślij podsumowanie','empty_data' => true, 'required'  => false, 'mapped' => false))
            ->add('sendMails', 'submit', array('label' => 'Wyślij Deklaracje'))            
            ->add('report_date', 'date'     , array('label' => 'Data', 'required' => false, 'widget' => 'single_text', 'mapped' => false))

			->add('Template', 'model', array(
				'label' => 'Szablon',
				'required' => false,
				'class' => 'Oppen\ProjectBundle\Model\Template',
				'empty_value' => 'Wybierz szablon',
				'query' => TemplateQuery::create()
							->filterByAsReport(1)
							->orderByName() ))	
								
			->add('ItemColls', 'collection', array(
				'type'          => new ItemCollType(),
				'by_reference'  => false)); 

		$builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
			$form = $event->getForm();
			$Report = $event->getData();			
            $form->add('shortname', 'text'    ,	array('label' => 'Skrót', 'required' => false, 'disabled' => $Report->getIsLocked()));
		});
				          
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Oppen\ProjectBundle\Model\Report',
		));
	}

    public function getName()
    {
        return 'report';
    }
}
