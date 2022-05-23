<?php
session_start();

include_once("conn.php");
include_once("functionSmsEmail.php");
$separator = "!I_I!";

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
  case "updateAttyProfile":
    print_r(updateAttyProfile());
    break;
  case "bookAppointment":
    print_r(bookAppointment());
    break;
  case "doneAppointment":
    print_r(doneAppointment());
    break;
  case "cancelAppointment":
    print_r(cancelAppointment());
    break;
  case "acceptAppointment":
    print_r(acceptAppointment());
    break;
  case "deleteAppointment":
    print_r(deleteAppointment());
    break;
  case "getNotificationCount":
    print_r(getNotificationCount());
    break;
  case "getNotificationData":
    print_r(getNotificationData());
    break;
  case "getAllNotificationData":
    print_r(getAllNotificationData());
    break;
  case "deleteNotif":
    print_r(deleteNotif());
    break;
  case "markAsSeen":
    print_r(markAsSeen());
    break;
  case "getSession":
    print_r(getSession());
    break;
  case "setOpenedAppointment":
    print_r(setOpenedAppointment());
    break;
  case "getAppointmentList":
    print_r(getAppointmentList());
    break;
  case "getTodoList":
    print_r(getTodoList());
    break;
  case "handleCheckUncheckTodo":
    print_r(handleCheckUncheckTodo());
    break;
  case "deleteTodo":
    print_r(deleteTodo());
    break;
  case "createTodo":
    print_r(createTodo());
    break;
  case "getConvo":
    print_r(getConvo());
    break;
  case "getChat":
    print_r(getChat());
    break;
  case "insertMessage":
    print_r(insertMessage());
    break;
  default:
    null;
    break;
}

function insertMessage()
{
  global $con, $_POST, $_FILES, $_SESSION, $separator;
  date_default_timezone_set("Asia/Manila");
  error_reporting(0);
  $resp = array("success" => false, "message" => "");

  $sender = getUserData($_SESSION['id']);
  $senderType = $sender->role == "user" ? "user" : "attorney";
  $message = encrypt_decrypt($_POST["message"]);

  $isFileUploaded = true;

  if ($_FILES["files"]["error"][0] == 0) {
    $file_ary = reArrayFiles($_FILES['files']);
    foreach ($file_ary as $file) {
      $uploadFileName = date("mdY-his") . $separator . basename($file['name']);
      $uploadFile = uploadFile($file["tmp_name"], $uploadFileName);
      $encryptedFileName = encrypt_decrypt($uploadFileName);

      if ($uploadFile) {
        mysqli_query(
          $con,
          "INSERT INTO chat(incoming_id, outgoing_id, sender_type, `message`, message_type) VALUES('$_POST[incoming_id]', '$_SESSION[id]', '$senderType', '$encryptedFileName', 'file')"
        );
      } else {
        $isFileUploaded = false;
        $resp["message"] = "File could not upload.\nPlease rename file and resend it.";
      }
    }
    if ($_POST["message"] != "") {
      mysqli_query(
        $con,
        "INSERT INTO chat(incoming_id, outgoing_id, sender_type, `message`, message_type) VALUES('$_POST[incoming_id]', '$_SESSION[id]', '$senderType', '$message', 'text')"
      );
    }
  } else {
    mysqli_query(
      $con,
      "INSERT INTO chat(incoming_id, outgoing_id, sender_type, `message`, message_type) VALUES('$_POST[incoming_id]', '$_SESSION[id]', '$senderType', '$message', 'text')"
    );
  }

  $firstMessageQuery = mysqli_num_rows(
    mysqli_query(
      $con,
      "SELECT * FROM chat WHERE (incoming_id=$_SESSION[id] or outgoing_id=$_SESSION[id])"
    )
  );

  if ($firstMessageQuery == 1) {
    firstMessage($_POST["incoming_id"]);
  }

  if (mysqli_error($con) == "" && $isFileUploaded) {
    $resp["success"] = true;
  }

  return json_encode($resp);
}

