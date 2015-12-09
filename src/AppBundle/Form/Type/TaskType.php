<?php

namespace AppBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface; 
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class TaskType extends AbstractType
{
	public function __construct ()
	{
	}	
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {					
		$builder 
			->add('id', 'text', array('required' => false))
			->add('desc', 'text', array('label' => 'Opis','required' => false))
			->add('from_date', 'date', array('label' => 'Od', 'required' => false, 'widget' => 'single_text' ))				
			->add('to_date', 'date', array('label' => 'Od', 'required' => false, 'widget' => 'single_text' ))				
			->add('send_reminder', 'checkbox', array('label' => 'Wyślij przypomnienie','required'  => false) )				
			->add('comment', 'textarea', array('label' => 'Komentarz', 'required' => false))
			->add('user_id', 'number', array('label' => 'Użytkownik','required' => false));								
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
