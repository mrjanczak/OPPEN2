<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ParameterType extends AbstractType
{	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {		
        $builder 
 				->add('id', 'text'    ,	array('required' => false))
 				->add('label', 'text'  , array('label' => 'Nazwa' ))
 				->add('name', 'text'  , array('label' => 'Symbol' ))
 				->add('field_type','text', array('label' => 'Typ' ))
 				
 				//'choice', array(
				//	'label' => 'Typ',
				//	'choices' => array('float', 'int', 'varchar', 'date') ))
					
				->add('value_float', 'number'    , array('label' => 'float'  ,'required'  => false))
				->add('value_int', 'text'        , array('label' => 'float'  ,'required'  => false))
				->add('value_varchar', 'text'    , array('label' => 'varchar','required'  => false))
				->add('value_date', 'date'       , array('label' => 'date'   ,'required'  => false, 'widget' => 'single_text'));           
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Model\Parameter',
		));
	}

    public function getName()
    {
        return 'parameter';
    }
}