function firstMessage($user_id)
{
  global $con, $_SESSION;

  $user = getUserData($_SESSION["id"]);

  $name =  ucwords("$user->fname $user->lname");
  $senderType = $user->role == "user" ? "attorney" : "user";

  $toAttorney = encrypt_decrypt("Hello <strong>Atty. $name</strong>,<br>This is the <strong>Client's CHAT BOT</strong>.<br>The client must be offline.<br>Don't worry your message will be replied as soon as possible.<br><br>Thank you!");

  $toUser = encrypt_decrypt("Hello <strong>$name</strong>,<br>This is the <strong>Attorney's CHAT BOT</strong>.<br>The attorney must be offline.<br>Don't worry your message will be replied as soon as possible.<br><br>Thank you!");

  $message = $user->role == "user" ? $toUser : $toAttorney;

  $query = mysqli_query(
    $con,
    "INSERT INTO chat(incoming_id, outgoing_id, sender_type, `message`, message_type) VALUES('$_SESSION[id]', '$user_id', '$senderType', '$message', 'text')"
  );

  return $query;
}

function uploadFile($tmp_file, $file_name)
{
  if (!is_dir("media")) {
    mkdir("media", 0777, true);
  }

  return move_uploaded_file($tmp_file, "media/$file_name");
}

function reArrayFiles($file_post)
{

  $file_ary = array();
  $file_count = count($file_post['name']);
  $file_keys = array_keys($file_post);

  for ($i = 0; $i < $file_count; $i++) {
    foreach ($file_keys as $key) {
      $file_ary[$i][$key] = $file_post[$key][$i];
    }
  }

  return $file_ary;
}

function getChat()
{
  global $con, $_GET, $_SESSION;

  $html = "";
  $empty = "
  <h5 style='text-align:center'>No message to show.<br> <small>Start chatting now.</small></h5>
  ";

  $query = mysqli_query(
    $con,
    "SELECT * FROM chat c LEFT JOIN users u ON u.id = c.outgoing_id
  WHERE (c.outgoing_id = {$_SESSION['id']} AND c.incoming_id = {$_GET['incoming']})
  OR (c.outgoing_id = {$_GET['incoming']} AND c.incoming_id = {$_SESSION['id']}) ORDER BY chat_id"
  );

  while ($chat = mysqli_fetch_object($query)) {
    $profile = $chat->profile == null ? 'default.png' : $chat->profile;
    $profileDir = "../../profile-photo/$profile";

    $time = date_format(
      date_create($chat->date_created),
      "M d, Y h:i A"
    );
    if ($chat->outgoing_id === $_SESSION['id']) {
      $html .= '<div class="chat outgoing chatItem ">
                  <div class="details">
                      <p>
                        ' . formatChatMessage($chat->message, $chat->message_type) . '
                        <span class="time">
                          <br>
                          <small>' . $time . '</small>
                        </span>
                      </p>
                      
                  </div>
                  </div>';
    } else {
      $html .= '<div class="chat incoming chatItem">
                  <img src="' . $profileDir . '" alt="">
                  <div class="details">
                      <p>
                        ' . formatChatMessage($chat->message, $chat->message_type) . '
                      <span class="time">
                        <br>
                        <small>' . $time . '</small>
                      </span>
                      </p>
                  </div>
                  </div>';
    }
  }

  return $html == "" ? $empty : $html;
}

function formatChatMessage($message, $messageType)
{
  global $separator;
  $messageText = encrypt_decrypt($message, 'decrypt');

  $respMessage = "";

  if ($messageType == "file") {
    $fileName = explode($separator, $messageText)[1];
    $respMessage = "<a class='text-primary text-underline' href='../../backend/media/" . $messageText . "' download='" . $fileName . "'>" . $fileName . "</a>";
  } else {
    $respMessage = $messageText;
  }

  return $respMessage;
}

