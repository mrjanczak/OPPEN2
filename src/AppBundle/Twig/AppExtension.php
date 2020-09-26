<?php

namespace AppBundle\Twig;

use AppBundle\Utils\Markdown;

class AppExtension extends \Twig_Extension
{
    private $parser;

    public function __construct(Markdown $parser)
    {
        $this->parser = $parser;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('md2html', array($this, 'markdownToHtml') ),
            new \Twig_SimpleFilter('pl2ascii',array($this, 'pl2asciiFilter') ),                       
        );
    }

    /**
     * markdownToHtml string
     */    
    public function markdownToHtml($content)
    {
        return $this->parser->toHtml($content);
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
