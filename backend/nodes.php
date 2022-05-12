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
  case "updateUserProfile":
    print_r(updateUserProfile());
    break;
  case "bookAppointment":
    print_r(bookAppointment());
    break;
  default:
    null;
    break;
}

function bookAppointment()
{
  global $con, $_POST, $_SESSION;

  $resp = array(
    "success" => false,
    "message" => ""
  );

  $creator = getNotificationCreator($_SESSION['id']);

  $attyId = $_POST["attyId"];
  $request = $_POST["request"];
  $requestDate = $_POST["requestDate"];
  $requestTime = $_POST["requestTime"];

  $bookAppointmentQuery = mysqli_query(
    $con,
    "INSERT INTO appointments(attorney_id, `user_id`, request, appointment_date, appointment_time, `status`) VALUES('$attyId', '$_SESSION[id]', '$request', '$requestDate', '$requestTime', 'pending')"
  );

  if ($bookAppointmentQuery) {
    $text = ucwords("$creator->fname " . $creator->mname[0] . ". $creator->lname") . " book an Appointment";
    insertNotification($attyId, $creator->id, $text);
    $resp["success"] = true;
  } else {
    $resp["message"] = mysqli_error($con);
  }

  return json_encode($resp);
}

function getNotificationCreator($id)
{

  global $con;

  $creatorQuery = mysqli_query(
    $con,
    "SELECT * FROM users WHERE id=$id"
  );

  return mysqli_fetch_object($creatorQuery);
}

function insertNotification($notify_to, $creator_id, $text)
{
  global $con;

  $query = mysqli_query(
    $con,
    "INSERT INTO notifications(notify_to, creator_id, `text`) VALUES('$notify_to', '$creator_id', '$text')"
  );

  return $query ? true : false;
}

function updateUserProfile()
{
  global $con;
  global $_POST;
  global $_FILES;

  $resp = array(
    "success" => false,
    "message" => "",
  );

  date_default_timezone_set("Asia/Manila");

  $id = $_POST["id"];
  $fname = $_POST["fname"];
  $mname = $_POST["mname"];
  $lname = $_POST["lname"];
  $email = $_POST["email"];
  $contactNumber = $_POST["contactNumber"];
  $address = $_POST["address"];
  $bday = $_POST["bday"];
  $uname = $_POST["uname"];

  $query = "";

  if (intval($_FILES["profile"]["error"]) == 0) {
    $uploadFile = date("mdY-his") . "_" . basename($_FILES['profile']['name']);
    $dir = "../profile-photo";

    if (!is_dir($dir)) {
      mkdir($dir, 0777, true);
    }
    if (move_uploaded_file($_FILES['profile']['tmp_name'], "$dir/$uploadFile")) {
      $query = "UPDATE users SET `profile`='$uploadFile', fname='$fname', mname='$mname', lname='$lname', email='$email', contact='$contactNumber', `address`='$address', birthday='$bday', uname='$uname' WHERE id='$id'";
    } else {
      $resp["message"] = "Error Uploading file.";
    }
  } else {
    $query = "UPDATE users SET fname='$fname', mname='$mname', lname='$lname', email='$email', contact='$contactNumber', `address`='$address', birthday='$bday', uname='$uname' WHERE id='$id'";
  }

  if ($resp["message"] == "") {
    $comm = mysqli_query(
      $con,
      $query
    );

    if ($comm) {
      $resp["success"] = true;
    } else {
      $resp["message"] = mysqli_error($con);
    }
  }
  return json_encode($resp);
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
  global $_POST;
  global $_SESSION;

  if (isset($_POST["isMyInfo"]) && isset($_SESSION['id'])) {
    return mysqli_num_rows(mysqli_query($con, "SELECT email FROM users WHERE email='$email' and id != $_SESSION[id]")) > 0 ? "true" : "false";
  } else {
    return mysqli_num_rows(mysqli_query($con, "SELECT email FROM users WHERE email='$email'")) > 0 ? "true" : "false";
  }
}

function contactNumberExist($contact)
{
  global $con;
  global $_POST;
  global $_SESSION;

  if (isset($_POST["isMyInfo"]) && isset($_SESSION['id'])) {
    return mysqli_num_rows(mysqli_query($con, "SELECT contact FROM users WHERE contact='$contact' and id != $_SESSION[id]")) > 0 ? "true" : "false";
  } else {
    return mysqli_num_rows(mysqli_query($con, "SELECT contact FROM users WHERE contact='$contact'")) > 0 ? "true" : "false";
  }
}

function userNameExist($userName)
{
  global $con;
  global $_POST;
  global $_SESSION;

  if (isset($_POST["isMyInfo"]) && isset($_SESSION['id'])) {
    return mysqli_num_rows(mysqli_query($con, "SELECT uname FROM users WHERE uname='$userName' and id != $_SESSION[id]")) > 0 ? "true" : "false";
  } else {
    return mysqli_num_rows(mysqli_query($con, "SELECT uname FROM users WHERE uname='$userName'")) > 0 ? "true" : "false";
  }
}