function getConvo()
{
  global $con, $_SESSION, $_GET;
  $html = "";

  $empty = "
  <h4 style='text-align:center'>No Conversation to show.</h4>
  ";

  $query = mysqli_query(
    $con,
    "SELECT * FROM chat WHERE (incoming_id=$_SESSION[id] or outgoing_id=$_SESSION[id]) and sender_type='$_GET[senderType]' GROUP BY incoming_id, outgoing_id"
  );

  $getLatestMessage = mysqli_fetch_object(
    mysqli_query(
      $con,
      "SELECT * FROM chat WHERE (incoming_id=$_SESSION[id] or outgoing_id=$_SESSION[id]) ORDER BY chat_id DESC LIMIT 1"
    )
  );

  while ($data = mysqli_fetch_object($query)) {
    $user = null;

    if ($data->incoming_id != $_SESSION['id']) {
      $user = getUserData($data->incoming_id);
    } else {
      $user = getUserData($data->outgoing_id);
    }

    $profile = $user->profile == null ? 'default.png' : $user->profile;
    $profileDir = "../../profile-photo/$profile";
    $time = time_elapsed_string($data->date_created);
    $name = $user->role == "atty" ? "Atty. " . ucwords("$user->fname " . $user->mname[0] . ". $user->lname") : ucwords("$user->fname " . $user->mname[0] . ". $user->lname");

    $decryptedText = encrypt_decrypt($getLatestMessage->message, 'decrypt');

    $text = $getLatestMessage->sender_type == $_GET["senderType"] ? "You: Send a file." : "Send a file.";

    $html .= "
      <li class='p-2 m-2' >
        <img class='pull-left mr-4 avatar-img' style='width: 40px; height: 40px;' src='$profileDir' />
        <div class='notification-content'>
          <div class='messageItem' onclick='handleMessageItemClick($user->id, \"$data->sender_type\")'>
            <small class='notification-timestamp pull-right text-primary'>
              $time
            </small>
            <div class='notification-heading'>
              $name
            </div>
            <div class='notification-text'>
              $text
            </div>
          </div>
        </div>
      </li>
    <hr>
  ";
  }

  return $html == "" ? $empty : $html;
}

function createTodo()
{
  global $con, $_POST, $_SESSION;

  $query = mysqli_query(
    $con,
    "INSERT INTO todo(attorney_id, todo_text, is_checked) VALUES('$_SESSION[id]', '$_POST[text]', 0)"
  );

  return $query;
}

function deleteTodo()
{
  global $con, $_POST;

  $query = mysqli_query(
    $con,
    "DELETE FROM todo WHERE todo_id=$_POST[todo_id]"
  );

  return $query;
}

function handleCheckUncheckTodo()
{
  global $con, $_POST;

  $isChecked = $_POST["status"] == "checked" ? 0 : 1;

  $query = mysqli_query(
    $con,
    "UPDATE todo SET is_checked=$isChecked WHERE todo_id=$_POST[todo_id]"
  );

  return $query;
}

function getTodoList()
{
  global $con, $_SESSION;
  $html = "";
  $empty = "<h4 style='text-align:center'>No Todo to show.</h4>";

  $query = mysqli_query(
    $con,
    "SELECT * FROM todo WHERE attorney_id=$_SESSION[id]"
  );

  while ($todo = mysqli_fetch_object($query)) {
    $isChecked = $todo->is_checked == 0 ? "" : "checked";
    $paramIsChecked = $todo->is_checked == 0 ? "not checked" : "checked";
    $html .= "
          <li>
            <label>
              <input type='checkbox' $isChecked onchange='checkItem($todo->todo_id, \"$paramIsChecked\")'/>
              <i></i>
              <span>
                $todo->todo_text
              </span>
              <a href='#' class='ti-close' onclick='deleteTodo($todo->todo_id)'></a>
            </label>
          </li>
    ";
  }
  return $html == "" ? $empty : $html;
}

