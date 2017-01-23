<?php

namespace AppBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface; 
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

use FOS\UserBundle\Propel\User;
use FOS\UserBundle\Propel\UserQuery;

class TaskDialogType extends AbstractType
{
	public function __construct ()
	{
	}	
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {					
		$builder 
			->add('desc', 'text', array('label' => 'Opis','required' => false))
			->add('comment', 'textarea', array('label' => 'Komentarz', 'required' => false))
			->add('send_reminder', 'checkbox', array('label' => 'Wyślij przypomnienie','required'  => false) )							
			->add('User', 'model', array(
				'required' => false,
				'label' => 'Użytkownik',
				'class' => 'FOS\UserBundle\Propel\User',
				'query' => UserQuery::create() ));								
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Model\Task',
		));
	}

    public function getName()
    {
        return 'task_dialog';
    }
}
