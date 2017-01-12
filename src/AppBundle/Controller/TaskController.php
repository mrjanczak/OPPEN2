<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Model\TaskQuery;
use AppBundle\Model\ParameterQuery;

class TaskController extends Controller
{
    public function moveAction($task_id, $dir, Request $request)
    {
		$Task = TaskQuery::create()->findPk($task_id); 
		$Project = $Task->getProject();
		$Year = $Project->getYear(); 
						
		if ($dir == 'up')   { $Task->moveUp(); }		
		if ($dir == 'down') { $Task->moveDown(); }
		
		$Task->save();
		
		return $this->redirect($this->generateUrl('oppen_project',array(
			'year_id' => $Year->getId(),
			'tab_id' => 1,
			'project_id' => $Project->getId())));		
	}
	
	public function sendReminderAction() {
		$now = new \DateTime('now');
		
		$organization_email= ParameterQuery::create()->getOneByName('organization_email');
		
		$Tasks = TaskQuery::create()
			->filterBySendReminder(1)
			->filterByFromDate(array('max' => $now))
			->filterByToDate(array('min' => $now))
			->find();
		
		$mailer_user = $this->container->getParameter('mailer_user');
		
		$contents = 'sent reminders:';	
		foreach ($Tasks as $Task) {
			$message = \Swift_Message::newInstance()
				->setSubject('Oppen Project remainder')
				->setFrom($organization_email)
				->setTo($Task->getUser()->getEmail())
				->setBody(
					$this->renderView(
						'AppBundle:Template:remainder.txt.twig',array('Task' => $Task)) );
			$this->get('mailer')->send($message);
			
			$contents .= $Task->getProject()->getName().' - '.$Task->getDesc().' > '.$Task->getUser()->getEmail().' | ';
		}
		
		return $this->render('AppBundle:Template:raw.html.twig',array('contents' => $contents));	
	}
}