function getAppointmentList()
{
  global $con;
  $query = mysqli_query(
    $con,
    "SELECT * FROM 
    appointments s 
    INNER JOIN 
    users u 
    ON 
    s.user_id = u.id 
    WHERE s.attorney_id = $_SESSION[id] and `status` = 'accepted'"
  );

  $arr = array();

  while ($data = mysqli_fetch_object($query)) {
    $title = ucwords("$data->request ($data->fname $data->lname)");
    $startTime = "$data->appointment_date $data->appointment_time";
    array_push($arr, [
      "title" => $title,
      "start" => $startTime
    ]);
  }
  return json_encode($arr);
}

function setOpenedAppointment()
{
  global $con, $_POST, $_SESSION;
  date_default_timezone_set("Asia/Manila");
  $resp = array(
    "success" => false,
    "message" => ""
  );

  $hasAppointment = false;

  if ($_POST['isChecked'] == "1") {
    $getAppointmentQuery = mysqli_query(
      $con,
      "SELECT * FROM appointments WHERE attorney_id=$_SESSION[id]"
    );
    $dateNow = date("Y-m-d");

    while ($appointment = mysqli_fetch_object($getAppointmentQuery)) {
      if ($dateNow == $appointment->appointment_date) {
        $hasAppointment = true;
      }
    }
  }

  if (!$hasAppointment) {
    $query = mysqli_query(
      $con,
      "UPDATE users SET opened_appointment=$_POST[isChecked] WHERE id = $_SESSION[id]"
    );
    if ($query) {
      $resp["success"] = true;
      if ($_POST["isChecked"] == "0") {
        $resp["message"] = "Successfully closed the opened appointment.<br>You can no longer receive chat from unrecognized or new users.";
      } else {
        $resp["message"] = "Successfully set to opened appointment.<br>You can now receive chat from unrecognized or new users.";
      }
    } else {
      $resp["message"] = mysqli_error($con);
    }
  } else {
    $resp["message"] = "Cannot set you still have an appointment today.";
  }

  return json_encode($resp);
}

function deleteNotif()
{
  global $con, $_POST, $_SESSION;

  $resp = array(
    "success" => false,
    "message" => ""
  );

  $query = "";
  if ($_POST["notification_id"] == "all") {
    $query = "DELETE FROM notifications WHERE notify_to=$_SESSION[id]";
  } else {
    $query = "DELETE FROM notifications WHERE notification_id=$_POST[notification_id]";
  }

  $comm = mysqli_query($con, $query);

  if ($comm) {
    $resp["success"] = true;
    $resp["message"] = "Notification(s) successfully remove";
  } else {
    $resp["success"] = false;
    $resp["message"] = mysqli_error($con);
  }

  return json_encode($resp);
}

function getAllNotificationData()
{
  global $con, $_SESSION;
  $html = "";

  $empty = "
  <h4 style='text-align:center'>No notifications to show.</h4>
  ";

  $query = mysqli_query(
    $con,
    "SELECT * FROM notifications WHERE notify_to=$_SESSION[id] ORDER BY notification_id DESC"
  );

  while ($data = mysqli_fetch_object($query)) {
    $user = mysqli_fetch_object(
      mysqli_query(
        $con,
        "SELECT * FROM users WHERE id = $data->creator_id"
      )
    );
    $profile = $user->profile == null ? 'default.png' : $user->profile;
    $profileDir = "../../profile-photo/$profile";
    $time = time_elapsed_string($data->created_at);
    $name = $user->role == "atty" ? "Atty. " . ucwords("$user->fname " . $user->mname[0] . ". $user->lname") : ucwords("$user->fname " . $user->mname[0] . ". $user->lname");
    $isActive = $data->is_seen == 1 ? "" : "active";

    $html .= "
    <li class='$isActive m-4'>
      <img class='pull-left mr-4 avatar-img' style='width: 40px; height: 40px;' src='$profileDir' />
      <div class='notification-content'>
        
        <div class='dropdown pull-right ml-3'>
            <button
            class='btn'
            id='dropdownMenuButton'
            data-toggle='dropdown'
            aria-haspopup='true'
            aria-expanded='false'
            style='background-color: transparent; border-radius: 50px;'
          >
            <i class='fa fa-ellipsis-v' aria-hidden='true'></i>
          </button>
          <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
            <a class='dropdown-item' href='#' onclick='deleteNotif($data->notification_id)'>Delete</a>
          </div>
        </div>
    
        <small class='notification-timestamp pull-right text-primary'>
          $time
        </small>
        <div class='notification-heading'>
          $name
        </div>
        <div class='notification-text'>
          $data->text
        </div>
      </div>
    </li>
    <hr>
  ";
  }

  return $html == "" ? $empty : $html;
}

