<?php

namespace AppBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface; 
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

use FOS\UserBundle\Propel\UserQuery;

class TaskType extends AbstractType
{
	public function __construct ()
	{
	}	
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {					
		$builder 
			->add('cancel', 'submit', array('label' => 'Anuluj'))        
			->add('save', 'submit', array('label' => 'Zapisz'))
			->add('delete', 'submit', array('label' => 'Usuń', 'attr' => array(
				'class' => 'confirm', 'data-confirm' => 'Czy chcesz usunąć zadanie?') 
			))
						
			->add('id', 'text', array('required' => false))
			->add('desc', 'text', array('label' => 'Opis','required' => false))
			->add('from_date', 'date', array('label' => 'Od', 'required' => false, 'widget' => 'single_text' ))				
			->add('to_date', 'date', array('label' => 'Od', 'required' => false, 'widget' => 'single_text' ))				
			->add('comment', 'textarea', array('label' => 'Komentarz', 'required' => false))

			->add('send_reminder', 'checkbox', array('label' => 'Wyślij przypomnienie','required'  => false) )				
			
			->add('User', 'model', array(
				'required' => false,
				'label' => 'Użytkownik',
				'class' => 'FOS\UserBundle\Propel\User',
				'query' => UserQuery::create() ))							
		;								
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Model\Task',
		));
	}

    public function getName()
    {
        return 'task';
    }
}
