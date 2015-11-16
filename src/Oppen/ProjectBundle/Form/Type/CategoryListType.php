<?php

namespace Oppen\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Oppen\ProjectBundle\Model\Year;
use Oppen\ProjectBundle\Model\FileCat;
use Oppen\ProjectBundle\Model\DocCat;

use Oppen\ProjectBundle\Model\YearQuery;

use Oppen\ProjectBundle\Form\Type\FileCatType;
use Oppen\ProjectBundle\Form\Type\DocCatType;

class CategoryListType extends AbstractType
{
	public $Year;
	
	public function __construct (Year $Year) 
	{
		$this->Year = $Year;		
	}
		
    public function buildForm(FormBuilderInterface $builder, array $options)
    {		
        $builder
            ->add('cancel', 'submit', array('label' => 'Anuluj')) 
            ->add('search', 'submit', array('label' => 'Szukaj'))    
                              
			->add('Year', 'model', array(
				'label' => 'Rok',
				'empty_value' => false,
				'required'  => false,
				'class' => 'Oppen\ProjectBundle\Model\Year',
				'query' => YearQuery::create()
							->orderByFromDate() ))		

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
			'data_class' => 'Oppen\ProjectBundle\Model\CategoryList',
		));
	}

    public function getName()
    {
        return 'category_list';
    }
}