function getSession()
{
  global $_SESSION;

  return json_encode(isset($_SESSION['id']));
}

function markAsSeen()
{
  global $con, $_SESSION;
  $query = mysqli_query(
    $con,
    "UPDATE notifications SET is_seen=1 WHERE notify_to = $_SESSION[id]"
  );

  return $query ? getNotificationData() : json_encode(mysqli_error($con));
}

function getNotificationData()
{
  global $con, $_SESSION;
  $html = "";
  $empty = "
  <h6 style='text-align:center'>No notifications to show.</h6>
  ";

  $query = mysqli_query(
    $con,
    "SELECT * FROM notifications WHERE notify_to=$_SESSION[id] ORDER BY notification_id DESC LIMIT 5"
  );

  while ($data = mysqli_fetch_object($query)) {
    $user = mysqli_fetch_object(
      mysqli_query(
        $con,
        "SELECT * FROM users WHERE id = $data->creator_id"
      )
    );
    $profile = $user->profile == null ? 'default.png' : $user->profile;
    $profileDir = "../../profile-photo/$profile";
    $time = time_elapsed_string($data->created_at);
    $name = $user->role == "atty" ? "Atty. " . ucwords("$user->fname " . $user->mname[0] . ". $user->lname") : ucwords("$user->fname " . $user->mname[0] . ". $user->lname");
    $isActive = $data->is_seen == 1 ? "" : "active";

    $html .= "
    <li class='$isActive'>
      <img class='pull-left m-r-10 avatar-img' src='$profileDir' />
      <div class='notification-content'>
        <small class='notification-timestamp pull-right text-primary'>
            $time
        </small>
        <div class='notification-heading'>
          $name
        </div>
        <div class='notification-text'>
          $data->text
        </div>
      </div>
    </li>
  ";
  }

  return  $html == "" ? $empty : $html;
}

