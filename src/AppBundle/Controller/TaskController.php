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
		
		$from_email= ParameterQuery::create()->getOneByName('organization_email');
		//$from_email = $this->getParameter('mailer_user');	
		
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
					$this->renderView(
						'AppBundle:Template:remainder.txt.twig',array('Task' => $Task)) );
			$this->get('mailer')->send($message);
			
			$contents .= $Task->getProject()->getName().' - '.$Task->getDesc().
				'( '.$from_email.' > '.$to_email.') '.PHP_EOL;
		}
		
		return $this->render('AppBundle:Template:raw.html.twig',array('contents' => $contents));	
	}
}
