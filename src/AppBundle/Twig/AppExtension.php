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
            new \Twig_SimpleFilter(
                'md2html',
                array($this, 'markdownToHtml'),
                array('is_safe' => array('html'))
            ),
             new \Twig_SimpleFilter(
                'pl2ascii',
                array($this, 'pl2asciiFilter'),
            ),           
            
        );
    }

    public function markdownToHtml($content)
    {
        return $this->parser->toHtml($content);
    }
    
    public function pl2asciiFilter($content)
    {
        $search  = array('ą','ć','ę','ł','ń','ó','ś','ż','ź','Ą','Ć','Ę','Ł','Ń','Ó','Ś','Ż','Ź',);
        $replace = array('a','c','e','l','n','o','s','z','z','A','C','E','L','N','O','S','Z','Z',);
        $subject = 'A';
        return str_replace($search, $replace, $content);        
    }    

    public function getName()
    {
        return 'app_extension';
    }
}
