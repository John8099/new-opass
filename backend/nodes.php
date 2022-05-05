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
  case "checkPassword";
    print_r(checkPassword($_POST["user_id"], $_POST["password"]));
    break;
  case "updatePassword";
    print_r(updatePassword($_POST["user_id"], $_POST["password"]));
    break;
  default:
    null;
    break;
}

function updatePassword($user_id, $password)
{
  global $con;
  $resp = array(
    "success" => false,
    "message" => "",
  );
  $hashPass = password_hash($password, PASSWORD_ARGON2I);
  $updatePasswordQuery = mysqli_query(
    $con,
    "UPDATE users SET `password`='$hashPass' WHERE id = '$user_id'"
  );
  if ($updatePasswordQuery) {
    $resp["success"] = true;
  }
  return json_encode($resp);
}

function checkPassword($user_id, $password)
{
  global $con;
  $resp = array(
    "success" => false,
    "message" => "",
  );
  $userQuery = mysqli_query(
    $con,
    "SELECT * FROM users WHERE id=$user_id"
  );
  if (mysqli_num_rows($userQuery) > 0) {
    $user = mysqli_fetch_object($userQuery);
    if (password_verify($password, $user->password)) {
      $resp["success"] = true;
    } else {
      $resp["message"] = "Old Password not match";
    }
  } else {
    $resp["message"] = "Old Password not match";
  }
  return json_encode($resp);
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
