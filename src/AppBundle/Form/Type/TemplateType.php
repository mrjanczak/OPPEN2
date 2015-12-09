<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TemplateType extends AbstractType
{	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {		
        $builder 
            ->add('save', 'submit'  , array('label' => 'Zapisz'))  
            ->add('cancel', 'submit', array('label' => 'Anuluj')) 
            ->add('delete', 'submit', array('label' => 'Usuń',
				'attr' => array('class' => 'confirm', 'data-confirm' => 'Czy chcesz usunąć szablon?'))) 
            
            ->add('id', 'text'           , array('label' => 'Id', 'required'  => false))
            ->add('name', 'text'           , array('label' => 'Nazwa'))
            ->add('symbol', 'text'      , array('label' => 'Symbol', 'required'  => false))
            ->add('as_contract', 'checkbox', array('label' => 'Jako umowa', 'required'  => false))
			->add('as_report', 'checkbox'  , array('label' => 'Jako raport','required'  => false))
			->add('as_booking', 'checkbox'  , array('label' => 'Jako księgowanie','required'  => false))
			->add('as_transfer', 'checkbox'  , array('label' => 'Jako przelew','required'  => false))
            ->add('contents', 'textarea'   , array('label' => 'Tekst','required'  => false))
            ->add('data', 'textarea'   , array('label' => 'YAML/Twig','required'  => false));           
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Model\Template',
		));
	}

    public function getName()
    {
        return 'template';
    }
}