function time_elapsed_string($datetime, $full = false)
{
  $now = new DateTime;
  $ago = new DateTime($datetime);
  $diff = $now->diff($ago);

  $diff->w = floor($diff->d / 7);
  $diff->d -= $diff->w * 7;

  $string = array(
    'y' => 'year',
    'm' => 'month',
    'w' => 'week',
    'd' => 'day',
    'h' => 'hour',
    'i' => 'minute',
    's' => 'second',
  );
  foreach ($string as $k => &$v) {
    if ($diff->$k) {
      $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
    } else {
      unset($string[$k]);
    }
  }

  if (!$full) $string = array_slice($string, 0, 1);
  return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function getNotificationCount()
{
  global $con, $_SESSION;

  $resp = array();

  $query = mysqli_query(
    $con,
    "SELECT * FROM notifications WHERE notify_to=$_SESSION[id] and is_seen = 0 ORDER BY notification_id DESC"
  );

  return json_encode(mysqli_num_rows($query));
}

function deleteAppointment()
{
  global $con, $_POST;

  if (count($_POST) == 0) convertUrlDataToPost();

  $resp = array(
    "success" => false,
    "message" => ""
  );
  $deleteAppointmentQuery = mysqli_query(
    $con,
    "DELETE FROM appointments WHERE appointment_id='$_POST[appointment_id]'"
  );

  if ($deleteAppointmentQuery) {
    $resp["success"] = true;
    $resp["message"] = "Successfully deleted the appointment.";
  } else {
    $resp["message"] = mysqli_error($con);
  }

  return json_encode($resp);
}

function acceptAppointment()
{
  global $con, $_POST;
  date_default_timezone_set("Asia/Manila");
  if (count($_POST) == 0) convertUrlDataToPost();

  $resp = array(
    "success" => false,
    "message" => ""
  );
  $date = date("Y-m-d");
  $acceptAppointmentQuery = mysqli_query(
    $con,
    "UPDATE appointments SET `status`='accepted', date_accepted='$date' WHERE appointment_id='$_POST[appointment_id]'"
  );

  if ($acceptAppointmentQuery) {
    // Notification
    $creator = getNotificationCreator($_SESSION['id']);
    $text = ucwords("$creator->fname " . $creator->mname[0] . ". $creator->lname") . " accepted the Appointment";
    insertNotification($_POST['notifyTo'], $creator->id, $text);

    // Send sms and email
    $appointment = getAppointmentData($_POST["appointment_id"]);
    $appointmentDate = date_format(
      date_create("$appointment->appointment_date $appointment->appointment_time"),
      "M d, Y h:i A"
    );
    $message = "Your Appointment on $appointmentDate was accepted";

    $userId = $creator->id === $appointment->attorney_id ? $appointment->user_id : $appointment->attorney_id;
    $user = getUserData($userId);

    sendEmail($user->email, $message);
    sendSms($user->contact, $message);

    $resp["success"] = true;
    $resp["message"] = "Successfully accepted the appointment.";
  } else {
    $resp["message"] = mysqli_error($con);
  }

  return json_encode($resp);
}

function cancelAppointment()
{

  global $con, $_POST;
  date_default_timezone_set("Asia/Manila");
  if (count($_POST) == 0) convertUrlDataToPost();

  $resp = array(
    "success" => false,
    "message" => ""
  );

  $date = date("Y-m-d");
  $cancelAppointmentQuery = mysqli_query(
    $con,
    "UPDATE appointments SET cancelation_reason='$_POST[inputRemark]', `status`='canceled', date_ended='$date' WHERE appointment_id='$_POST[appointment_id]'"
  );

  if ($cancelAppointmentQuery) {
    // Notification
    $creator = getNotificationCreator($_SESSION['id']);
    $text = ucwords("$creator->fname " . $creator->mname[0] . ". $creator->lname") . " canceled the Appointment";
    insertNotification($_POST['notifyTo'], $creator->id, $text);

    // Send sms and email
    $appointment = getAppointmentData($_POST["appointment_id"]);
    $appointmentDate = date_format(
      date_create("$appointment->appointment_date $appointment->appointment_time"),
      "M d, Y h:i A"
    );
    $message = "Your Appointment on $appointmentDate was canceled";

    $userId = $creator->id === $appointment->attorney_id ? $appointment->user_id : $appointment->attorney_id;
    $user = getUserData($userId);

    sendEmail($user->email, $message);
    sendSms($user->contact, $message);

    $resp["success"] = true;
    $resp["message"] = "Successfully canceled the appointment.";
  } else {
    $resp["message"] = mysqli_error($con);
  }

  return json_encode($resp);
}

function doneAppointment()
{
  date_default_timezone_set("Asia/Manila");
  global $con, $_POST;

  if (count($_POST) == 0) convertUrlDataToPost();

  $resp = array(
    "success" => false,
    "message" => ""
  );

  $date = date("Y-m-d");
  $doneAppointmentQuery = mysqli_query(
    $con,
    "UPDATE appointments SET ended_remark='$_POST[inputRemark]', `status`='done', date_ended='$date' WHERE appointment_id='$_POST[appointment_id]'"
  );

  if ($doneAppointmentQuery) {
    // Notification
    $creator = getNotificationCreator($_SESSION['id']);
    $text = ucwords("$creator->fname " . $creator->mname[0] . ". $creator->lname") . " ended the Appointment";
    insertNotification($_POST['notifyTo'], $creator->id, $text);

    // Send sms and email
    $appointment = getAppointmentData($_POST["appointment_id"]);
    $appointmentDate = date_format(
      date_create("$appointment->appointment_date $appointment->appointment_time"),
      "M d, Y h:i A"
    );
    $message = "Your Appointment on $appointmentDate was ended";

    $userId = $creator->id === $appointment->attorney_id ? $appointment->user_id : $appointment->attorney_id;
    $user = getUserData($userId);

    sendEmail($user->email, $message);
    sendSms($user->contact, $message);

    $resp["success"] = true;
    $resp["message"] = "Successfully ended the appointment.";
  } else {
    $resp["message"] = mysqli_error($con);
  }

  return json_encode($resp);
}

function convertUrlDataToPost()
{
  $entityBody = json_decode(file_get_contents('php://input'));

  foreach ($entityBody as $index => $value) {
    $_POST[$index] = $value;
  }
}

function getUserData($user_id)
{
  global $con;

  $query = mysqli_query(
    $con,
    "SELECT * FROM users WHERE id=$user_id"
  );

  return mysqli_fetch_object($query);
}

function getAppointmentData($appointmentId)
{
  global $con;

  $query = mysqli_query(
    $con,
    "SELECT * FROM appointments WHERE appointment_id=$appointmentId"
  );

  return mysqli_fetch_object($query);
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
    "INSERT INTO notifications(notify_to, creator_id, `text`, is_seen) VALUES('$notify_to', '$creator_id', '$text', false)"
  );

  return $query ? true : false;
}

