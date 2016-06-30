<?php

/* 
$Name = "FAWNTEST"; //senders name
$email = "fanjie@ufl.edu"; //senders e-mail adress
$recipient = "ohyeahfanfan@gmail.com"; //recipient
$mail_body = "The text for the mail..."; //mail body
$subject = "Test on fawntest"; //subject
$header = "From: ". $Name . " <" . $email . ">\r\n"; //optional headerfields
//ini_set('SMTP', 'relay.ifas.ufl.edu');
ini_set('SMTP', 'smtp.ufl.edu');
ini_set('username','fanjie@ufl.edu');
ini_set('password','xiaoke=1zhu');
ini_set('sendmail_from', $email); //Suggested by "Some Guy"
echo mail($recipient, $subject, $mail_body, $header); //mail command :)*/

if($_SERVER['SERVER_NAME'] !== 'fawn.ifas.ufl.edu'){
 require_once dirname(__FILE__)."/../Mail-1.2.0/Mail.php";
}
/* $from = "Sandra Sender <fanjie@ufl.edu>";
 $to = "Ramona Recipient <fanjie@ufl.edu>";
 $subject = "Hi!";
 $body = "Hi,\n\nHow are you?";
 $host = "ssl://smtp.gmail.com";
 $port = "465";

 $username = "ohyeahfanfan";
 $password = "FRAN362011";
 $headers = array ('From' => $from,
   'To' => $to,
   'Subject' => $subject);
 $smtp = Mail::factory('smtp',
   array ('host' => $host,
     'port' => $port,
     'auth' => true,
     'username' => $username,
     'password' => $password));
 
 $mail = $smtp->send($to, $headers, $body);
 
 if (PEAR::isError($mail)) {
   echo("<p>" . $mail->getMessage() . "</p>");
  } else {
   echo("<p>Message successfully sent!</p>");
  }*/
abstract class  Email{
 	protected $host;
 	protected $from;
 	public static function getInstance(){
 		if($_SERVER['SERVER_NAME'] === 'fawn.ifas.ufl.edu'){
 			$p_email = new ProdEmail();
 			$p_email->init();
 			return $p_email;
 		}else{
 			$t_email = new TestEmail();
 			$t_email->init();
 			return $t_email;
 		}
 		
 	}
 	public abstract function init();
 	public abstract function send($to,$subject,$body);
 	public function get($name){
 		if($name=='host'){
 			return $this->host;
 		}else if($name=='from'){
 			return $this->from;
 		}
 	}
 }
 class TestEmail extends Email{
 	protected $port;
 	protected $userName;
 	protected $password;
 	public function init(){
 		 $this->host = "ssl://smtp.gmail.com";
         $this->port = "465";
   		 $this->userName = "uffawn";
         $this->password ="fawn_036";
  		 $this->from = "uffawn@gmail.com";
 	}
 	public function send($to,$subject,$body){
 	 $headers = array ('From' => $this->from,
     'To' => $to,
     'CC' => 'uffawn@gmail.com',
     'Subject' => $subject);
 	 $mail = new Mail();
   	 $smtp = $mail->factory('smtp',
     array ('host' => $this->host,
       'port' => $this->port,
       'auth' => true,
       'username' => $this->userName,
       'password' => $this->password));
   
      $mail = $smtp->send($to, $headers, $body);
   
	    if(PEAR::isError($mail)) {
	     return $mail->getMessage();
	    } else {
	     return true;
	    }
 	}
 }
 class ProdEmail extends Email{
 	protected $cc;
 	protected $fromName;
 	public function init(){
 	$this->host = "smtp.ufl.edu";
 	$this->from = "webmaster@fawn.ifas.ufl.edu";
  	$this->cc = "webmaster@fawn.ifas.ufl.edu";
  	$this->fromName = "FAWN";
 	}
   public function send($to,$subject,$body){
    $header = 'From: '. $this->fromName . '<' . $this->from. ">\r\n".'Cc: '.$this->cc."\r\n";
    ini_set('SMTP', $this->host); //Suggested by "Some Guy"
    $mail = mail($to, $subject, $body, $header); //mail command :)
    if($mail!=1)
    $mail = false;
    else
    $mail = true;
    return $mail;
   }
 }
 /*
  class Email{
  private static $host = "ssl://smtp.gmail.com";
  private static $port = "465";
  private static $username = "uffawn";
  private static $password ="fawn_036";
  private static $from = "uffawn@gmail.com";
  
  public function sendMail($to,$subject,$body){
    $headers = array ('From' => self::$from,
     'To' => $to,
     'CC' => 'uffawn@gmail.com',
     'Subject' => $subject);
   $smtp = Mail::factory('smtp',
     array ('host' => self::$host,
       'port' => self::$port,
       'auth' => true,
       'username' => self::$username,
       'password' => self::$password));
   
   $mail = $smtp->send($to, $headers, $body);
   
   if (PEAR::isError($mail)) {
     echo("<p>" . $mail->getMessage() . "</p>");
    } else {
     echo("<p>Message successfully sent!</p>");
    }
  }
    
  }*/
 //$email = Email::getInstance();
// $rtn = $email->send('fanjie@ufl.edu', 'FAWN Freeze Alert Error', 'register failed'); 
  
 ?>