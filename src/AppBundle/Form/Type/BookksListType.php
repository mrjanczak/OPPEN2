<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use AppBundle\Model\Year;

use AppBundle\Model\MonthQuery;
use AppBundle\Model\DocCatQuery;

class BookksListType extends AbstractType
{	
	public function __construct (Year $Year)
	{
		$this->Year = $Year;
	}	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {			
        $builder
            ->add('accept', 'submit', array('label' => 'Zatwierdź'))                   
			->add('delete', 'submit', array('label' => 'Usuń')) 
            ->add('filter', 'submit', array('label' => 'Filtr'))                                      

			->add('Month', 'model', array(
				'required' => false,
				'label' => 'Miesiąc',
				'class' => 'AppBundle\Model\Month',
				'empty_value' => 'Cały rok',
				'query' => MonthQuery::create()
							->filterByYear($this->Year)
							->orderByFromDate() ))
										
			->add('AccNo', 'text', array('label' => 'Konto','required' => false))
			->add('IsAccepted', 'checkbox', array('label' => 'Zaakceptowane ','required'  => false))

			->add('Bookks', 'collection', array(
				'type'          => new BookkItemType() ));    //BookkType($this->Year)   
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Model\BookksList'));
	}

    public function getName()
    {
        return 'bookks_list';
    }
}
