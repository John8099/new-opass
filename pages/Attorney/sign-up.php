<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Sign Up</title>

  <!-- ================= Favicon ================== -->
  <!-- Standard -->

  <!-- Styles -->
  <link href="../../assets/css/lib/font-awesome.min.css" rel="stylesheet">
  <link href="../../assets/css/lib/themify-icons.css" rel="stylesheet">
  <link href="../../assets/css/lib/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/css/lib/toastr/toastr.min.css" rel="stylesheet">
  <link href="../../assets/css/style.css" rel="stylesheet">

</head>

<body class="opass-body">

  <div class="unix-login">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-lg-5">
          <div class="login-content" style="margin:0;">
            <div class="login-logo">
              <a href="./">
                <img src="../../5.png" style="height: 150px;width: 280px;">
              </a>
            </div>
            <div class="login-form">
              <h4>Create Account</h4>
              <form id="signup-form" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                  <label class="control-label">First Name</label>
                  <input type="text" class="form-control" placeholder="First Name" name="fname" required>
                </div>
                <div class="form-group">
                  <label class="control-label">Middle Name</label>
                  <input type="text" class="form-control" placeholder="Middle Name" name="mname" required>
                </div>
                <div class="form-group">
                  <label class="control-label">Last Name</label>
                  <input type="text" class="form-control" placeholder="Last Name" name="lname" required>
                </div>
                <div class="form-group" id="contactDiv">
                  <label class="control-label">Contact Number <small style="text-transform: lowercase;">(eg. 09xxxxxxxxx)</small></label>
                  <input type="text" class="form-control" placeholder="Contact Number" id="contact" name="contactNum" required>
                </div>
                <div class="form-group">
                  <label class="control-label">Address</label>
                  <input type="text" class="form-control" placeholder="Address" name="address" required>
                </div>
                <div class="form-group">
                  <label class="control-label">Schedule</label>
                  <input type="text" class="form-control" placeholder="Schedule" name="sched" required>
                </div>
                <div class="form-group">
                  <label class="control-label">Year of Experience</label>
                  <input type="text" class="form-control" placeholder="Year of Experience" name="exp" required>
                </div>
                <div class="form-group">
                  <label class="control-label">Specialization</label>
                  <select name="specs" class="form-control">
                    <?php
                    include_once("../../backend/conn.php");
                    $specsQuery = mysqli_query(
                      $con,
                      "SELECT * FROM specialization"
                    );
                    while ($spec = mysqli_fetch_object($specsQuery)) {
                    ?>
                      <option value="<?= $spec->specialization_id ?>"><?= $spec->specialization_name ?></option>
                    <?php
                    }
                    ?>
                  </select>
                </div>
                <div class="form-group" id="unameDiv">
                  <label class="control-label">User Name</label>
                  <input type="text" class="form-control" placeholder="User Name" id="userName" name="uname" required>
                </div>
                <div class="form-group" id="emailDiv">
                  <label class="control-label">Email address</label>
                  <input type="email" class="form-control" placeholder="Email" id="email" name="email" required>
                </div>
                <div class="form-group">
                  <label class="control-label">Password</label>
                  <input type="password" class="form-control" placeholder="Password" id="password" name="password" required>
                </div>
                <div class="form-group" id="divRepeat">
                  <label class="control-label">Repeat Password</label>
                  <input type="password" class="form-control" placeholder="Repeat Password" id="repeatPassword" required>
                </div>
                <button type="submit" class="btn btn-primary m-b-30 m-t-30" style="margin-top: 30px;margin-bottom: 30px;">Register</button>
                <div class="register-link m-t-15 text-center">
                  <p>Already have account ? <a href="./"> Sign in</a></p>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>
<script src="../../assets/js/lib/jquery.min.js"></script>
<script src="../../assets/js/lib/bootstrap.min.js"></script>
<script src="../../assets/js/lib/sweetalert/sweetalert.min.js"></script>
<script src="../../assets/js/lib/toastr/toastr.min.js"></script>
<script src="../../assets/js/scripts.js"></script>


<script>
  const contact = $("#contact")
  const userName = $("#userName")
  const email = $("#email")
  const password = $("#password")
  const repeatPassword = $("#repeatPassword")
  const btnReg = $(":submit")

  toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": true,
    "progressBar": false,
    "positionClass": "toast-top-center",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
  }

  const isEmail = (email) => {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
  }

  contact.on("blur", (e) => {
    const contactVal = e.target.value;
    if ((/(\+?\d{2}?\s?\d{3}\s?\d{3}\s?\d{4})|([0]\d{3}\s?\d{3}\s?\d{4})/g).test(contactVal)) {
      $.ajax({
        url: '../../backend/nodes.php?action=contactExist',
        data: {
          contact: contactVal
        },
        type: 'POST',
        success: function(data) {
          const resp = JSON.parse(data);
          if (resp) {
            toastr["error"]("Contact number already exist.")
            btnReg.prop('disabled', true);
            $("#contactDiv").addClass("has-error")
          } else {
            btnReg.prop('disabled', false);
            $("#contactDiv").removeClass("has-error")
          }
        },
      });
    } else {
      if (contactVal === "") {
        btnReg.prop('disabled', false);
        $("#contactDiv").removeClass("has-error")
      } else {
        toastr["error"]("Error contact number format.")
        btnReg.prop('disabled', true);
        $("#contactDiv").addClass("has-error")
      }
    }
  })

  userName.on("blur", (e) => {
    $.ajax({
      url: '../../backend/nodes.php?action=userNameExist',
      data: {
        userName: e.target.value
      },
      type: 'POST',
      success: function(data) {
        const resp = JSON.parse(data);
        if (resp) {
          toastr["error"]("User name already exist.")
          btnReg.prop('disabled', true);
          $("#unameDiv").addClass("has-error")
        } else {
          btnReg.prop('disabled', false);
          $("#unameDiv").removeClass("has-error")
        }
      },

    });
  })

  email.on("blur", (e) => {
    const emailVal = e.target.value
    if (isEmail(emailVal)) {
      $.ajax({
        url: '../../backend/nodes.php?action=emailExist',
        data: {
          email: emailVal
        },
        type: 'POST',
        success: function(data) {
          const resp = JSON.parse(data);
          if (resp) {
            toastr["error"]("Email Address already exist.")
            btnReg.prop('disabled', true);
            $("#emailDiv").addClass("has-error")
          } else {
            btnReg.prop('disabled', false);
            $("#emailDiv").removeClass("has-error")
          }
        },
      });
    } else {
      if (emailVal === "") {
        btnReg.prop('disabled', false);
        $("#emailDiv").removeClass("has-error")
      } else {
        toastr["error"]("Error Email.")
        btnReg.prop('disabled', true);
        $("#emailDiv").addClass("has-error")
      }
    }
  })

  repeatPassword.on("input", (e) => {
    const passwordVal = password.val();
    if (passwordVal !== e.target.value) {
      btnReg.prop('disabled', true);
      $("#divRepeat").addClass("has-error")
    } else {
      btnReg.prop('disabled', false);
      $("#divRepeat").removeClass("has-error")
    }
  })

  $("#signup-form").on("submit", function(e) {
    swal.showLoading();
    $.ajax({
      url: '../../backend/register.php?role=atty',
      data: $(this).serialize(),
      type: 'POST',
      success: function(data) {
        swal.close();
        const resp = JSON.parse(data)
        if (resp.success) {
          swal.fire({
              title: 'Congratulations!',
              text: 'You are now registered!',
              icon: 'success',
            })
            .then(() => {
              window.location.href = "dashboard.php"
            })
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

    e.preventDefault();
  });
</script>

</html>