<?php

namespace AppBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Model\Project;
use AppBundle\Model\ProjectQuery;
use AppBundle\Model\Task;
use AppBundle\Model\TaskQuery;
use AppBundle\Model\ParameterQuery;
use AppBundle\Model\User;

use AppBundle\Form\Type\TaskType;

class TaskController extends Controller
{
   public function editAction($task_id, $project_id, Request $request) {
		
		$buttons = array('cancel','save');
		$msg = array('errors' => array(), 'warnings' => array());
 		$security_context = $this->get('security.context');
 		$User = $security_context->getToken()->getUser();
							
		if($task_id == 0) {	
			$Project = ProjectQuery::create()->findPk($project_id); 
			$Task = new Task();
			$Task->setProject($Project);
			$Task->setUser($User);
		} else {
			
			$Task = TaskQuery::create()->findPk($task_id);
			if(!($Task instanceOf Task)) {
				$msg['errors'][] = 'Zadanie '.$task_id. ' nie istnieje';
				return $this->render('AppBundle:Settings:empty_layout.html.twig', array('errors' => $msg['errors'],));
			}

			$buttons[] = 'delete';
			$Project = $Task->getProject();
		}
		
		if (!($Project instanceOf Project)) {
			$msg['errors'][] = 'Projekt zadania '.$task_id. ' nie istnieje';
			return $this->render('AppBundle:Settings:empty_layout.html.twig', array('errors' => $msg['errors'],));
		}
			
		$Year = $Project->getYear(); 									
 				
 		$form = $this->createForm(new TaskType(), $Task); 
        $form->handleRequest($request);  
		
		$params = array(
			'Project' => $Project,
			'Task'=> $Task,
			'task_id' => $task_id,
			'form' => $form->createView(),
			'errors' => $msg['errors'],
			'buttons' => $buttons,	
		);
						
		if ($form->isSubmitted()) 
		{	
			$redirect = true;
			$params['twig'] = 'AppBundle:Task:view.html.twig';	
			
			if ($form->get('save')->isClicked()) 
			{ 	
				$Task->save();
				$js = $task_id == 0 ? 'APPEND' : 'REPLACE'; 				
			}						
			
			if ($form->get('delete')->isClicked()) 
			{ 	
				$Task->delete(); 
				$js = 'REMOVE'; 
			}
			
			if ($form->get('cancel')->isClicked()) 
			{ 	
				$js = 'CANCEL';  
			}			
		} else {		
			
			$params['twig'] ='AppBundle:Task:edit.html.twig';
			$js = 'REFRESH_FORM'; 	
			
			$redirect = false;
		} 			
		
		if ($request->isXmlHttpRequest()) 
		{
			$html = $this->renderView($params['twig'], $params); 
			return new JsonResponse(array('status'=>'success', 'html'=>$html, 'js'=>$js, ), 200);
			 	
		} else {
			if (!$redirect) {			
				return $this->render('AppBundle:Template:content.html.twig', $params); 
				
			} else {
				
	$html = $this->renderView($params['twig'], $params); 			
				return $this->redirect($this->generateUrl('oppen_project',array(
					'project_id' => $project_id,
					'tab_id' => 1,
					'year_id'=> $Year->getId(),					

				) ));						
			}	
		}
	}  	
	
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
