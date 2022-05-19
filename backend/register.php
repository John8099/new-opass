<?php
session_start();

include_once("conn.php");
include("functionSmsEmail.php");

$resp = array(
  "success" => false,
  "message" => "",
  "isEmailSent" => false,
  "isSmsSent" => false,
  "role" => ""
);

try {
  $fname = $_POST["fname"];
  $mname = $_POST["mname"];
  $lname = $_POST["lname"];
  $contactNum = $_POST["contactNum"];
  $address = $_POST["address"];
  $bday = $_POST["bday"];
  $uname = $_POST["uname"];
  $email = $_POST["email"];
  $password = md5($_POST["password"]);

  $q = null;

  if ($_GET["role"] == "user") {
    $q = mysqli_query(
      $con,
      "INSERT INTO 
      users (fname, mname, lname, email, contact, `address`, birthday, `role`, uname, `password`) 
      VALUES('$fname', '$mname', '$lname', '$email', '$contactNum', '$address', '$bday', 'user', '$uname', '$password')"
    );
    $resp["role"] = "user";
  } else {
    //Attorney
    $sched = $_POST["sched"];
    $exp = $_POST["exp"];
    $spec_id = $_POST["specs"];
    $q = mysqli_query(
      $con,
      "INSERT INTO 
      users (fname, mname, lname, email, contact, `address`,birthday, schedule, year_exp, specialization_id, `role`, uname, `password`) 
      VALUES('$fname', '$mname', '$lname', '$email', '$contactNum', '$address', '$bday', '$sched', '$exp', '$spec_id', 'atty', '$uname', '$password')"
    );
    $resp["role"] = "atty";
  }

  if ($q) {
    $resp["success"] = true;
    $last_id = mysqli_insert_id($con);
    $_SESSION["id"] = $last_id;
    $otpCode = generateOTP();

    $message = "Your OTP code is: $otpCode";
    $emailSent = sendEmail($email, $message) == 1 ? true : false;
    $smsSent = sendSms($contactNum, $message) == 0 ? true : false;

    $resp["isEmailSent"] = $emailSent;
    $resp["isSmsSent"] = $smsSent;

    $otpQuery = mysqli_query(
      $con,
      "INSERT INTO otp(`user_id`, code, is_email_sent, is_sms_sent) VALUES('$last_id','$otpCode', '$emailSent', '$smsSent')"
    );
  } else {
    $resp["message"] = mysqli_error($con);
  }
} catch (Exception $ex) {
  $resp["message"] = $ex;
}
print_r(json_encode($resp));
