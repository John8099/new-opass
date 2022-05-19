<?php

$host = "localhost";
$user = "root";
$password = "";
$db = "opass2";

// $host = "sql213.epizy.com";
// $user = "epiz_31765644";
// $password = "lebcWoRB9ESXB";
// $db = "epiz_31765644_opass2";

try {
  $con = mysqli_connect($host, $user, $password, $db);
} catch (Exception $e) {
  print_r($e->getMessage());
}

//create OTP Code
function generateOTP()
{
  global $con;
  $otp = rand(100000, 999999);
  $checkOTP = mysqli_query($con, "SELECT * FROM otp WHERE code = '$otp'");
  if (mysqli_num_rows($checkOTP) > 0) {
    generateOTP($con);
  }
  return $otp;
}

function hideEmail($minFill = 4, $email)
{
  return preg_replace_callback(
    '/^(.)(.*?)([^@]?)(?=@[^@]+$)/u',
    function ($m) use ($minFill) {
      return $m[1]
        . str_repeat("*", max($minFill, mb_strlen($m[2], 'UTF-8')))
        . ($m[3] ?: $m[1]);
    },
    $email
  );
}
