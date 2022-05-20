<?php

// Send sms notif
function sendSms($number, $message)
{
  $url = 'https://www.itexmo.com/php_api/api.php';
  $apicode = "TR-ONLIN583868_IUU2A";
  $passwd = '6$#bi9$k8a';
  // $apicode = "TR-STUDE423049_K6UIA";
  // $passwd = "ga)zk8$2})";
  $itexmo = array('1' => strval($number), '2' => strval($message), '3' => $apicode, 'passwd' => $passwd);
  $param = array(
    'http' => array(
      'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
      'method'  => 'POST',
      'content' => http_build_query($itexmo),
    ),
  );
  $context  = stream_context_create($param);
  $fileContents = file_get_contents($url, false, $context);
  return $fileContents;

  // $ch = curl_init();
  // $itexmo = array('1' => $number, '2' => $message, '3' => $apicode, 'passwd' => $passwd);
  // curl_setopt($ch, CURLOPT_URL, $url);
  // curl_setopt($ch, CURLOPT_POST, 1);
  // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($itexmo));
  // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  // $exec =  curl_exec($ch);
  // return $exec;
  // curl_close($ch);
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once('../assets/vendor/autoload.php');

// Send Email notif
function sendEmail($sendTo, $content)
{
  $mail = new PHPMailer(true);

  try {
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = 1;
    $mail->Username = "opass6460@gmail.com";
    $mail->Password = 'opass12345';
    // $mail->Username = "drellbatarian@gmail.com";
    // $mail->Password = 'drellbatariancomplex';
    // $mail->Username = "pedro.juan42069@gmail.com";
    // $mail->Password = 'juanpedro42069';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';

    $mail->setFrom("opass6460@gmail.com", "Online Public Attorney Scheduling System");
    // $mail->setFrom("pedro.juan42069@gmail.com");
    $mail->addAddress($sendTo);
    $mail->addReplyTo("noreply@google.com");

    $html_body = file_get_contents('email-template.php');
    $html_body = str_replace('%content%', $content, $html_body);
    $mail->addEmbeddedImage("../5.png", "logo");
    $mail->IsHTML(true);
    $mail->Subject = "OPASS OTP Code";
    $mail->Body    = $html_body;
    return $mail->send();
  } catch (Exception $e) {
    return false;
  }
}
