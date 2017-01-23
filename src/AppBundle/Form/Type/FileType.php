<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use AppBundle\Model\FileCat;

use AppBundle\Model\FileQuery;
use AppBundle\Model\FileCatQuery;

class FileType extends AbstractType
{	
	protected $SubFileCat;
	protected $full;	
	
	public function __construct ($SubFileCat, $full)
	{
		$this->SubFileCat = $SubFileCat;
		$this->full = $full;
	}	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {		
		$builder
			->add('select', 'checkbox', array('required'  => false, 'mapped' => false))
			->add('id', 'text', array('required' => false));                       

		if ($this->full) {			
			$builder
            ->add('cancel', 'submit', array('label' => 'Anuluj'))        
            ->add('save', 'submit', array('label' => 'Zapisz'))
            ->add('delete', 'submit', array('label' => 'Usuń',
					'attr' => array('class' => 'confirm', 'data-confirm' => 'Czy chcesz usunąć kartotekę?')))        
			
            ->add('accNo', 'text', array('label' => 'Numer','required' => false))
            ->add('name', 'text', array('label' => 'Nazwa','required' => false))
            ->add('firstName', 'text', array('label' => 'Imię','required' => false))
            ->add('secondName', 'text', array('label' => 'Drugie Imię','required' => false))
            ->add('lastName', 'text', array('label' => 'Nazwisko','required' => false))
            ->add('birth_date', 'date', array('label' => 'Data ur.','required' => false, 'widget' => 'single_text'))
            ->add('NIP', 'text', array('label' => 'NIP','required' => false))
            ->add('PESEL', 'text', array('label' => 'PESEL','required' => false))
            ->add('Passport', 'text', array('label' => 'Nr Paszportu','required' => false))
            ->add('profession', 'text', array('label' => 'Zawód','required' => false))
            
            ->add('street', 'text', array('label' => 'Ulica','required' => false))
            ->add('house', 'text', array('label' => 'Dom','required' => false, 'attr' => array('size' => 6)))
            ->add('flat', 'text', array('label' => 'Lokal','required' => false, 'attr' => array('size' => 6)))
			->add('code', 'text', array('label' => 'Kod pocz.','required' => false, 'attr' => array('size' => 6)))
            ->add('city', 'text', array('label' => 'Miasto','required' => false))
            ->add('district2', 'text', array('label' => 'Gmina','required' => false))
            ->add('district', 'text', array('label' => 'Powiat','required' => false))
			->add('province', 'choice', array(
				'label' => 'Województwo', 'required' => false, 'choices' => array( 
					'dolnośląskie' => 'dolnośląskie',
					'kujawsko-pomorskie' => 'kujawsko-pomorskie',
					'lubelskie' => 'lubelskie',
					'lubuskie' => 'lubuskie',
					'łódzkie' => 'łódzkie',
					'małopolskie' => 'małopolskie',
					'mazowieckie' => 'mazowieckie',
					'opolskie' => 'opolskie',
					'podkarpackie' => 'podkarpackie',
					'podlaskie' => 'podlaskie',
					'pomorskie' => 'pomorskie',
					'śląskie' => 'śląskie',
					'świętokrzyskie' => 'świętokrzyskie',
					'Warminsko-mazurskie' => 'Warminsko-mazurskie',
					'wielkopolskie' => 'wielkopolskie',
					'zachodniopomorskie' => 'zachodniopomorskie',
				) ))            

			->add('country', 'choice', array(
				'label' => 'Kraj', 
				'required' => false, 
				'choices' => array( 
					'AT' => 'Austria',
					'BE' => 'Belgia',
					'BG' => 'Bułgaria',
					'HR' => 'Chorwacja',
					'CY' => 'Cypr',
					'CZ' => 'Czechy',
					'DK' => 'Dania',
					'EE' => 'Estonia',
					'FI' => 'Finlandia',
					'FR' => 'Francja',
					'GR' => 'Grecja',
					'ES' => 'Hiszpania',
					'IE' => 'Irlandia',
					'LT' => 'Litwa',
					'LU' => 'Luksemburg',
					'LV' => 'Łotwa',
					'MT' => 'Malta',
					'NL' => 'Holandia',
					'DE' => 'Niemcy',
					'PL' => 'Polska',
					'PT' => 'Portugalia',
					'RO' => 'Rumunia',
					'SK' => 'Słowacja',
					'SI' => 'Słowenia',
					'SE' => 'Szwecja',
					'HU' => 'Węgry',
					'GB' => 'Wielka Brytania',
					'IT' => 'Włochy',),
				 )) 

            ->add('post_office', 'text', array('label' => 'Poczta','required' => false))
            
			->add('bankAccount', 'text', array('label' => 'Konto bankowe','required' => false, 'attr' => array('size' => 30)) )
            ->add('bankName', 'text', array('label' => 'Nazwa banku','required' => false))
            ->add('phone', 'text', array('label' => 'telefon','required' => false))
            ->add('email', 'text', array('label' => 'E-mail','required' => false));
            
			if($this->SubFileCat instanceOf FileCat) {                						
				$builder
				->add('SubFile', 'model', array(
					'label' => $this->SubFileCat->getName(),
					'required' => false,
					'class' => 'AppBundle\Model\File',
					'empty_value' => 'Brak subkartoteki',
					'query' => FileQuery::create()
								->filterByFileCat($this->SubFileCat)						
								->orderByName() ));
			}  
		}
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Model\File',
		));
	}

    public function getName()
    {
        return 'file';
    }
}
