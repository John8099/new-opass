<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Send Email notif
function sendEmail($sendTo, $content)
{
  require "vendor/PHPMailer/vendor/autoload.php";
  $mail = new PHPMailer(true);

  try {
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = 1;
    $mail->Username = "drellbatarian@gmail.com";
    $mail->Password = 'drellbatariancomplex';
    // $mail->Username = "pedro.juan42069@gmail.com";
    // $mail->Password = 'juanpedro42069';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';

    $mail->setFrom("drellbatarian@gmail.com");
    // $mail->setFrom("pedro.juan42069@gmail.com");
    $mail->addAddress($sendTo);
    $mail->addReplyTo("noreply@google.com");

    $html_body = file_get_contents('email-template.php');
    $html_body = str_replace('%content%', $content, $html_body);
    $mail->addEmbeddedImage("5.png", "logo");
    $mail->IsHTML(true);
    $mail->Subject = "OPASS";
    $mail->Body    = $html_body;
    return $mail->send();
  } catch (Exception $e) {
    return false;
  }
}

// Send sms notif
function sendSms($number, $message)
{
  $url = 'https://www.itexmo.com/php_api/api.php';
  $apicode = "TR-ONLIN583868_IUU2A";
  $passwd = '6$#bi9$k8a';
  // $apicode = "TR-STUDE423049_K6UIA";
  // $passwd = "ga)zk8$2})";
  $itexmo = array('1' => $number, '2' => strval($message), '3' => $apicode, 'passwd' => $passwd);
  $param = array(
    'http' => array(
      'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
      'method'  => 'POST',
      'content' => http_build_query($itexmo),
    ),
  );
  $context  = stream_context_create($param);
  return file_get_contents($url, false, $context);
}
