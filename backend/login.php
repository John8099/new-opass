<?php
session_start();
include_once("conn.php");
include("functionSmsEmail.php");

$resp = array(
  "success" => false,
  "message" => ""
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
      if (password_verify($password, $user->password)) {
        $_SESSION['id'] = $user->id;
        $resp["success"] = true;
        $otpCode = generateOTP();
        $otpQuery = mysqli_query(
          $con,
          "INSERT INTO otp(`user_id`, code) VALUES('$user->id','$otpCode')"
        );
      } else {
        $resp["message"] = "Error Password";
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
