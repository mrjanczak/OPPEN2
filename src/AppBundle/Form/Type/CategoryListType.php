<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use AppBundle\Model\Year;
use AppBundle\Model\FileCat;
use AppBundle\Model\DocCat;

use AppBundle\Model\YearQuery;

use AppBundle\Form\Type\FileCatType;
use AppBundle\Form\Type\DocCatType;

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
				'class' => 'AppBundle\Model\Year',
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
			'data_class' => 'AppBundle\Model\CategoryList',
		));
	}

    public function getName()
    {
        return 'category_list';
    }
}
