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
  $uname = $_POST["uname"];
  $password = $_POST["password"];

  $query = mysqli_query(
    $con,
    "SELECT * FROM users WHERE uname='$uname'"
  );
  if ($query) {
    if (mysqli_num_rows($query) > 0) {
      $user = mysqli_fetch_object($query);
      if ($user->role !== $_GET["role"]) {
        $resp["message"] = "User not found";
      } else {
        if (password_verify($password, $user->password)) {
          $_SESSION['id'] = $user->id;
          $resp["success"] = true;
          $resp["role"] = $user->role;
          $otpCode = generateOTP();

          $message = "Your one time password is: $otpCode";
          $emailSent = sendEmail($user->email, $message) == 1 ? true : false;
          $smsSent = sendSms($user->contact, $message) == 0 ? true : false;
          // $smsSent = true;

          $resp["isEmailSent"] = $emailSent;
          $resp["isSmsSent"] = $smsSent;

          $otpQuery = mysqli_query(
            $con,
            "INSERT INTO otp(`user_id`, code, is_email_sent, is_sms_sent) VALUES('$user->id','$otpCode', '$emailSent', '$smsSent')"
          );
        } else {
          $resp["message"] = "Error Password";
        }
      }
    } else {
      $resp["message"] = "User not found";
    }
  } else {
    $resp["message"] = mysqli_error($con);
  }
} catch (Exception $ex) {
  $resp["message"] = $ex;
}
print_r(json_encode($resp));
