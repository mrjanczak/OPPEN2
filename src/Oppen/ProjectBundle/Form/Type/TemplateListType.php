<?php

namespace Oppen\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Oppen\ProjectBundle\Model\Year;
use Oppen\ProjectBundle\Model\YearQuery;

class TemplateListType extends AbstractType
{
	public function __construct () {}
		
    public function buildForm(FormBuilderInterface $builder, array $options)
    {		
        $builder
            ->add('cancel', 'submit', array('label' => 'Anuluj')) 
            ->add('search', 'submit', array('label' => 'Szukaj'))                                
            ->add('show_archived', 'checkbox', array('label' => 'Pokaż zarchiwizowane','required'  => false, 'mapped' => false))
			->add('Templates', 'collection', array(
				'type'          => new TemplateType(null, false),
				'allow_add'     => false,
				'allow_delete'  => false,
				'by_reference'  => false));           
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Oppen\ProjectBundle\Model\TemplateList',
		));
	}

    public function getName()
    {
        return 'template_list';
    }
}
