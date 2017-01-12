<?php

namespace AppBundle\EventListener;

use AppBundle\Model\TaskQuery;
use AppBundle\Model\ParameterQuery;
use Symfony\Component\DependencyInjection\ContainerInterface;

class HerokuSchedulerListener
{
    protected $twig;
    protected $mailer;
    protected $container;

    public function __construct(\Twig_Environment $twig, \Swift_Mailer $mailer, ContainerInterface $container)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
        $this->container = $container;
    }
	
    public function onHerokudaily()
    {	
		$now = new \DateTime('now');
		
		//$from_email= ParameterQuery::create()->getOneByName('organization_email');
		$from_email = $this->container->getParameter('mailer_user');;	
		
		$Tasks = TaskQuery::create()
			->filterBySendReminder(1)
			->filterByFromDate(array('max' => $now))
			->filterByToDate(array('min' => $now))
			->find();
		
		$contents = 'sent reminders:';	
		foreach ($Tasks as $Task) {
			$to_email = $Task->getUser()->getEmail();
			
			$message = \Swift_Message::newInstance()
				->setSubject('Oppen Project remainder')
				->setFrom($from_email)
				->setTo($to_email)
				->setBody(
					$this->twig->render(
						'AppBundle:Template:remainder.txt.twig',array('Task' => $Task)) );
						
			$this->mailer->send($message);	

		}	
		
    }
    
    
}
