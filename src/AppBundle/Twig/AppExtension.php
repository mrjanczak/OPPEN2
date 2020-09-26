<?php

namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{


    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('pl2ascii',array($this, 'pl2asciiFilter') ),                       
        );
    }
    
    /**
     * pl2asciiFilter string
     */    
    public function pl2asciiFilter($content)
    {
        $search  = array('ą','ć','ę','ł','ń','ó','ś','ż','ź','Ą','Ć','Ę','Ł','Ń','Ó','Ś','Ż','Ź',);
        $replace = array('a','c','e','l','n','o','s','z','z','A','C','E','L','N','O','S','Z','Z',);
        return str_replace($search, $replace, $content);        
    }    
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'twig_extension';
    }
}
