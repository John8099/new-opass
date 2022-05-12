<?php
session_start();
include_once("conn.php");

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

$notif_query = mysqli_query(
  $con,
  "SELECT * FROM `notification` WHERE notify_to = $_SESSION[id]"
);
$arr = array(
  "numRows" => mysqli_num_rows($notif_query)
);

echo json_encode($arr);
sleep(5);
flush();
