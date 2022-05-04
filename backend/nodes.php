<?php
session_start();
include_once("conn.php");

switch ($_GET["action"]) {
  case "emailExist":
    print_r(checkEmailExist($_POST["email"]));
    break;
  case "contactExist":
    print_r(contactNumberExist($_POST["contact"]));
    break;
  case "userNameExist":
    print_r(userNameExist($_POST["userName"]));
    break;
  case "validateOtp";
    print_r(confirmOtp($_POST["user_id"], $_POST["otp_code"]));
    break;
  default:
    null;
    break;
}
function confirmOtp($user_id, $code)
{
  global $con;
  $resp = array(
    "isCorrect" => false,
    "userRole" => "",
    "message" => "",
  );
  $confirmOtpQuery = mysqli_query($con, "SELECT * FROM otp WHERE `user_id`='$user_id' and code = '$code' ORDER BY id ASC LIMIT 1");
  if (mysqli_num_rows($confirmOtpQuery) > 0) {
    $user = mysqli_fetch_object(
      mysqli_query(
        $con,
        "SELECT * FROM users WHERE id='$user_id'"
      )
    );
    if ($user) {
      $resp["userRole"] = $user->role;
      $removeOTP = mysqli_query($con, "DELETE FROM otp WHERE `user_id`='$user_id'");
      if ($removeOTP) {
        $resp["isCorrect"] = true;
      }
    }
  } else {
    $resp["message"] = "Error OTP Code.";
  }
  return json_encode($resp);
}

function checkEmailExist($email)
{
  global $con;
  return mysqli_num_rows(mysqli_query($con, "SELECT email FROM users WHERE email='$email'")) > 0 ? "true" : "false";
}

function contactNumberExist($contact)
{
  global $con;
  return mysqli_num_rows(mysqli_query($con, "SELECT contact FROM users WHERE contact='$contact'")) > 0 ? "true" : "false";
}

function userNameExist($userName)
{
  global $con;
  return mysqli_num_rows(mysqli_query($con, "SELECT uname FROM users WHERE uname='$userName'")) > 0 ? "true" : "false";
}
