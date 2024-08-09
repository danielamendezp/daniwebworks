<?php

  $receiving_email_address = 'mendez.daniela1199@gmail.com';

  if( file_exists($php_email_form = '../assets/vendor/php-email-form/php-email-form.php' )) {
    include( $php_email_form );
  } else {
    die( 'Unable to load the "PHP Email Form" Library!');
  }

  $contact = new PHP_Email_Form;
  $contact->ajax = true;
  
  $contact->to = $receiving_email_address;
  $contact->from_name = $_POST['name'];
  $contact->from_email = $_POST['email'];
  $contact->subject = $_POST['subject'];

  // Uncomment below code if you want to use SMTP to send emails. You need to enter your correct SMTP credentials
  $contact->smtp = array(
    'host' => 'smtp.gmail.com',        // Host SMTP de Gmail
    'username' => 'mendez.daniela@gmail.com', // Tu dirección de correo electrónico de Gmail
    'password' => 'xx',        // Tu contraseña de Gmail (considera usar una contraseña de aplicación)
    'port' => '587',                    // Puerto SMTP para Gmail
    'encryption' => 'tls'               // Encriptación TLS
  );
  

  $contact->add_message( $_POST['name'], 'From');
  $contact->add_message( $_POST['email'], 'Email');
  $contact->add_message( $_POST['message'], 'Message', 10);

  echo $contact->send();
?>
