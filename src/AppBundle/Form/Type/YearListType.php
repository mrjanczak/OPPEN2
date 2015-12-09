<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use AppBundle\Model\Year;
use AppBundle\Form\Type\FileCatType;
use AppBundle\Form\Type\DocCatType;

class YearListType extends AbstractType
{
	public $Year;
	
	public function __construct (Year $Year) 
	{
		$this->Year = $Year;		
	}	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('delete', 'submit', array('label' => 'Usuń Rok',
				'attr' => array('class' => 'confirm', 'data-confirm' => 'Czy chcesz usunąć rok?'))) 
            
			->add('Years', 'collection', array(
				'type'          => new YearType(),
				'allow_add'     => false,
				'allow_delete'  => false,
				'by_reference'  => false))
				
			->add('copy_accounts', 'checkbox', array('label' => 'Kopiuj Plan Kont','required'  => false))
			->add('copy_reports', 'checkbox', array('label' => 'Kopiuj raporty','required'  => false, ))
										
            ->add('add_next', 'submit', array('label' => 'Dodaj Następny Rok'))
            ->add('add_prev', 'submit', array('label' => 'Dodaj Poprzedni Rok'))	
            
			->add('FileCats', 'collection', array(
				'type'          => new FileCatType($this->Year, false),
				'by_reference'  => false))
				
			->add('DocCats', 'collection', array(
				'type'          => new DocCatType($this->Year, false),
				'by_reference'  => false));  
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Model\YearList',
		));
	}

    public function getName()
    {
        return 'year_list';
    }
}
