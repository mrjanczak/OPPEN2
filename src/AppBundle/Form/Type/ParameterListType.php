<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ParameterListType extends AbstractType
{
	public function __construct () {}
		
    public function buildForm(FormBuilderInterface $builder, array $options)
    {		
        $builder
            ->add('search', 'submit', array('label' => 'Szukaj')) //dla zgodności z resztą tabow
            ->add('cancel', 'submit', array('label' => 'Anuluj')) 
            ->add('save', 'submit', array('label' => 'Zapisz')) 
                                           
 			->add('Parameters', 'collection', array(
				'type'          => new ParameterType(),
				'allow_add'     => false,
				'allow_delete'  => false,
				'by_reference'  => false));           
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Model\ParameterList',
		));
	}

    public function getName()
    {
        return 'parameter_list';
    }
}
