<?php
	
	$_POST['name'] = preg_replace("/\r|\n/", "", $_POST['name']);
	$_POST['email'] = preg_replace("/\r|\n/", "", $_POST['email']);
	$_POST['message'] = preg_replace("/\r|\n/", "", $_POST['message']);	
	
	$name = $_POST['name'];
	$email = $_POST['email'];
	$message = $_POST['message'];
	 
	$to = 'contact@niklasplessing.net';
	$subject = 'Kontakt über www.projektgeneration.de';
	$message = $message;
	$headers = 'From: '.$name.' <'.$email.">\r\n"."Reply-To: ".$email."\r\n"."X-Mailer: PHP/" . phpversion();
	 
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) { // this line checks that we have a valid email address
		mail($to, $subject, $message,$headers) or die('Error sending Mail'); //This method sends the mail.
		
		if(isset($_POST['copymail']) && 
		$_POST['copymail'] == 'yes') 
		{
			mail($email, 'Kopie: '.$subject, $message,$headers) or die('Error sending Mail'); //This method sends the mail.	
		} 
		
		echo '<div class="alert alert-success alert-dismissable">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<strong>Ihre Nachricht wurde erfolgreich versendet!</strong> Wir antworten Ihnen sobald wie möglich.</div>';
		

	}

?>
