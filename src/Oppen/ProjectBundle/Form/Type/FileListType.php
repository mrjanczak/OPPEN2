<?php

namespace Oppen\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Oppen\ProjectBundle\Model\Year;
use Oppen\ProjectBundle\Model\YearQuery;
use Oppen\ProjectBundle\Model\FileCatQuery;

class FileListType extends AbstractType
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
            ->add('selectFiles', 'submit', array('label' => 'Wybierz'))  
            
            ->add('name', 'text', array('label' => 'Nazwa','required' => false)) 
			->add('page', 'integer', array('required' => false))
			                              
			->add('Year', 'model', array(
				'label' => 'Rok',
				'empty_value' => false,
				'required'  => false,
				'class' => 'Oppen\ProjectBundle\Model\Year',
				'query' => YearQuery::create()
							->orderByFromDate() ))

			->add('FileCat', 'model', array(
				'label' => 'Kategoria',
				'required'  => false,
				'empty_value' => false,
				'class' => 'Oppen\ProjectBundle\Model\FileCat',			
				'query' => FileCatQuery::create()
							->filterByYear($this->Year)
							->orderById() ))			

			->add('Files', 'collection', array(
				'type'          => new FileType(null, false),
				'allow_add'     => false,
				'allow_delete'  => false,
				'by_reference'  => false))
				;           
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Oppen\ProjectBundle\Model\FileList',
		));
	}

    public function getName()
    {
        return 'file_list';
    }
}
