<?php

namespace Oppen\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Oppen\ProjectBundle\Model\TaskQuery;

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
				->setFrom($mailer_user)
				->setTo($Task->getUser()->getEmail())
				->setBody(
					$this->renderView(
						'OppenProjectBundle:Template:remainder.txt.twig',array('Task' => $Task)) );
			$this->get('mailer')->send($message);
			
			$contents .= $Task->getDesc().'|';
		}
		
		return $this->render('OppenProjectBundle:Template:raw.html.twig',array('contents' => $contents));	
	}
}
