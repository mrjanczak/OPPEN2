ReportController.php
--------------------

```php

//  public function listAction($year_id, Request $request) {
//...
				$helper = $this->container->get('oneup_uploader.templating.uploader_helper');
				$endpoint = $helper->endpoint('gallery');
//...

//  public function reportAction($report_id, $method_id, Request $request) {
//...
		$summary = null;
		if ($form->get('sendMails')->isClicked()) {
			$summary = $this->sendMails($Report, $Entries, $Params, $form, $Template, $path);
		}
//...

	public function sendMails($Report, $Entries, $Params, $form, $Template, $path) {
		$ReportShortname = $Report->getShortname();
		$mailer_user = $this->container->getParameter('mailer_user');
		$translator = $this->get('translator');
		$result = '';
				
		if($Report->ItemColls != null) {
			foreach($Report->ItemColls as $ItemColl) {
				$attachment = $path . $ItemColl->data['filename'];

				$message = \Swift_Message::newInstance()
					->setSubject($form->get('mail_subject')->getData())
					->setFrom($mailer_user)
					->setTo($ItemColl->data['email'])
					->setBody($form->get('mail_body')->getData());
							
				if($form->get('mail_attach_report')->getData() && file_exists($attachment)) {
						$message->attach(Swift_Attachment::fromPath($attachment)); 	}
							
				$result .= '['.$this->get('mailer')->send($message).'] - ' . $ItemColl->data['email'] . '</br>';
			}
		}
		/*
		$data = array(array($translator->trans('headers.common.name'), 
							$translator->trans('headers.common.email'),
							$translator->trans('headers.contracts.gross'),
							$translator->trans('headers.contracts.netto'),
							$translator->trans('headers.contracts.income_cost'),
							$translator->trans('headers.contracts.tax'),
							$translator->trans('headers.common.filename'), ));
		
		foreach($Report->ItemColls as $ItemColl) {
			
			$d = $ItemColl->data;
			
			$data[] = array('name' => $d['first_name'].' '.$d['last_name'],
							'email' => $d['email'],
							'gross' => $d['gross'],
							'netto' => $d['netto'],
							'income_cost' => $d['income_cost'],
							'tax' => $d['tax'],
							'filename' => $path.$d['filename'], );
		}
		
		$summary = $this->renderView('AppBundle:Template:table.html.twig',
									  array('data' => $data ) );
		/*
		if($form->get('mail_summary')->getData()) {
			$message = \Swift_Message::newInstance()
				->setSubject('Wysyłka '.$Report->getName().' '.$Report->getYear())
				->setFrom($mailer_user)
				->setTo($this->get('security.context')->getToken()->getUser()->getEmail())
				->setBody($summary, 'text/html');	
			$this->get('mailer')->send($message);
		}
		*/
		return $result;
	}	

	public function createSHScript($Report, $Entries, $Params, $form, $Template) {
		$env = new \Twig_Environment(new \Twig_Loader_String());
		$form_data = array('objective' => $form->get('objective')->getData());
		$ReportShortname = $Report->getShortname();
		
		$contents = '';
		if($Report->ItemColls != null) {
			foreach($Report->ItemColls as $ItemColl) {
				$filename = $this->ItemColl2Filename($ReportShortname, $ItemColl->data);
				$contents .= 
					'cat <<EOT > '.$filename.'.xml'.PHP_EOL.					
					$env->render($Template->getData(),
						array('year' => $Report->getYear()->getName(),
							  'form' => $form_data,
							  'data' => $ItemColl->data,
							  'items'=> $ItemColl->Items,
							  'params'=> $Params)).
					PHP_EOL.'EOT'.PHP_EOL;
			}
		}
		elseif($Report->Items != null) {
				$contents .= 
					'cat <<EOT > '.$ReportShortname.'.xml'.PHP_EOL.					
					$env->render($Template->getData(),
						array('year' => $Report->getYear()->getName(),
							  'form' => $form_data,
							  'items'=> $Report->Items,
							  'params'=> $Params)).
					'EOT'.PHP_EOL;
		}
		return $contents;
	}	
```

pit11.html.twig
---------------

```html
</br></br>
<h2>3. Wczytaj podpisane deklaracje</h2>
<div class="s"></div>
<!-- The fileinput-button span is used to style the file input field as button -->
<span class="fileinput-button">
	<span>Wczytaj pliki...</span>
	<input id="fileupload" type="file" name="files[]" data-url="{{ oneup_uploader_endpoint('gallery') }}" multiple>
</span>
<!-- The global file processing state -->
<div id="progressbar"></div>
    
</br></br>
<h2>4. Wyślij wiadomości</h2>
<div class="s"></div>
{{ form_row(form.mail_subject,{'attr':{'class': 'text' }}) }}
{{ form_row(form.mail_body,   {'attr':{'class': 'text' }}) }}
{{ form_row(form.mail_attach_report) }}
{#{ form_row(form.mail_summary) }#}
{{ form_widget(form.sendMails) }}
			
{% if summary %}
</br></br>
<h2>5. Podsumowanie</h2>
<div class="s"></div>
{{ summary|raw }}	
{% endif %}		
```
ReportType.php
--------------

```php
			->add('mail_subject',         'text', array('label' => 'Tytuł','required'  => false, 'mapped' => false))
			->add('mail_body',            'textarea', array('label' => 'Treść','required'  => false, 'mapped' => false))
			->add('mail_attach_report',   'checkbox', array('label' => 'Załącz raport','empty_data' => true, 'required'  => false, 'mapped' => false))
			//->add('mail_summary',         'checkbox', array('label' => 'Wyślij podsumowanie','empty_data' => true, 'required'  => false, 'mapped' => false))
            ->add('sendMails', 'submit', array('label' => 'Wyślij Deklaracje'))            
```