function updateAttyProfile()
{
  global $con, $_POST, $_FILES;
  date_default_timezone_set("Asia/Manila");
  $resp = array(
    "success" => false,
    "message" => "",
  );



  $id = $_POST["id"];
  $fname = $_POST["fname"];
  $mname = $_POST["mname"];
  $lname = $_POST["lname"];
  $email = $_POST["email"];
  $contactNumber = $_POST["contactNumber"];
  $address = $_POST["address"];
  $bday = $_POST["bday"];
  $uname = $_POST["uname"];
  $schedule = $_POST["schedule"];
  $year_exp = $_POST["year_exp"];
  $specs = $_POST["specs"];

  $query = "";

  if (intval($_FILES["profile"]["error"]) == 0) {
    $uploadFile = date("mdY-his") . "_" . basename($_FILES['profile']['name']);
    $dir = "../profile-photo";

    if (!is_dir($dir)) {
      mkdir($dir, 0777, true);
    }
    if (move_uploaded_file($_FILES['profile']['tmp_name'], "$dir/$uploadFile")) {
      $query = "UPDATE users SET `profile`='$uploadFile', fname='$fname', mname='$mname', lname='$lname', email='$email', contact='$contactNumber', `address`='$address', birthday='$bday', schedule='$schedule', year_exp='$year_exp', specialization_id='$specs', uname='$uname' WHERE id='$id'";
    } else {
      $resp["message"] = "Error Uploading file.";
    }
  } else {
    $query = "UPDATE users SET fname='$fname', mname='$mname', lname='$lname', email='$email', contact='$contactNumber', `address`='$address', birthday='$bday', schedule='$schedule', year_exp='$year_exp', specialization_id='$specs', uname='$uname' WHERE id='$id'";
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
function updateUserProfile()
{
  global $con, $_POST, $_FILES;
  date_default_timezone_set("Asia/Manila");

  $resp = array(
    "success" => false,
    "message" => "",
  );



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

function encrypt_decrypt($string, $action = 'encrypt')
{
  $encrypt_method = "AES-256-CBC";
  $secret_key = 'AA74CDCC2BBRT935136HH7B63C27'; // user define private key
  $secret_iv = '5fgf5HJ5g27'; // user define secret key
  $key = hash('sha256', $secret_key);
  $iv = substr(hash('sha256', $secret_iv), 0, 16); // sha256 is hash_hmac_algo
  if ($action == 'encrypt') {
    $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
    $output = base64_encode($output);
  } else if ($action == 'decrypt') {
    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
  }
  return $output;
}
// echo "Your Encrypted password is = " . $pwd = encrypt_decrypt('sample', 'encrypt');
// echo "Your Decrypted password is = " . encrypt_decrypt($pwd, 'decrypt');
