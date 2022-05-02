<?php
session_start();

include_once("conn.php");
include("functionSmsEmail.php");

$resp = array(
  "success" => false,
  "message" => ""
);

try {
  $fname = $_POST["fname"];
  $mname = $_POST["mname"];
  $lname = $_POST["lname"];
  $contactNum = $_POST["contactNum"];
  $address = $_POST["address"];
  $uname = $_POST["uname"];
  $email = $_POST["email"];
  $password = password_hash($_POST["password"], PASSWORD_ARGON2I);

  $q = null;

  if ($_GET["role"] == "user") {
    $q = mysqli_query(
      $con,
      "INSERT INTO 
      users (fname, mname, lname, email, contact, `address`, `role`, uname, `password`) 
      VALUES('$fname','$mname','$lname','$email','$contactNum','$address','user','$uname', '$password')"
    );
  } else {
    //Attorney
    $sched = $_POST["sched"];
    $exp = $_POST["exp"];
    $spec_id = $_POST["specs"];
    $q = mysqli_query(
      $con,
      "INSERT INTO 
      users (fname, mname, lname, email, contact, `address`, schedule, year_exp, specialization_id, `role`, uname, `password`) 
      VALUES('$fname', '$mname', '$lname', '$email', '$contactNum', '$address', '$sched', '$exp', '$spec_id', 'atty', '$uname', '$password')"
    );
  }

  if ($q) {
    $resp["success"] = true;
    $last_id = mysqli_insert_id($con);
    $_SESSION["id"] = $last_id;
    $otpCode = generateOTP();

    $message = "Your OTP code is: $otpCode";
    $emailSent = sendEmail($email, $message);
    $smsSent = sendSms($contactNum, $message);

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
