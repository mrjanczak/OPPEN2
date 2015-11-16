<?
				$fieldValues = array('001'=>'123-12-12-123',
									 '005'=>'ĄąĆćĘęŁłŃńÓóŚśŻżŹź');
				

				$processBuilder = new ProcessBuilder(
					array(
						"java",
						"-jar",
						"/home/mike/Symfony/web/pdfformfiller.jar",
					    "/home/mike/Symfony/web/pdf/pit_11_form.pdf", 					     
						"-flatten"
					)
				);
					
				$process = $processBuilder->getProcess();				 
				$process->setEnv( array( "LANG" => "pl_PL.UTF-8" ) );
				$process->setStdin( $this->createFieldsInput( $fieldValues ) );
				
				$process->run();
				if ( !$process->isSuccessful() ) {
					throw new \Exception( $process->getErrorOutput() );	}
				$data = $process->getOutput();
				
				$filename = '/home/mike/Symfony/web/pdf/003';
				file_put_contents($filename.".pdf", $data );						
				
				
				
				$path = '/home/mike/Symfony/web/pdf/';
				file_put_contents($path."002.dat", $this->createFieldsInput( $fieldValues ) );					
				
				shell_exec( "cd /home/mike/Symfony/web; ".
							"java -cp iText-5.0.6.jar:itextpdf-5.5.0.jar:itext-xtra-5.5.0.jar:. ".
							"PdfFormFiller pit_11_form.pdf ".
							"-f pdf/002.dat ".
							"pdf/002.pdf");	
				
				$path = '/home/mike/Symfony/web/';
				file_put_contents($path."pdf/001.dat", $this->createFieldsInput( $fieldValues ) );
									
				shell_exec("java -jar ".$path."pdfformfiller.jar pit_11_form.pdf ".
						   "-f ".$path."pdf/001.dat -font Ubuntu-R.ttf ".$path."pdf/001.pdf");	
											
	private function createFieldsInput( array $fieldValues )
	{
		$data = "";
		foreach ( $fieldValues as $name => $value )
		{
			$data .= $name . " " . $value . PHP_EOL;
		}
	 
		return $data;
	}
	
	function pdfff_escape($str) {
		//$str = str_replace("\\", "\\\\", $str);
		//$str = str_replace("\n", "\\n", $str);
		mb_internal_encoding('UTF-8');
		mb_regex_encoding('UTF-8');

		if (($s = mb_ereg_replace("\\", "\\\\", $str, "p")) !== false)
			$str = $s;

		// U+2028 utf-8 E280A8 : LINE SEPARATOR LS
		if (($s = mb_ereg_replace("\xE2\x80\xA8", "\\n", $str, "p")) !== false)
			$str = $s;

		//U+2029 utf-8 E280A9 : PARAGRAPH SEPARATOR PS
		if (($s = mb_ereg_replace("\xE2\x80\xA8", "\\p", $str, "p")) !== false)
			$str = $s;

		// DOS newline
		if (($s = mb_ereg_replace("\r\n", "\\n", $str, "p")) !== false)
			$str = $s;

		if (($s = mb_ereg_replace("\n", "\\n", $str, "p")) !== false)
			$str = $s;
		return $str;
	}

	function pdfff_checkbox($value) {
		if ($value)
			return 'Yes';
		return 'Off';
	}	
