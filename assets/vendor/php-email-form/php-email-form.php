<?php
require 'vendor/autoload.php'; // Asegúrate de que esta línea esté al principio del archivo

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PHP_Email_Form {

  public $ajax = false;
  public $to = '';
  public $from_name = '';
  public $from_email = '';
  public $subject = '';
  public $smtp = array();

  private $errors = array();
  private $messages = array();

  function __construct() {
    // Constructor
  }

  function add_message($message, $label = '', $max_length = 0) {
    if ($max_length > 0 && strlen($message) > $max_length) {
      $this->errors[] = "The $label field exceeded the maximum length.";
      return;
    }
    $this->messages[] = array('message' => $message, 'label' => $label);
  }

  function send() {
    // Check if SMTP configuration is provided
    if (!empty($this->smtp)) {
      return $this->send_smtp_email();
    } else {
      return $this->send_mail();
    }
  }

  private function send_mail() {
    $headers = "From: " . $this->from_name . " <" . $this->from_email . ">\r\n";
    $headers .= "Reply-To: " . $this->from_email . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    $body = "";
    foreach ($this->messages as $message) {
      $body .= $message['label'] . ": " . $message['message'] . "\r\n";
    }

    if (mail($this->to, $this->subject, $body, $headers)) {
      return 'Message sent successfully!';
    } else {
      return 'Message sending failed!';
    }
  }

  private function send_smtp_email() {
    // SMTP Configuration
    $host = $this->smtp['host'];
    $username = $this->smtp['username'];
    $password = $this->smtp['password'];
    $port = $this->smtp['port'];
    $encryption = $this->smtp['encryption'];

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);
    
    try {
      // Server settings
      $mail->isSMTP();
      $mail->Host = $host;
      $mail->SMTPAuth = true;
      $mail->Username = $username;
      $mail->Password = $password;
      $mail->SMTPSecure = $encryption;
      $mail->Port = $port;

      // Recipients
      $mail->setFrom($this->from_email, $this->from_name);
      $mail->addAddress($this->to);

      // Content
      $mail->isHTML(false);
      $mail->Subject = $this->subject;
      $mail->Body = $this->get_message_body();

      $mail->send();
      return 'Message sent successfully!';
    } catch (Exception $e) {
      return 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    }
  }

  private function get_message_body() {
    $body = "";
    foreach ($this->messages as $message) {
      $body .= $message['label'] . ": " . $message['message'] . "\r\n";
    }
    return $body;
  }

  function get_errors() {
    return $this->errors;
  }
}
?>
