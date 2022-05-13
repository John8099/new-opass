<?php
session_start();
include_once("backend/conn.php");
if (!isset($_SESSION["id"])) {
  header("location: ./");
}
$user = mysqli_fetch_object(
  mysqli_query(
    $con,
    "SELECT * FROM users WHERE id = $_SESSION[id]"
  )
);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Verify OTP</title>

  <!-- ================= Favicon ================== -->

  <!-- Styles -->
  <link href="assets/css/lib/font-awesome.min.css" rel="stylesheet">
  <link href="assets/css/lib/themify-icons.css" rel="stylesheet">
  <link href="assets/css/lib/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/lib/helper.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
  <style>
    /* OTP */
    .height-100 {
      height: 100vh
    }

    .card {
      width: 400px;
      border: none;
      height: 300px;
      box-shadow: 0px 5px 20px 0px #d2dae3;
      z-index: 1;
      display: flex;
      justify-content: center;
      align-items: center
    }

    .card h6 {
      color: #000000;
      font-size: 20px
    }

    .inputs input {
      width: 40px;
      height: 40px
    }

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
      -webkit-appearance: none;
      -moz-appearance: none;
      appearance: none;
      margin: 0
    }

    .card-2 {
      background-color: #fff;
      padding: 10px;
      width: 350px;
      height: 100px;
      bottom: -50px;
      left: 20px;
      position: absolute;
      border-radius: 5px
    }

    .card-2 .content {
      margin-top: 50px
    }

    .card-2 .content a {
      color: red
    }

    .form-control:focus {
      box-shadow: none;
      border: 2px solid red
    }
  </style>
</head>

<body class="opass-body">
  <div class="container height-100 d-flex justify-content-center align-items-center">
    <div class="position-relative">
      <div class="card p-2 text-center">
        <h6>Please enter the one time password <br> to verify your account</h6>
        <div>
          <span>A code has been sent to</span>
          <small id="number">*******<?= substr($user->contact, -4); ?> </small>
          <span id="or">
            OR
          </span>
          <small id="email"><?= hideEmail(5, $user->email) ?></small>
        </div>
        <input type="text" value="<?= $user->id ?>" readonly hidden id="userID">
        <div id="otp" class="inputs d-flex flex-row justify-content-center mt-2">
          <input class="m-2 text-center form-control rounded" type="text" id="first" maxlength="1" />
          <input class="m-2 text-center form-control rounded" type="text" id="second" maxlength="1" />
          <input class="m-2 text-center form-control rounded" type="text" id="third" maxlength="1" />
          <input class="m-2 text-center form-control rounded" type="text" id="fourth" maxlength="1" />
          <input class="m-2 text-center form-control rounded" type="text" id="fifth" maxlength="1" />
          <input class="m-2 text-center form-control rounded" type="text" id="sixth" maxlength="1" />
        </div>
        <div class="mt-4">
          <button class="btn btn-danger px-4 btn-rounded" id="validate">
            Validate
          </button>
        </div>
      </div>
    </div>
  </div>

</body>
<script src="assets/js/lib/jquery.min.js"></script>
<script src="assets/js/lib/bootstrap.min.js"></script>
<script src="assets/js/lib/sweetalert/sweetalert.min.js"></script>
<script src="assets/js/lib/toastr/toastr.min.js"></script>
<script src="assets/js/scripts.js"></script>

<script>
  const response = JSON.parse(sessionStorage.getItem("response"));
  if (!response) {
    history.back();
  } else {
    $("#number").hide()
    $("#or").hide()
    $("#email").hide()
    if (response.isEmailSent || response.isSmsSent) {
      if (response.isEmailSent && response.isSmsSent) {
        $("#number").show()
        $("#or").show()
        $("#email").show()
      } else if (response.isEmailSent && !response.isSmsSent) {
        $("#email").show()
      } else {
        $("#number").show()
      }
    } else {
      if (role === "atty") {
        window.location.href = "pages/Attorney/dashboard.php"
      } else {
        window.location.href = "pages/Users/index.php"
      }
    }
  }

  $("#validate").on("click", () => {
    const code = $("#first").val() + $("#second").val() + $("#third").val() + $("#fourth").val() + $("#fifth").val() + $("#sixth").val();
    swal.showLoading();
    $.ajax({
      url: 'backend/nodes.php?action=validateOtp',
      data: {
        user_id: $("#userID").val(),
        otp_code: code
      },
      type: 'POST',
      success: function(data) {
        swal.close();
        const resp = JSON.parse(data)
        if (resp.isCorrect) {
          if (resp.userRole == "user") {
            window.location.href = 'pages/Users/index.php'
          } else {
            window.location.href = 'pages/Attorney/dashboard.php'
          }
        } else {
          swal.fire({
            title: 'Error!',
            text: resp.message,
            icon: 'error',
          })
        }
      },
      error: function(data) {
        swal.fire({
          title: 'Oops...',
          text: 'Something went wrong.',
          icon: 'error',
        })
      }
    });
  })
  document.addEventListener("DOMContentLoaded", function(event) {

    function OTPInput() {
      const inputs = document.querySelectorAll('#otp > *[id]');
      for (let i = 0; i < inputs.length; i++) {
        inputs[i].addEventListener('keydown', function(event) {
          if (event.key === "Backspace") {
            inputs[i].value = '';
            if (i !== 0) inputs[i - 1].focus();
          } else {
            if (i === inputs.length - 1 && inputs[i].value !== '') {
              return true;
            } else if (event.keyCode > 47 && event.keyCode < 58) {
              inputs[i].value = event.key;
              if (i !== inputs.length - 1) inputs[i + 1].focus();
              event.preventDefault();
            } else if (event.keyCode > 64 && event.keyCode < 91) {
              inputs[i].value = String.fromCharCode(event.keyCode);
              if (i !== inputs.length - 1) inputs[i + 1].focus();
              event.preventDefault();
            }
          }
        });
      }
    }
    OTPInput();
  });
</script>

</html>