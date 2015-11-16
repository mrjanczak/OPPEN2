<?php

namespace Oppen\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ItemCollType extends AbstractType
{	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {		
		$builder
			->add('select', 'checkbox', array('required'  => false))
			->add('id', 'text', array('required' => false));                         
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Oppen\ProjectBundle\Model\ItemColl',
		));
	}

    public function getName()
    {
        return 'item_coll';
    }
}
